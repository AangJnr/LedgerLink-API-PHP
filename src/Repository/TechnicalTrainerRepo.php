<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of TechnicalTrainerRepo
 *
 * @author JCapito
 */
use App\Model\TechnicalTrainer;
use App\Helpers\DatabaseHandler;
use App\Repository\VslaRegionRepo;
use PDO;

class TechnicalTrainerRepo {
    //put your code here
    protected $ID;
    protected $technicalTrainer;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->technicalTrainer = new TechnicalTrainer();
        $this->db = DatabaseHandler::getInstance();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from technicaltrainer where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            if($object != false){
                $this->technicalTrainer->setID($object["id"]);
                $this->technicalTrainer->setPhoneNumber($object["PhoneNumber"]);
                $this->technicalTrainer->setEmail($object["Email"]);
                $this->technicalTrainer->setStatus($object["Status"]);
                $this->technicalTrainer->setFirstName($object["FirstName"]);
                $this->technicalTrainer->setLastName($object["LastName"]);
                $this->technicalTrainer->setUsername($object["Username"]);
                $this->technicalTrainer->setPassKey($object["PassKey"]);

                $vslaRegion = (new VslaRegionRepo($object["Region_id"]))->getVslaRegion();
                $this->technicalTrainer->setVslaRegion($vslaRegion);
            }
        }
    }
    
    public function getTechnicalTrainer(){
        return $this->technicalTrainer;
    }
}
