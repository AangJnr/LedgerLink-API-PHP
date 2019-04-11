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
    var $db;
    
    public function __construct($ID = null){
        $this->ID;
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
    
    public static function save($dataSubmission){
        return (new DataSubmissionRepo())->__save($dataSubmission);
    }
}
