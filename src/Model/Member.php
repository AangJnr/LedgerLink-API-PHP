<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of Member
 *
 * @author JCapito
 */
class Member {
    //put your code here
    protected $ID;
    protected $memberIdEx;
    protected $memberNumber;
    protected $cyclesCompleted;
    protected $surname;
    protected $otherNames;
    protected $gender;
    protected $occupation;
    protected $dateArchived;
    protected $dateOfBirth;
    protected $isActive;
    protected $isArchived;
    protected $phoneNumber;
    protected $vsla;
    
    public function setIsArchived($isArchived){
        $this->isArchived = $isArchived;
    }
    
    public function getIsArchived(){
        return $this->isArchived;
    }
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setMemberIdEx($memberIdEx){
        $this->memberIdEx = $memberIdEx;
    }
    
    public function getMemberIdEx(){
        return $this->memberIdEx;
    }
    
    public function setMemberNumber($memberNumber){
        $this->memberNumber = $memberNumber;
    }
    
    public function getMemberNumber(){
        return $this->memberNumber;
    }
    
    public function setCyclesCompleted($cyclesCompleted){
        $this->cyclesCompleted = $cyclesCompleted;
    }
    
    public function getCyclesCompleted(){
        return $this->cyclesCompleted;
    }
    
    public function setSurname($surname){
        $this->surname = $surname;
    }
    
    public function getSurname(){
        return $this->surname;
    }
    
    public function setOtherNames($otherNames){
        $this->otherNames = $otherNames;
    }
    
    public function getOtherNames(){
        return $this->otherNames;
    }
    
    public function setGender($gender){
        $this->gender = $gender;
    }
    
    public function getGender(){
        return $this->gender;
    }
    
    public function setOccupation($occupation){
        $this->occupation = $occupation;
    }
    
    public function getOccupation(){
        return $this->occupation;
    }
    
    public function setDateArchived($dateArchived){
        $this->dateArchived = $dateArchived;
    }
    
    public function getDateArchived(){
        return $this->dateArchived;
    }
    
    public function setDateOfBirth($dateOfBirth){
        $this->dateOfBirth = $dateOfBirth;
    }
    
    public function getDateOfBirth(){
        return $this->dateOfBirth;
    }
    
    public function setIsActive($isActive){
        $this->isActive = $isActive;
    }
    
    public function getIsActive(){
        return $this->isActive;
    }
    
    public function setPhoneNumber($phoneNumber){
        $this->phoneNumber = $phoneNumber;
    }
    
    public function getPhoneNumber(){
        return $this->phoneNumber;
    }
    
    public function setVsla($vsla){
        $this->vsla = $vsla;
    }
    
    public function getVsla(){
        return $this->vsla;
    }
}
