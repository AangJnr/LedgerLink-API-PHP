<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of Fine
 *
 * @author JCapito
 */
class Fine {
    //put your code here
    protected $ID;
    protected $fineIdEx;
    protected $amount;
    protected $expectedDate;
    protected $isCleared;
    protected $dateCleared;
    protected $issuedInMeetingIdEx;
    protected $paidInMeetingIdEx;
    protected $fineTypeId;
    protected $issuedInMeeting;
    protected $member;
    protected $paidInMeeting;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setFineIdEx($fineIdEx){
        $this->fineIdEx = $fineIdEx;
    }
    
    public function getFineIdEx(){
        return $this->fineIdEx;
    }
    
    public function setAmount($amount){
        $this->amount = $amount;
    }
    
    public function getAmount(){
        return $this->amount;
    }
    
    public function setExpectedDate($expectedDate){
        $this->expectedDate = $expectedDate;
    }
    
    public function getExpectedDate(){
        return $this->expectedDate;
    }
    
    public function setIsCleared($isCleared){
        $this->isCleared = $isCleared;
    }
    
    public function getIsCleared(){
        return $this->isCleared;
    }
    
    public function setDateCleared($dateCleared){
        $this->dateCleared = $dateCleared;
    }
    
    public function getDateCleared(){
        return $this->dateCleared;
    }
    
    public function setIssuedInMeetingIdEx($issuedInMeetingIdEx){
        $this->issuedInMeetingIdEx = $issuedInMeetingIdEx;
    }
    
    public function getIssuedInMeetingIdEx(){
        return $this->issuedInMeetingIdEx;
    }
    
    public function setPaidInMeetingIdEx($paidInMeetingIdEx){
        $this->paidInMeetingIdEx = $paidInMeetingIdEx;
    }

    public function getPaidInMeetingIdEx(){
        return $this->paidInMeetingIdEx;
    }
    
    public function setFineTypeId($fineTypeId){
        $this->fineTypeId = $fineTypeId;
    }
    
    public function getFineTypeId(){
        return $this->fineTypeId;
    }
    
    public function setIssuedInMeeting($issuedInMeeting){
        $this->issuedInMeeting = $issuedInMeeting;
    }
    
    public function getIssuedInMeeting(){
        return $this->issuedInMeeting;
    }
    
    public function setMember($member){
        $this->member = $member;
    }
    
    public function getMember(){
        return $this->member;
    }
    
    public function setPaidInMeeting($paidInMeeting){
        $this->paidInMeeting = $paidInMeeting;
    }
    
    public function getPaidInMeeting(){
        return $this->paidInMeeting;
    }
}
