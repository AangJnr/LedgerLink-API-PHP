<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of OutstandingWelfare
 *
 * @author JCapito
 */
class OutstandingWelfare {
    //put your code here
    protected $ID;
    protected $outstandingWelfareIdEx;
    protected $amount;
    protected $expectedDate;
    protected $isCleared;
    protected $dateCleared;
    protected $comment;
    protected $meeting;
    protected $member;
    protected $paidInMeeting;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setOutstandingWelfareIdEx($outstandingWelfareIdEx){
        $this->outstandingWelfareIdEx = $outstandingWelfareIdEx;
    }
    
    public function getOutstandingWelfareIdEx(){
        return $this->outstandingWelfareIdEx;
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
    
    public function setComment($comment){
        $this->comment = $comment;
    }
    
    public function getComment(){
        return $this->comment;
    }
    
    public function setMeeting($meeting){
        $this->meeting = $meeting;
    }
    
    public function getMeeting(){
        return $this->meeting;
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
