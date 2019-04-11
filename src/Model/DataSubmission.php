<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of DataSubmission
 *
 * @author JCapito
 */
class DataSubmission {
    //put your code here
    protected $ID;
    protected $sourceVslaCode;
    protected $sourcePhoneImei;
    protected $sourceNetworkOperator;
    protected $sourceNetworkType;
    protected $submissionTimestamp;
    protected $data;
    protected $processedFlag;
    
    public function setProcessedFlag($processFlag){
        $this->processedFlag = $processFlag;
    }
    
    public function getProcessedFlag(){
        return $this->processedFlag;
    }
    
    public function setData($data){
        $this->data = $data;
    }
    
    public function getData(){
        return $this->data;
    }
    
    public function setSubmissionTimestamp($submissionTimestamp){
        $this->submissionTimestamp = $submissionTimestamp;
    }
    
    public function getSubmissionTimestamp(){
        return $this->submissionTimestamp;
    }
    
    public function setSourceNetworkType($sourceNetworkType){
        $this->sourceNetworkType = $sourceNetworkType;
    }
    
    public function getSourceNetworkType(){
        return $this->sourceNetworkType;
    }
    
    public function setSourceNetworkOperator($sourceNetworkOperator){
        $this->sourceNetworkOperator = $sourceNetworkOperator;
    }
    
    public function getSourceNetworkOperator(){
        return $this->sourceNetworkOperator;
    }
    
    public function setSourcePhoneImei($sourcePhoneImei){
        $this->sourcePhoneImei = $sourcePhoneImei;
    }
    
    public function getSourcePhoneImei(){
        return $this->sourcePhoneImei;
    }
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setSourceVslaCode($sourceVslaCode){
        $this->sourceVslaCode = $sourceVslaCode;
    }
    
    public function getSourceVslaCode(){
        return $this->sourceVslaCode;
    }
}
