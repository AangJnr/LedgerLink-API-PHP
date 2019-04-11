<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of VslaRepository
 *
 * @author JCapito
 */
use App\Model\Vsla;
use App\Repository\VslaRegionRepo;
use App\Repository\TechnicalTrainerRepo;
use App\Repository\FinancialInstitutionRepo;
use PDO;
use App\Helpers\DatabaseHandler;


class VslaRepo {
    //put your code here
    protected $ID;
    protected $vsla;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->vsla = new Vsla();
        $this->db = DatabaseHandler::getInstance();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from vsla where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->vsla->setID($object["id"]);
            $this->vsla->setVslaCode($object["VslaCode"]);
            $this->vsla->setVslaName($object["VslaName"]);
            $this->vsla->setVslaPhoneMsisdn($object["VslaPhoneMsisdn"]);
            $this->vsla->setPhysicalLocation($object["PhysicalAddress"]);
            $this->vsla->setGpsLocation($object["GpsLocation"]);
            $this->vsla->setDateRegistered($object["DateRegistered"]);
            $this->vsla->setDateLinked($object["DateLinked"]);
            $this->vsla->setContactPerson($object["ContactPerson"]);
            $this->vsla->setPositionInVsla($object["PositionInVsla"]);
            $this->vsla->setPhoneNumber($object["PhoneNumber"]);
            $this->vsla->setStatus($object["Status"]);
            $this->vsla->setGroupAccountNumber($object["GroupAccountNumber"]);
            $this->vsla->setNumberOfCycles($object["NumberOfCycles"]);
            $this->vsla->setImplementer($object["Implementer"]);
            $this->vsla->setTechnicalTrainer((new TechnicalTrainerRepo($object["TechnicalTrainer_id"]))->getTechnicalTrainer());
            $this->vsla->setFinancialInstitution((new FinancialInstitutionRepo($object["FinancialInstitution_id"]))->getFinancialInstitution());
            $this->vsla->setVslaRegion((new VslaRegionRepo($object["Region_id"]))->getVslaRegion());   
        }
    }
    
    protected function __getVslaIdByVslaCode($vslaCode){
        $statement = $this->db->prepare("select id from vsla where VslaCode = :VslaCode");
        $statement->bindValue(":VslaCode", $vslaCode, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return count($object) == 1 ? $object["id"] : null;
    }
    
    public static function getVslaIdByVslaCode($vslaCode){
        return (new VslaRepo())->__getVslaIdByVslaCode($vslaCode);
    }
    
    public function getVsla(){
        return $this->vsla;
    }
}
