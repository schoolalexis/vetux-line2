<?php


namespace App\Service;

use League\Csv\Reader;

class Csv
{
    /**
     * @return string[]
     */
    public static function getSpecificColumns(): array
    {
        return ['Gender', 'Title', 'Surname', 'GivenName', 'EmailAddress', 'Birthday', 'TelephoneNumber', 'CCType', 'CCNumber', 'CVV2', 'CCExpires', 'StreetAddress', 'City', 'ZipCode', 'CountryFull', 'Centimeters', 'Kilograms', 'Vehicle', 'Latitude', 'Longitude'];
    }

    /**
     * @param $csvDescriptor
     * @return string[]
     * @throws \League\Csv\Exception
     */
    public static function getCsvHeader($csvDescriptor): array
    {
        return Reader::createFromPath($csvDescriptor)->setHeaderOffset(0)->getHeader();
    }

    /**
     * @param $uploadedCsvHeader
     * @return bool
     */
    public static function isValidCsvHeader($uploadedCsvHeader): bool
    {
        $uploadedCsvHeader = array_map('strtolower', $uploadedCsvHeader);
        $i = 0;
        $csvColumns = array_map('strtolower', Csv::getSpecificColumns());
        foreach ($uploadedCsvHeader as $value){
            if (in_array($value, $csvColumns)){
                $i++;
            }
        }
        return $i === count($csvColumns);
    }

    /**
     * @param $csvName
     * @return string
     */
    public static function getValidCsvName($csvName): string
    {
        $tab = [' ', '²', '~', '"', '\'', '{', '(', '[', '|', '`', '\\', '^', '°', ')', ']', '+', '=', '}', '¤', '*', '?', ',', '.', ';', '/', ':', '§', '!'];
        $replacement = '-';
        return str_replace($tab, $replacement, $csvName).".csv";
    }

    /**
     * @param $csvData
     * @return array
     */
    public static function getArrayOfCsvCcNumber($csv1Data, $csv2Data = []): array
    {
        $arrayOfCcNumber = [];
        foreach ($csv1Data as $data) {
            $data = array_change_key_case($data, CASE_LOWER);
            $arrayOfCcNumber[] = $data["ccnumber"];
        }
        foreach ($csv2Data as $data) {
            $data = array_change_key_case($data, CASE_LOWER);
            $arrayOfCcNumber[] = $data["ccnumber"];
        }
        return $arrayOfCcNumber;
    }

    /**
     * @param []
     * @return []
     */
    public static function getDuplicateValueInArray($array): array
    {
        $rValue = [];

        $arrayUnique = array_unique($array);

        if (count($array) - count($arrayUnique)){
            for ($i=0; $i<count($array); $i++) {
                if (!array_key_exists($i, $arrayUnique)){
                    $rValue[] = $array[$i];
                }
            }
        }
        
        return array_unique($rValue);
    }

}