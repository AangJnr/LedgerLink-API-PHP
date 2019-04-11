<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of Saving
 *
 * @author JCapito
 */
class Saving {
    //put your code here
    protected $ID;
    protected $savingIdEx;
    protected $amount;
    protected $meeting;
    protected $member;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setSavingIdEx($savingIdEx){
        $this->savingIdEx = $savingIdEx;
    }
    
    public function getSavingIdEx(){
        return $this->savingIdEx;
    }
    
    public function setAmount($amount){
        $this->amount = $amount;
    }
    
    public function getAmount(){
        return $this->amount;
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
}
