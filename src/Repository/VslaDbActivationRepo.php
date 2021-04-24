<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of VslaDbActivation
 *
 * @author JCapito
 */
use App\Model\VslaDbActivation;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\VslaRepo;

class VslaDbActivationRepo {
    //put your code here
    protected $ID;
    protected $vslaDbActivation;
    var $db;
    
    public function __construct($db, $ID = null){
        $this->ID = $ID;
        $this->vslaDbActivation = new VslaDbActivation();
        $this->db = $db;
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from vsladbactivation where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->vslaDbActivation->setID($object["id"]);
            $this->vslaDbActivation->setActivationDate($object["ActivationDate"]);
            $this->vslaDbActivation->setIsActive($object["IsActive"]);
            $this->vslaDbActivation->setPassKey($object["PassKey"]);
            $this->vslaDbActivation->setPhoneImei1($object["PhoneImei1"]);
            $this->vslaDbActivation->setPhoneImei2($object["PhoneImei2"]);
            $this->vslaDbActivation->setSimImsiNo01($object["SimImsiNo01"]);
            $this->vslaDbActivation->setSimImsiNo02($object["SimImsiNo02"]);
            $this->vslaDbActivation->setSimNetworkOperator01($object["SimNetworkOperator01"]);
            $this->vslaDbActivation->setSimNetworkOperator02($object["SimNetworkOperator02"]);
            $this->vslaDbActivation->setSimSerialNo1($object["SimSerialNo01"]);
            $this->vslaDbActivation->setSimSerialNo2($object["SimSerialNo02"]);
            $this->vslaDbActivation->setVsla((new VslaRepo($object["Vsla_id"]))->getVsla());
        }
    }
    
    public function getVslaDbActivation(){
        return $this->vslaDbActivation;
    }
    
    protected function __authenticate($vslaCode, $passKey){
        $statement = $this->db->prepare("select a.*, b.VslaCode from vsladbactivation a inner join vsla b on a.Vsla_id=b.id where a.PassKey = :PassKey and b.VslaCode = :VslaCode");
        $statement->bindValue(":PassKey", $passKey, PDO::PARAM_STR);
        $statement->bindValue(":VslaCode", $vslaCode, PDO::PARAM_STR);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $vslaCode == $object["VslaCode"] && $passKey == $object["PassKey"] ? $object["id"] : null;
    }
    
    public static function authenticate($db, $vslaCode, $passKey){
        return (new VslaDbActivationRepo($db))->__authenticate($vslaCode, $passKey);
    }
    
    public static function save($db, $vslaDbActivation){
        return (new VslaDbActivationRepo($db))->__save($vslaDbActivation);
    }
    
    protected function __save($vslaDbActivation){
        $resultStatus = false;
        $vslaDbActivationId = $this->__authenticate($vslaDbActivation->getVsla()->getVslaCode(), $vslaDbActivation->getPassKey());
        if($vslaDbActivationId != null){
            $vslaDbActivation->setID($vslaDbActivationId);
            $resultStatus = $this->update($vslaDbActivation) > -1 ? true : false;
        }else{
            $resultStatus = $this->__add($vslaDbActivation) > 0 ? true : false;
        }
        return $resultStatus;
    }
    
    protected function __add($vslaDbActivation){
        $statement = $this->db->prepare("insert into vsladbactivation values (0, "
                . ":ActivationDate, "
                . ":IsActive,"
                . ":PassKey,"
                . ":PhoneImei1,"
                . ":PhoneImei2,"
                . ":SimImsiNo01,"
                . ":SimImsiNo02,"
                . ":SimNetworkOperator01,"
                . ":SimNetworkOperator02,"
                . ":SimSerialNo01,"
                . ":SimSerialNo02,"
                . ":VslaId)");
        $statement->bindValue(":ActivationDate", $vslaDbActivation->getActivationDate(), PDO::PARAM_INT);
        $statement->bindValue(":IsActive", $vslaDbActivation->getIsActive(), PDO::PARAM_INT);
        $statement->bindValue(":PassKey", $vslaDbActivation->getPassKey(), PDO::PARAM_INT);
        $statement->bindValue(":PhoneImei1", $vslaDbActivation->getPhoneImei1(), PDO::PARAM_INT);
        $statement->bindValue(":PhoneImei2", $vslaDbActivation->getPhoneImei2(), PDO::PARAM_INT);
        $statement->bindValue(":SimImsiNo01", $vslaDbActivation->getSimImsiNo01(), PDO::PARAM_INT);
        $statement->bindValue(":SimImsiNo02", $vslaDbActivation->getSimImsiNo02(), PDO::PARAM_INT);
        $statement->bindValue(":SimNetworkOperator01", $vslaDbActivation->getSimNetworkOperator01(), PDO::PARAM_INT);
        $statement->bindValue(":SimNetworkOperator02", $vslaDbActivation->getSimNetworkOperator02(), PDO::PARAM_INT);
        $statement->bindValue(":SimSerialNo01", $vslaDbActivation->getSimSerialNo1(), PDO::PARAM_INT);
        $statement->bindValue(":SimSerialNo02", $vslaDbActivation->getSimSerialNo2(), PDO::PARAM_INT);
        $statement->bindValue(":VslaId", $vslaDbActivation->getVsla()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($vslaDbActivation){
        $statement = $this->db->prepare("update vsladbactivation set "
                . "ActivationDate = :ActivationDate, "
                . "IsActive = :IsActive,"
                . "PassKey = :PassKey,"
                . "PhoneImei1 = :PhoneImei1,"
                . "PhoneImei2 = :PhoneImei2,"
                . "SimImsiNo01 = :SimImsiNo01,"
                . "SimImsiNo02 = :SimImsiNo02,"
                . "SimNetworkOperator01 = :SimNetworkOperator01,"
                . "SimNetworkOperator02 = :SimNetworkOperator02,"
                . "SimSerialNo01 = :SimSerialNo01,"
                . "SimSerialNo02 = :SimSerialNo02,"
                . "Vsla_id = :VslaId where id = :id");
        $statement->bindValue(":ActivationDate", $vslaDbActivation->getActivationDate(), PDO::PARAM_INT);
        $statement->bindValue(":IsActive", $vslaDbActivation->getIsActive(), PDO::PARAM_INT);
        $statement->bindValue(":PassKey", $vslaDbActivation->getPassKey(), PDO::PARAM_INT);
        $statement->bindValue(":PhoneImei1", $vslaDbActivation->getPhoneImei1(), PDO::PARAM_INT);
        $statement->bindValue(":PhoneImei2", $vslaDbActivation->getPhoneImei2(), PDO::PARAM_INT);
        $statement->bindValue(":SimImsiNo01", $vslaDbActivation->getSimImsiNo01(), PDO::PARAM_INT);
        $statement->bindValue(":SimImsiNo02", $vslaDbActivation->getSimImsiNo02(), PDO::PARAM_INT);
        $statement->bindValue(":SimNetworkOperator01", $vslaDbActivation->getSimNetworkOperator01(), PDO::PARAM_INT);
        $statement->bindValue(":SimNetworkOperator02", $vslaDbActivation->getSimNetworkOperator02(), PDO::PARAM_INT);
        $statement->bindValue(":SimSerialNo01", $vslaDbActivation->getSimSerialNo1(), PDO::PARAM_INT);
        $statement->bindValue(":SimSerialNo02", $vslaDbActivation->getSimSerialNo2(), PDO::PARAM_INT);
        $statement->bindValue(":VslaId", $vslaDbActivation->getVsla()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $vslaDbActivation->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }
}
