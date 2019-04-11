<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of VslaCycle
 *
 * @author JCapito
 */
class VslaCycle {
    //put your code here
    protected $ID;
    protected $cycleIdEx;
    protected $dateEnded;
    protected $startDate;
    protected $endDate;
    protected $cycleCode;
    protected $interestRate;
    protected $isEnded;
    protected $maxShareQuantity;
    protected $maxStartShare;
    protected $sharedAmount;
    protected $sharePrice;
    protected $shareDate;
    protected $migratedInterest;
    protected $migratedFines;
    protected $vsla;
        
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setCycleIdEx($cycleIdEx){
        $this->cycleIdEx = $cycleIdEx;
    }
    
    public function getCycleIdEx(){
        return $this->cycleIdEx;
    }
    
    public function setStartDate($startDate){
        $this->startDate = $startDate;
    }
    
    public function getStartDate(){
        return $this->startDate;
    }
    
    public function setDateEnded($dateEnded){
        $this->dateEnded = $dateEnded;
    }
    
    public function getDateEnded(){
        return $this->dateEnded;
    }
    
    public function setEndDate($endDate){
        $this->endDate = $endDate;
    }
    
    public function getEndDate(){
        return $this->endDate;
    }
    
    public function setCycleCode($cycleCode){
        $this->cycleCode = $cycleCode;
    }
    
    public function getCycleCode(){
        return $this->cycleCode;
    }
    
    public function setInterestRate($interestRate){
        $this->interestRate = $interestRate;
    }
    
    public function getInterestRate(){
        return $this->interestRate;
    }
    
    public function setIsEnded($isEnded){
        $this->isEnded = $isEnded;
    }
    
    public function getIsEnded(){
        return $this->isEnded;
    }
    
    public function setMaxShareQuantity($maxShareQuantity){
        $this->maxShareQuantity = $maxShareQuantity;
    }
    
    public function getMaxShareQuantity(){
        return $this->maxShareQuantity;
    }
    
    public function setMaxStartShare($maxStartShare){
       $this->maxStartShare = $maxStartShare; 
    }
    
    public function getMaxStartShare(){
        return $this->maxStartShare;
    }
    
    public function setSharedAmount($sharedAmount){
        $this->sharedAmount;
    }
    
    public function getSharedAmount(){
        return $this->sharedAmount;
    }
    
    public function setSharePrice($sharePrice){
        $this->sharePrice = $sharePrice;
    }
    
    public function getSharePrice(){
        return $this->sharePrice;
    }
    
    public function setShareDate($shareDate){
        $this->shareDate = $shareDate;
    }
    
    public function getShareDate(){
        return $this->shareDate;
    }
    
    public function setMigratedInterest($migratedInterest){
        $this->migratedInterest = $migratedInterest;
    }
    
    public function getMigratedInterest(){
        return $this->migratedInterest;
    }
    
    public function setMigratedFines($migratedFines){
        $this->migratedFines = $migratedFines;
    }
    
    public function getMigratedFines(){
        return $this->migratedFines;
    }
    
    public function setVsla($vsla){
        $this->vsla = $vsla;
    }
    
    public function getVsla(){
        return $this->vsla;
    }
}
