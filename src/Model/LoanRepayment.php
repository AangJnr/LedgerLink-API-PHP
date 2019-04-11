<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of LoanRepayment
 *
 * @author JCapito
 */
class LoanRepayment {
    //put your code here
    protected $ID;
    protected $loanRepaymentIdEx;
    protected $amount;
    protected $balanceAfter;
    protected $balanceBefore;
    protected $comments;
    protected $lastDueDate;
    protected $nextDueDate;
    protected $interestAmount;
    protected $rolloverAmount;
    protected $loanIssue;
    protected $meeting;
    protected $member;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setLoanRepaymentIdEx($loanRepaymentIdEx){
        $this->loanRepaymentIdEx = $loanRepaymentIdEx;
    }
    
    public function getLoanRepaymentIdEx(){
        return $this->loanRepaymentIdEx;
    }
    
    public function setAmount($amount){
        $this->amount = $amount;
    }
    
    public function getAmount(){
        return $this->amount;
    }
    
    public function setBalanceAfter($balanceAfter){
        $this->balanceAfter = $balanceAfter;
    }
    
    public function getBalanceAfter(){
        return $this->balanceAfter;
    }
    
    public function setBalanceBefore($balanceBefore){
        $this->balanceBefore = $balanceBefore;
    }
    
    public function getBalanceBefore(){
        return $this->balanceBefore;
    }
    
    public function setComments($comments){
        $this->comments = $comments;
    }
    
    public function getComments(){
        return $this->comments;
    }
    
    public function setLastDueDate($lastDueDate){
        $this->lastDueDate = $lastDueDate;
    }
    
    public function getLastDueDate(){
        return $this->lastDueDate;
    }
    
    public function setNextDueDate($nextDueDate){
        $this->nextDueDate = $nextDueDate;
    }
    
    public function getNextDueDate(){
        return $this->nextDueDate;
    }
    
    public function setInterestAmount($interestAmount){
        $this->interestAmount = $interestAmount;
    }
    
    public function getInterestAmount(){
        return $this->interestAmount;
    }
    
    public function setRolloverAmount($rolloverAmount){
        $this->rolloverAmount = $rolloverAmount;
    }
    
    public function getRolloverAmount(){
        return $this->rolloverAmount;
    }
    
    public function setLoanIssue($loanIssue){
        $this->loanIssue = $loanIssue;
    }
    
    public function getLoanIssue(){
        return $this->loanIssue;
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
