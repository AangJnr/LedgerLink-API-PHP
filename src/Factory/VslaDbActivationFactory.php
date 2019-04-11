<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of VslaDbActivation
 *
 * @author JCapito
 */
use App\Repository\VslaDbActivationRepo;
use App\Repository\VslaRepo;
use App\Model\VslaDbActivation;

class VslaDbActivationFactory {
    //put your code here
    
    protected $submittedData;
    
    protected function __construct($submittedData){
        $this->submittedData = $submittedData;
    }
    
    protected function __process(){
        $isProcessed = false;
        if(is_array($this->submittedData)){
            $vslaDbActivation = new VslaDbActivation();
            if(array_key_exists("VslaCode", $this->submittedData)){
                $vslaId = VslaRepo::getVslaIdByVslaCode($this->submittedData["VslaCode"]);
                $vsla = (new VslaRepo($vslaId))->getVsla();
                $vslaDbActivation->setVsla($vsla);
            }
            if(array_key_exists("PassKey", $this->submittedData)){
                $vslaDbActivation->setPassKey($this->submittedData["PassKey"]);
            }
            if(array_key_exists("PhoneImei", $this->submittedData)){
                $vslaDbActivation->setPhoneImei1($this->submittedData["PhoneImei"]);
            }
            if(array_key_exists("SimImsi", $this->submittedData)){
                $vslaDbActivation->setSimImsiNo01($this->submittedData["SimImsi"]);
            }
            if(array_key_exists("SimSerialNo", $this->submittedData)){
                $vslaDbActivation->setSimSerialNo1($this->submittedData["SimSerialNo"]);
            }
            if(array_key_exists("NetworkOperatorName", $this->submittedData)){
                $vslaDbActivation->setSimNetworkOperator01($this->submittedData["NetworkOperatorName"]);
            }
            $vslaDbActivation->setActivationDate(date("Y-m-d H:i:s"));
            $vslaDbActivation->setIsActive(1);
            $isProcessed = VslaDbActivationRepo::save($vslaDbActivation);
        }
        return $isProcessed;
    }
    
    public static function authenticate($vslaCode, $passKey){
        return VslaDbActivationRepo::authenticate($vslaCode, $passKey);
    }
    
    public static function process($submittedData){
        return (new VslaDbActivationFactory($submittedData))->__process();
    }
}
