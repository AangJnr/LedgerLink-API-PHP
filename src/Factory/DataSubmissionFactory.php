<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of DataSubmissionFactory
 *
 * @author JCapito
 */
use App\Model\DataSubmission;
use App\Repository\DataSubmissionRepo;

class DataSubmissionFactory {
    //put your code here
    protected $headerInfo;
    protected $dataSubmission;
    protected $db;
    
    protected function __construct($db, $headerInfo){
        $this->db = $db;
        $this->headerInfo = $headerInfo;
        $this->dataSubmission = new DataSubmission();
    }
    
    protected function __process($jsonString){
        $result = 0;
        if(is_array($this->headerInfo)){
            if(array_key_exists("VslaCode", $this->headerInfo)){
                $this->dataSubmission->setSourceVslaCode($this->headerInfo["VslaCode"]);
            }
            if(array_key_exists(("PhoneImei"), $this->headerInfo)){
                $this->dataSubmission->setSourcePhoneImei($this->headerInfo["PhoneImei"]);
            }
            if(array_key_exists("NetworkOperator", $this->headerInfo)){
                $this->dataSubmission->setSourceNetworkOperator($this->headerInfo["NetworkOperator"]);
            }
            if(array_key_exists("NetworkType", $this->headerInfo)){
                $this->dataSubmission->setSourceNetworkType($this->headerInfo["NetworkType"]);
            }
            $this->dataSubmission->setData($jsonString);
            $this->dataSubmission->setSubmissionTimestamp(date("Y-m-d H:i:s"));
            $this->dataSubmission->setProcessedFlag(0);
            $result = DataSubmissionRepo::save($this->db, $this->dataSubmission);
        }
        return $result;
    }
    
    public static function process($db, $headerInfo, $jsonString){
        return (new DataSubmissionFactory($db, $headerInfo))->__process($jsonString);
    }
}
