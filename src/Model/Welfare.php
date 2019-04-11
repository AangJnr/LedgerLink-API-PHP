<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of Welfare
 *
 * @author JCapito
 */
class Welfare {
    //put your code here
    
    protected $ID;
    protected $welfareIdEx;
    protected $amount;
    protected $comment;
    protected $meeting;
    protected $member;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setWelfareIdEx($welfareIdEx){
        $this->welfareIdEx = $welfareIdEx;
    }
    
    public function getWelfareIdEx(){
        return $this->welfareIdEx;
    }
    
    public function setAmount($amount){
        $this->amount = $amount;
    }
    
    public function getAmount(){
        return $this->amount;
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
}
