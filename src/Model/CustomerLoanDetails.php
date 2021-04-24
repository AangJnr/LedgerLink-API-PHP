<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of CustomerLoanDetails
 *
 * @author JCapito
 */
class CustomerLoanDetails {
    //put your code here
    protected $loanNumber;
    protected $loanStatus;
    protected $loanAmountDue;
    protected $daysInArrears;
    protected $amountInArrears;
    protected $repaymentRate;
    protected $averageLoanAmount;
    protected $numberOfLoansDisbursed;
    protected $clientCode;
    
    public function setClientCode($clientCode){
        $this->clientCode = $clientCode;
    }
    
    public function getClientCode(){
        return $this->clientCode;
    }
    
    public function setAmountInArrears($amountInArrears){
        $this->amountInArrears = $amountInArrears;
    }
    
    public function getAmountInArrears(){
        return $this->amountInArrears;
    }
    
    public function setNumberOfLoansDisbursed($numberOfLoansDisbursed){
        $this->numberOfLoansDisbursed = $numberOfLoansDisbursed;
    }
    
    public function getNumberOfLoansDisbursed(){
        return $this->numberOfLoansDisbursed;
    }
    
    public function setAverageLoanAmount($averageLoanAmount){
        $this->averageLoanAmount = $averageLoanAmount;
    }
    
    public function getAverageLoanAmount(){
        return $this->averageLoanAmount;
    }
    
    public function setRepaymentRate($repaymentRate){
        $this->repaymentRate = $repaymentRate;
    }
    
    public function getRepaymentRate(){
        return $this->repaymentRate;
    }
    
    public function setLoanNumber($loanNumber){
        $this->loanNumber = $loanNumber;
    }
    
    public function getLoanNumber(){
        return $this->loanNumber;
    }
    
    public function setLoanStatus($loanStatus){
        $this->loanStatus = $loanStatus;
    }
    
    public function getLoanStatus(){
        return $this->loanStatus;
    }
    
    public function setLoanAmountDue($loanAmountDue){
        $this->loanAmountDue = $loanAmountDue;
    }
    
    public function getLoanAmountDue(){
        return $this->loanAmountDue;
    }
    
    public function setDaysInArrears($daysInArrears){
        $this->daysInArrears = $daysInArrears;
    }
    
    public function getDaysInArrears(){
        return $this->daysInArrears;
    }
}
