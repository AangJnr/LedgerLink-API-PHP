<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of VslaDbActivation
 *
 * @author JCapito
 */
class VslaDbActivation {
    //put your code here
    protected $ID;
    protected $activationDate;
    protected $isActive;
    protected $passKey;
    protected $phoneImei1;
    protected $phoneImei2;
    protected $simImsiNo01;
    protected $simImsiNo02;
    protected $simNetworkOperator01;
    protected $simNetworkOperator02;
    protected $simSerialNo01;
    protected $simSerialNo02;
    protected $vsla;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setActivationDate($activationDate){
        $this->activationDate = $activationDate;
    }
    
    public function getActivationDate(){
        return $this->activationDate;
    }
    
    public function setIsActive($isActive){
        $this->isActive = $isActive;
    }
    
    public function getIsActive(){
        return $this->isActive;
    }
    
    public function setPassKey($passKey){
        $this->passKey = $passKey;
    }
    
    public function getPassKey(){
        return $this->passKey;
    }
    
    public function setPhoneImei1($phoneImei1){
        $this->phoneImei1 = $phoneImei1;
    }
    
    public function getPhoneImei1(){
        return $this->phoneImei1;
    }
    
    public function setPhoneImei2($phoneImei2){
        $this->phoneImei2 = $phoneImei2;
    }
    
    public function getPhoneImei2(){
        return $this->phoneImei2;
    }
    
    public function setSimImsiNo01($simImsiNo01){
        $this->simImsiNo01 = $simImsiNo01;
    }
    
    public function getSimImsiNo01(){
        return $this->simImsiNo01;
    }
    
    public function setSimImsiNo02($simImsiNo02){
        $this->simImsiNo02 = $simImsiNo02;
    }
    
    public function getSimImsiNo02(){
        return $this->simImsiNo02;
    }
    
    public function setSimNetworkOperator01($simNetworkOperator01){
        $this->simNetworkOperator01 = $simNetworkOperator01;
    }
    
    public function getSimNetworkOperator01(){
        return $this->simNetworkOperator01;
    }
    
    public function setSimNetworkOperator02($simNetworkOperator02){
        $this->simNetworkOperator02 = $simNetworkOperator02;
    }
    
    public function getSimNetworkOperator02(){
        return $this->simNetworkOperator02;
    }
    
    public function setSimSerialNo1($simSerialNo01){
        $this->simSerialNo01 = $simSerialNo01;
    }
    
    public function getSimSerialNo1(){
        return $this->simImsiNo01;
    }
    
    public function setSimSerialNo2($simSerialNo02){
        $this->simSerialNo02 = $simSerialNo02;
    }
    
    public function getSimSerialNo2(){
        return $this->simSerialNo02;
    }
    
    public function setVsla($vsla){
        $this->vsla = $vsla;
    }
    
    public function getVsla(){
        return $this->vsla;
    }
}
