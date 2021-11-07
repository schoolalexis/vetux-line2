<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Mark;
use App\Entity\Vehicule;


class Etl
{

    /**
     * @param $entityManager
     * @param $data
     */
    private function addCustomers($entityManager, $data, $markRepository){
        $customer = new Customer();
        $customer->setGender($data["gender"]);
        $customer->setTitle($data["title"]);
        $customer->setSurname($data["surname"]);
        $customer->setGivenName($data["givenname"]);
        $customer->setEmailAddress($data["emailaddress"]);
        $customer->setBirthday($data["birthday"]);
        $customer->setTelephoneNumber($data["telephonenumber"]);
        $customer->setCCType($data["cctype"]);
        $customer->setCcNumber($data["ccnumber"]);
        $customer->setCvv2($data["cvv2"]);
        $customer->setCCExpires($data["ccexpires"]);
        $customer->setStreetAddress($data["streetaddress"]);
        $customer->setCity($data["city"]);
        $customer->setZipCode($data["zipcode"]);
        $customer->setCountryFull($data["countryfull"]);
        $customer->setCentimeters($data["centimeters"]);
        $customer->setKilograms($data["kilograms"]);
        $explodeVehicule = \explode(" ", $data["Vehicule"]);
        $Vehicule = new Vehicule();
        $Vehicule->setYear($explodeVehicule[0]);
        $Vehicule->setModel($explodeVehicule[2]);
        if ($markRepository->findOneBy(["name" => $explodeVehicule[1]])){
            $VehiculeMark = $markRepository->findOneBy(["name" => $explodeVehicule[1]]);
        }else{
            $VehiculeMark = new Mark();
            $VehiculeMark->setName($explodeVehicule[1]);
        }
        $VehiculeMark->addVehicule($Vehicule);
        $customer->setVehicule($Vehicule);
        $Vehicule->setMark($VehiculeMark);
        $Vehicule->addCustomer($customer);
        $customer->setLatitude($data["latitude"]);
        $customer->setLongitude($data["longitude"]);
        $entityManager->persist($customer);
    }

    /**
     * Extract transform load
     * @param $csvData
     * @param $entityManager
     * @param $customerRepository
     * @return int[]
     */
    public static function etl($csv, $entityManager, $customerRepository, $markRepository){
        $isValidColumns = 1;
        $customerExist = 1;
        $isMajor = 1;
        $isValidSize = 1;
        $isValidBankIdentidiers = 1;
        $added = 0;

        if(Csv::isValidCsvHeader($csv->getHeader())){
            foreach($csv->getRecords() as $data){
                $data = array_change_key_case($data, CASE_LOWER);
                if (!$customerRepository->findOneBy(["ccNumber" => $data["ccnumber"]])) {
                    if (Verification::isMajor($data["birthday"])) {
                        if (array_key_exists("feetinches", $data)) {
                            if (Verification::isValidSize($data["feetinches"], $data["centimeters"])) {
                                (new Etl)->addCustomers($entityManager, $data, $markRepository);
                                $added = 1;
                            } else {
                                $isValidSize = 0;
                            }
                        } else {
                            if (!in_array($data["ccnumber"], Csv::getDuplicateValueInArray(Csv::getArrayOfCsvCcNumber($csv)))) {
                                (new Etl)->addCustomers($entityManager, $data, $markRepository);
                                $added = 1;
                            } else {
                                $isValidBankIdentidiers = 0;
                            }
                        }
                    } else {
                        $isMajor = 0;
                    }
                }else{
                    $customerExist = 0;
                }
            }
        }else{
            $isValidColumns = 0;
        }

        return ["isValidColumns" => $isValidColumns, "customerExist" => $customerExist, "isMajor" => $isMajor, "isValidSize" => $isValidSize, "isValidBankIdentidiers" => $isValidBankIdentidiers, "added" => $added];
    }

}