<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of Vsla
 *
 * @author JCapito
 */
class Vsla {
    //put your code here
    protected $ID;
    protected $vslaCode;
    protected $vslaName;
    protected $vslaPhoneMsisdn;
    protected $physicalLocation;
    protected $gpsLocation;
    protected $dateRegistered;
    protected $dateLinked;
    protected $contactPerson;
    protected $positionInVsla;
    protected $phoneNumber;
    protected $status;
    protected $groupAccountNumber;
    protected $numberOfCycles;
    protected $implementer;
    protected $technicalTrainer;
    protected $financialInstitution;
    protected $vslaRegion;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setVslaCode($vslaCode){
        $this->vslaCode = $vslaCode;
    }
    
    public function getVslaCode(){
        return $this->vslaCode;
    }
    
    public function setVslaName($vslaName){
        $this->vslaName = $vslaName;
    }
    
    public function getVslaName(){
        return $this->vslaName;
    }
    
    public function setVslaPhoneMsisdn($vslaPhoneMsisdn){
        $this->vslaPhoneMsisdn = $vslaPhoneMsisdn;
    }
    
    public function getVslaPhoneMsisdn(){
       return $this->vslaPhoneMsisdn; 
    }
    
    public function setPhysicalLocation($physicalLocation){
        $this->physicalLocation = $physicalLocation;
    }
    
    public function getPhysicalLocation(){
        return $this->physicalLocation;
    }
    
    public function setGpsLocation($gpsLocation){
        $this->gpsLocation = $gpsLocation;
    }
    
    public function getGpsLocation(){
        return $this->gpsLocation;
    }
    
    public function setDateRegistered($dateRegistered){
        $this->dateRegistered = $dateRegistered;
    }
    
    public function getDateRegistered(){
        return $this->dateRegistered;
    }
    
    public function setDateLinked($dateLinked){
        $this->dateLinked = $dateLinked;
    }
    
    public function getDateLinked(){
        return $this->dateLinked;
    }
    
    public function setContactPerson($contactPerson){
        $this->contactPerson = $contactPerson;
    }
    
    public function getContactPerson(){
        return $this->contactPerson;
    }
    
    public function setPositionInVsla($positionInVsla){
        $this->positionInVsla = $positionInVsla;
    }
    
    public function getPositionInVsla(){
        return $this->positionInVsla;
    }
    
    public function setPhoneNumber($phoneNumber){
        $this->phoneNumber = $phoneNumber;
    }
    
    public function getPhoneNumber(){
        return $this->phoneNumber;
    }
    
    public function setStatus($status){
        $this->status = $status;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function setGroupAccountNumber($groupAccountNumber){
        $this->groupAccountNumber = $groupAccountNumber;
    }
    
    public function getGroupAccountNumber(){
        return $this->groupAccountNumber;
    }
    
    public function setNumberOfCycles($numberOfCycles){
        $this->numberOfCycles = $numberOfCycles;
    }
    
    public function getNumberOfCycles(){
        return $this->numberOfCycles;
    }
    
    public function setImplementer($implementer){
        $this->implementer;
    }
    
    public function getImplementer(){
        return $this->implementer;
    }
    
    public function setTechnicalTrainer($technicalTrainer){
        $this->technicalTrainer = $technicalTrainer;
    }
    
    public function getTechnicalTrainer(){
        return $this->technicalTrainer;
    }
    
    public function getFinancialInsitution(){
        return $this->financialInstitution;
    }
    
    public function setFinancialInstitution($financialInstitution){
        $this->financialInstitution = $financialInstitution;
    }
    
    public function setVslaRegion($vslaRegion){
        $this->vslaRegion = $vslaRegion;
    }
    
    public function getVslaRegion(){
        return $this->VslaRegion;
    }
}
