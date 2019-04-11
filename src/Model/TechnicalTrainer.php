<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of TechnicalTrainer
 *
 * @author JCapito
 */
class TechnicalTrainer {
    //put your code here
    protected $ID;
    protected $phoneNumber;
    protected $email;
    protected $status;
    protected $firstName;
    protected $lastName;
    protected $username;
    protected $passKey;
    protected $vslaRegion;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID($ID){
        return $this->ID;
    }
    
    public function setPhoneNumber($phoneNumber){
        $this->phoneNumber = $phoneNumber;
    }
    
    public function getPhoneNumber(){
        return $this->phoneNumber;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function setStatus($status){
        $this->status = $status;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }
    
    public function getFirstName(){
        return $this->firstName;
    }
    
    public function setLastName($lastName){
        $this->lastName = $lastName;
    }
    
    public function getLastName(){
        return $this->lastName;
    }
    
    public function setUsername($username){
        $this->username = $username;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function setPassKey($passKey){
        $this->passKey = $passKey;
    }
    
    public function getPassKey(){
        return $this->passKey;
    }
    
    public function setVslaRegion($vslaRegion){
        $this->vslaRegion = $vslaRegion;
    }
    
    public function getVslaRegion(){
        return $this->vslaRegion;
    }
}
