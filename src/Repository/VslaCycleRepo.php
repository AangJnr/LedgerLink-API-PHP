<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of VslaCycleRepo
 *
 * @author JCapito
 */
use App\Model\VslaCycle;
use App\Repository\VslaRepo;
use PDO;
use App\Helpers\DatabaseHandler;


class VslaCycleRepo {
    //put your code here
    
    protected $ID;
    protected $vslaCycle;
    var $db;
    
    public function __construct($db, $ID = null){
        $this->ID = $ID;
        $this->db = $db;
        $this->vslaCycle = new VslaCycle();
        $this->__load();
    }
    
    public function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from vslacycle where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            if($object != false){
                $this->vslaCycle->setID($object["id"]);
                $this->vslaCycle->setCycleIdEx($object["CycleIdEx"]);
                $this->vslaCycle->setDateEnded($object["DateEnded"]);
                $this->vslaCycle->setEndDate($object["EndDate"]);
                $this->vslaCycle->setCycleCode($object["CycleCode"]);
                $this->vslaCycle->setInterestRate($object["InterestRate"]);
                $this->vslaCycle->setIsEnded($object["IsEnded"]);
                $this->vslaCycle->setMaxShareQuantity($object["MaxShareQuantity"]);
                $this->vslaCycle->setMaxStartShare($object["MaxStartShare"]);
                $this->vslaCycle->setSharedAmount($object["SharedAmount"]);
                $this->vslaCycle->setSharePrice($object["SharePrice"]);
                $this->vslaCycle->setShareDate($object["ShareDate"]);
                $this->vslaCycle->setMigratedInterest($object["MigratedInterest"]);
                $this->vslaCycle->setMigratedFines($object["MigratedFines"]);
                $this->vslaCycle->setStartDate($object["StartDate"]);
                $this->vslaCycle->setVsla((new VslaRepo($object["Vsla_id"]))->getVsla());
            }
        }
    }
    
    public function getVslaCycle(){
        return $this->vslaCycle;
    }
    
    public static function getIDByCycleIdEx($db, $vslaId, $cycleIdEx){
        return (new VslaCycleRepo($db))->__getIDByCycleIdEx($vslaId, $cycleIdEx);
    }
    
    protected function __getIDByCycleIdEx($vslaId, $cycleIdEx){
        $statement = $this->db->prepare("select id from vslacycle where CycleIdEx = :CycleIdEx and Vsla_id = :VslaId");
        $statement->bindValue(":CycleIdEx", $cycleIdEx, PDO::PARAM_INT);
        $statement->bindValue(":VslaId", $vslaId, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    protected function __add($vslaCycle){
        
        $statement = $this->db->prepare("insert into vslacycle (CycleIdEx, DateEnded, EndDate, CycleCode, InterestRate, IsEnded, MaxShareQuantity, MaxStartShare, SharedAmount, SharePrice, ShareDate, MigratedInterest, MigratedFines, Vsla_id, StartDate) values (:CycleIdEx, :DateEnded, :EndDate, :CycleCode, :InterestRate, :IsEnded, :MaxShareQuantity, :MaxStartShare, :SharedAmount, :SharePrice, :ShareDate, :MigratedInterest, :MigratedFines, :Vsla_id, :StartDate)");
        $statement->bindValue(":CycleIdEx", $vslaCycle->getCycleIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":DateEnded", $vslaCycle->getDateEnded() == null ? NULL : $vslaCycle->getDateEnded(), PDO::PARAM_STR);
        $statement->bindValue(":EndDate", $vslaCycle->getEndDate() == null ? NULL : $vslaCycle->getEndDate(), PDO::PARAM_STR);
        $statement->bindValue(":CycleCode", $vslaCycle->getCycleCode() == null ? NULL : $vslaCycle->getCycleCode(), PDO::PARAM_STR);
        $statement->bindValue(":InterestRate", $vslaCycle->getInterestRate() == null ? NULL : $vslaCycle->getInterestRate(), PDO::PARAM_INT);
        $statement->bindValue(":IsEnded", $vslaCycle->getIsEnded() == null ? 0 : $vslaCycle->getIsEnded(), PDO::PARAM_INT);
        $statement->bindValue(":MaxShareQuantity", $vslaCycle->getMaxShareQuantity() == null ? NULL : $vslaCycle->getMaxShareQuantity(), PDO::PARAM_INT);
        $statement->bindValue(":MaxStartShare", $vslaCycle->getMaxStartShare() == null ? NULL : $vslaCycle->getMaxStartShare(), PDO::PARAM_INT);
        $statement->bindValue(":SharedAmount", $vslaCycle->getSharedAmount() == null ? NULL : $vslaCycle->getSharedAmount(), PDO::PARAM_INT);
        $statement->bindValue(":SharePrice", $vslaCycle->getSharePrice() == null ? NULL : $vslaCycle->getSharePrice(), PDO::PARAM_INT);
        $statement->bindValue(":ShareDate", $vslaCycle->getShareDate() == null ? NULL : $vslaCycle->getShareDate(), PDO::PARAM_STR);
        $statement->bindValue(":MigratedInterest", $vslaCycle->getMigratedInterest() == null ? NULL : $vslaCycle->getMigratedInterest(), PDO::PARAM_INT);
        $statement->bindValue(":MigratedFines", $vslaCycle->getMigratedFines() == null ? NULL : $vslaCycle->getMigratedFines(), PDO::PARAM_INT);
        $statement->bindValue(":Vsla_id", $vslaCycle->getVsla()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":StartDate", $vslaCycle->getStartDate() == null ? NULL : $vslaCycle->getStartDate(), PDO::PARAM_STR);        
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    protected function __getCurrentCycleID($vslaID){
        $statement = $this->db->prepare("select id from vslacycle where Vsla_id = :Vsla_id order by CycleIdEx, id desc limit 0, 1");
        $statement->bindValue(":Vsla_id", $vslaID, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    protected function __getPreviousCycleID($vslaID){
        $numberOfCycles = $this->getNumberOfCycles($vslaID);
        if($numberOfCycles == false || $numberOfCycles == 1){
            return 0;
        }
        $statement = $this->db->prepare("select id from vslacycle where Vsla_id = :Vsla_id order by CycleIdEx, id desc limit 1, 1");
        $statement->bindValue(":Vsla_id", $vslaID, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    public static function getPreviousCycleID($db, $vslaID){
        return (new VslaCycleRepo($db))->__getPreviousCycleID($vslaID);
    }
    
    public function getNumberOfCycles($vslaID){
        $statement = $this->db->prepare("select count(id) NumberOfCycles from vslacycle where Vsla_id = :Vsla_id");
        $statement->bindValue(":Vsla_id", $vslaID, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["NumberOfCycles"];
    }
    
    public static function getCurrentCycleID($db, $vslaID){
        return (new VslaCycleRepo($db))->__getCurrentCycleID($vslaID);
    }
    
    public function update($vslaCycle){
        $statement = $this->db->prepare("update vslacycle set CycleIdEx = :CycleIdEx, "
                . "DateEnded = :DateEnded, "
                . "EndDate = :EndDate, "
                . "CycleCode = :CycleCode, "
                . "InterestRate = :InterestRate, "
                . "IsEnded = :IsEnded, "
                . "MaxShareQuantity = :MaxShareQuantity, "
                . "MaxStartShare = :MaxStartShare, "
                . "SharedAmount = :SharedAmount, "
                . "SharePrice = :SharePrice, "
                . "ShareDate = :ShareDate, "
                . "MigratedInterest = :MigratedInterest, "
                . "MigratedFines = :MigratedFines, "
                . "Vsla_id = :Vsla_id, "
                . "StartDate = :StartDate where id = :id");
        $statement->bindValue(":CycleIdEx", $vslaCycle->getCycleIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":DateEnded", $vslaCycle->getDateEnded() == null ? NULL : $vslaCycle->getDateEnded(), PDO::PARAM_STR);
        $statement->bindValue(":EndDate", $vslaCycle->getEndDate() == null ? NULL : $vslaCycle->getEndDate(), PDO::PARAM_STR);
        $statement->bindValue(":CycleCode", $vslaCycle->getCycleCode() == null ? NULL : $vslaCycle->getCycleCode(), PDO::PARAM_STR);
        $statement->bindValue(":InterestRate", $vslaCycle->getInterestRate() == null ? NULL : $vslaCycle->getInterestRate(), PDO::PARAM_INT);
        $statement->bindValue(":IsEnded", $vslaCycle->getIsEnded() == null ? 0 : $vslaCycle->getIsEnded(), PDO::PARAM_INT);
        $statement->bindValue(":MaxShareQuantity", $vslaCycle->getMaxShareQuantity() == null ? NULL : $vslaCycle->getMaxShareQuantity(), PDO::PARAM_INT);
        $statement->bindValue(":MaxStartShare", $vslaCycle->getMaxStartShare() == null ? NULL : $vslaCycle->getMaxStartShare(), PDO::PARAM_INT);
        $statement->bindValue(":SharedAmount", $vslaCycle->getSharedAmount() == null ? NULL : $vslaCycle->getSharedAmount(), PDO::PARAM_INT);
        $statement->bindValue(":SharePrice", $vslaCycle->getSharePrice() == null ? NULL : $vslaCycle->getSharePrice(), PDO::PARAM_INT);
        $statement->bindValue(":ShareDate", $vslaCycle->getShareDate() == null ? NULL : $vslaCycle->getShareDate(), PDO::PARAM_STR);
        $statement->bindValue(":MigratedInterest", $vslaCycle->getMigratedInterest() == null ? NULL : $vslaCycle->getMigratedInterest(), PDO::PARAM_INT);
        $statement->bindValue(":MigratedFines", $vslaCycle->getMigratedFines() == null ? NULL : $vslaCycle->getMigratedFines(), PDO::PARAM_INT);
        $statement->bindValue(":Vsla_id", $vslaCycle->getVsla()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":StartDate", $vslaCycle->getStartDate() == null ? NULL : $vslaCycle->getStartDate(), PDO::PARAM_STR);
        $statement->bindValue(":id", $vslaCycle->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }
    
    protected function __save($vslaCycle){
        $cycleID = $this->__getIDByCycleIdEx($vslaCycle->getVsla()->getID(), $vslaCycle->getCycleIdEx());
        if($cycleID != null){
            $vslaCycle->setID($cycleID);
            return $this->update($vslaCycle);
        }else{
            return $this->__add($vslaCycle);
        }   
        return -1;
    }
    
    public static function save($db, $vslaCycle){
        return (new VslaCycleRepo($db))->__save($vslaCycle);
    }
}
