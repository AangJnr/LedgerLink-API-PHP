<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of DataSubmissionRepo
 *
 * @author JCapito
 */
use App\Model\DataSubmission;
use App\Helpers\DatabaseHandler;
use PDO;

class DataSubmissionRepo {
    //put your code here
    protected $ID;
    protected $dataSubmission;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->dataSubmission = new DataSubmission();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $this->db = DatabaseHandler::getInstance();
            $statement = $this->db->prepare("select * from datasubmission where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->dataSubmission->setData($object["Data"]);
            $this->dataSubmission->setProcessedFlag($object["ProcessedFlag"]);
            $this->dataSubmission->setSubmissionTimestamp($object["SubmissionTimestamp"]);
            $this->dataSubmission->setSourceNetworkOperator($object["SourceNetworkOperator"]);
            $this->dataSubmission->setSourceNetworkType($object["SourceNetworkType"]);
            $this->dataSubmission->setID($object["id"]);
            $this->dataSubmission->setSourcePhoneImei($object["SourcePhoneImei"]);
            $this->dataSubmission->setSourceVslaCode($object["SourceVslaCode"]);
        }
    }
    
    public function getDataSubmission(){
        return $this->dataSubmission;
    }
    
    protected function __save($dataSubmission){
        $this->db = DatabaseHandler::getInstance();
        $statement = $this->db->prepare("insert into datasubmission values (0, :SourceVslaCode, :SourcePhoneImei, :SourceNetworkOperator, :SourceNetworkType, :SubmissionTimestamp, :Data, :ProcessedFlag)");
        $statement->bindValue(":SourceVslaCode", $dataSubmission->getSourceVslaCode(), PDO::PARAM_STR);
        $statement->bindValue(":SourcePhoneImei", $dataSubmission->getSourcePhoneImei(), PDO::PARAM_STR);
        $statement->bindValue(":SourceNetworkOperator", $dataSubmission->getSourceNetworkOperator(), PDO::PARAM_STR);
        $statement->bindValue(":SourceNetworkType", $dataSubmission->getSourceNetworkType(), PDO::PARAM_STR);
        $statement->bindValue(":SubmissionTimestamp", $dataSubmission->getSubmissionTimestamp(), PDO::PARAM_STR);
        $statement->bindValue(":Data", $dataSubmission->getData(), PDO::PARAM_STR);
        $statement->bindValue(":ProcessedFlag", $dataSubmission->getProcessedFlag(), PDO::PARAM_STR);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function __getIdAtIndex($index){
        $this->db = DatabaseHandler::getInstance();
        $statement = $this->db->prepare("select id from datasubmission order by id limit :index, 1");
        $statement->bindValue(":index", $index, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object["id"];
    }
    
    public function updateProcessedFlag($boolean = false){
        $processedFlag = $boolean == false ? 0 : 1;
        $this->db = DatabaseHandler::getInstance();
        $statement = $this->db->prepare("update datasubmission set ProcessedFlag = :ProcessedFlag where id = :id");
        $statement->bindValue(":ProcessedFlag", $processedFlag, PDO::PARAM_INT);
        $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function getIdAtIndex($index=0){
        return (new DataSubmissionRepo())->__getIdAtIndex($index);
    }
    
    protected function __getCountOfUnproccessedDataSubmissions(){
        $this->db = DatabaseHandler::getInstance();
        $statement = $this->db->prepare("select count(*) as TotalNumber from datasubmission where ProcessedFlag = 0");
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object["TotalNumber"];
    }
    
    public static function getCountOfUnProcessedDataSubmissions(){
        return (new DataSubmissionRepo())->__getCountOfUnproccessedDataSubmissions();
    }
    
    public static function save($dataSubmission){
        return (new DataSubmissionRepo())->__save($dataSubmission);
    }
    
}
