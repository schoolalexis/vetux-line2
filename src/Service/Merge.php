<?php

namespace App\Service;

use League\Csv\Reader;
use League\Csv\Writer;

class Merge
{
    private $columns;

    private $csv1;

    private $csv2;

    private $mergeCsv;

    private $mergeCsvName;

    private $arrayOfCsvCcNumber;

    /**
     * @var int
     * 0 si le aucun client n'a été inséré
     * 1 si l'inverse
     */
    private $insert;

    /**
     * Merge constructor.
     * @param $csv1Descriptor
     * @param $csv2Descriptor
     * @param $mergeCsvName
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function __construct($csv1Descriptor, $csv2Descriptor, $mergeCsvName = "default-name"){
        $this->columns = array_map('strtolower', Csv::getSpecificColumns());
        $this->csv1 = Reader::createFromPath($csv1Descriptor)->setHeaderOffset(0);
        $this->csv2 = Reader::createFromPath($csv2Descriptor)->setHeaderOffset(0);
        $this->mergeCsv = Writer::createFromFileObject(new \SplTempFileObject());
        $this->mergeCsv->insertOne(Csv::getSpecificColumns());
        $this->mergeCsvName = $mergeCsvName;
        $this->arrayOfCsvCcNumber = Csv::getArrayOfCsvCcNumber($this->csv1->getRecords(), $this->csv2->getRecords());
        $this->insert = 0;
    }

    /**
     * @param $csvData
     * @throws \League\Csv\CannotInsertRecord
     */
    private function insertIntoCsvForSequential($csvData){
        foreach ($csvData as $data) {
            $data = array_change_key_case($data, CASE_LOWER);

            $content = [];
            foreach ($this->columns as $column) {
                $content[] = $data[$column];
            }

            if(Verification::isMajor($data["birthday"]) && Verification::isValidSize($data["feetinches"], $data["centimeters"]) && !in_array($data["ccnumber"],  Csv::getDuplicateValueInArray($this->arrayOfCsvCcNumber))) {
                $this->mergeCsv->insertOne($content);
                $this->insert = 1;
            }
        }
    }

    /**
     * @return bool
     */
    public function sequential(): bool
    {
        $this->insertIntoCsvForSequential($this->csv1->getRecords());
        $this->insertIntoCsvForSequential($this->csv2->getRecords());
        return $this->insert === 1;
    }

    /**
     * @return bool
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function interlaced(): bool
    {

        $max = ($this->csv1->count() > $this->csv2->count()) ? $this->csv1->count() : $this->csv2->count();

        $csv1Index = 0;
        $csv2Index = 0;

        do{
            $customerIsInserted = false;
            while($customerIsInserted == false && $csv1Index < $this->csv1->count()){
                $content = [];
                foreach ($this->columns as $column) {
                    $data = array_change_key_case($this->csv1->fetchOne($csv1Index), CASE_LOWER);
                    $content[] = $data[$column];
                }
                if(Verification::isMajor($data["birthday"]) && Verification::isValidSize($data["feetinches"], $data["centimeters"]) && !in_array($data["ccnumber"],  Csv::getDuplicateValueInArray($this->arrayOfCsvCcNumber))) {
                    $this->mergeCsv->insertOne($content);
                    $customerIsInserted = true;
                    $this->insert = 1;
                }
                $csv1Index++;
            }

            $customerIsInserted = false;
            while($customerIsInserted == false && $csv2Index < $this->csv2->count()){
                $content = [];
                foreach ($this->columns as $column) {
                    $data = array_change_key_case($this->csv2->fetchOne($csv2Index), CASE_LOWER);
                    $content[] = $data[$column];
                }
                if(Verification::isMajor($data["birthday"]) && Verification::isValidSize($data["feetinches"], $data["centimeters"]) && !in_array($data["ccnumber"],  Csv::getDuplicateValueInArray($this->arrayOfCsvCcNumber))) {
                    $this->mergeCsv->insertOne($content);
                    $customerIsInserted = true;
                    $this->insert = 1;
                }
                $csv2Index++;
            }

            $max--;
        }while($max > 0);

        return $this->insert === 1;
    }

    /**
     * @return int
     */
    public function downloadCsv(): int
    {
        return $this->mergeCsv->output(Csv::getValidCsvName($this->mergeCsvName));
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return \League\Csv\AbstractCsv|Reader
     */
    public function getCsv1()
    {
        return $this->csv1;
    }

    /**
     * @return \League\Csv\AbstractCsv|Reader
     */
    public function getCsv2()
    {
        return $this->csv2;
    }

    /**
     * @return Writer
     */
    public function getMergeCsv(): Writer
    {
        return $this->mergeCsv;
    }

    /**
     * @return mixed
     */
    public function getMergeCsvName(): mixed
    {
        return $this->mergeCsvName;
    }

    /**
     * @return array
     */
    public function getArrayOfCsvCcNumber(): array
    {
        return $this->arrayOfCsvCcNumber;
    }

}