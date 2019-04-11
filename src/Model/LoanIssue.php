<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of LoanIssue
 *
 * @author JCapito
 */
class LoanIssue {
    //put your code here
    protected $ID;
    protected $loanIdEx;
    protected $loanNumber;
    protected $principalAmount;
    protected $interestAmount;
    protected $balance;
    protected $comments;
    protected $dateCleared;
    protected $dueDate;
    protected $isCleared;
    protected $isDefaulted;
    protected $totalRepaid;
    protected $isWrittenOff;
    protected $meeting;
    protected $member;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setLoanIdEx($loanIdEx){
        $this->loanIdEx = $loanIdEx;
    }
    
    public function getLoanIdEx(){
        return $this->loanIdEx;
    }
    
    public function setLoanNumber($loanNumber){
        $this->loanNumber = $loanNumber;
    }
    
    public function getLoanNumber(){
        return $this->loanNumber;
    }
    
    public function setPrincipalAmount($principalAmount){
        $this->principalAmount = $principalAmount;
    }
    
    public function getPrincipalAmount(){
        return $this->principalAmount;
    }
    
    public function setInterestAmount($interestAmount){
        $this->interestAmount = $interestAmount;
    }
    
    public function getInterestAmount(){
        return $this->interestAmount;
    }
    
    public function setBalance($balance){
        $this->balance = $balance;
    }
    
    public function getBalance(){
        return $this->balance;
    }
    
    public function setComments($comments){
        $this->comments = $comments;
    }
    
    public function getComments(){
        return $this->comments;
    }
    
    public function setDateCleared($dateCleared){
        $this->dateCleared = $dateCleared;
    }
    
    public function getDateCleared(){
        return $this->dateCleared;
    }
    
    public function setDueDate($dueDate){
        $this->dueDate = $dueDate;
    }
    
    public function getDueDate(){
        return $this->dueDate;
    }
    
    public function setIsCleared($isCleared){
        $this->isCleared = $isCleared;
    }
    
    public function getIsCleared(){
        return $this->isCleared;
    }
    
    public function setIsDefaulted($isDefaulted){
        $this->isDefaulted = $isDefaulted;
    }
    
    public function getIsDefaulted(){
        return $this->isDefaulted;
    }
    
    public function setTotalRepaid($totalRepaid){
        $this->totalRepaid = $totalRepaid;
    }
    
    public function getTotalRepaid(){
        return $this->totalRepaid;
    }
    
    public function setIsWrittenOff($isWrittenOff){
        $this->isWrittenOff = $isWrittenOff;
    }
    
    public function getIsWrittenOff(){
        return $this->isWrittenOff;
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
