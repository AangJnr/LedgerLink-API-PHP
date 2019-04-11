<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of Meeting
 *
 * @author JCapito
 */
class Meeting {
    //put your code here
    protected $ID;
    protected $meetingIdEx;
    protected $cashExpenses = 0.00;
    protected $cashFines = 0.00;
    protected $cashFromBox = 0.00;
    protected $cashSavedBank = 0.00;
    protected $cashSavedBox = 0.00;
    protected $cashWelfare = 0.00;
    protected $dateSent;
    protected $isCurrent = 0;
    protected $isDataSent = 0;
    protected $meetingDate;
    protected $countOfMembersPresent = 0;
    protected $sumOfSavings = 0.00;
    protected $sumOfLoanIssues = 0.00;
    protected $sumOfLoanRepayments = 0.00;
    protected $loanFromBank = 0.00;
    protected $bankLoanRepayment = 0.00;
    protected $vslaCycle;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setMeetingIdEx($meetingIdEx){
        $this->meetingIdEx = $meetingIdEx;
    }
    
    public function getMeetingIdEx(){
        return $this->meetingIdEx;
    }
    
    public function setCashExpenses($cashExpenses){
        $this->cashExpenses = $cashExpenses;
    }
    
    public function getCashExpenses(){
        return $this->cashExpenses;
    }
    
    public function setCashFines($cashFines){
        $this->cashFines = $cashFines;
    }
    
    public function getCashFines(){
        return $this->cashFines;
    }
    
    public function setCashFromBox($cashFromBox){
        $this->cashFromBox = $cashFromBox;
    }
    
    public function getCashFromBox(){
        return $this->cashFromBox;
    }
    
    public function setCashSavedBank($cashSavedBank){
        $this->cashSavedBank = $cashSavedBank;
    }
    
    public function getCashSavedBank(){
        return $this->cashSavedBank;
    }
    
    public function setCashSavedBox($cashSavedBox){
        $this->cashSavedBox = $cashSavedBox;
    }
    
    public function getCashSavedBox(){
        return $this->cashSavedBox;
    }
    
    public function setCashWelfare($cashWelfare){
        $this->cashWelfare = $cashWelfare;
    }
    
    public function getCashWelfare(){
        return $this->cashWelfare;
    }
    
    public function setDateSent($dateSent){
        $this->dateSent = $dateSent;
    }
    
    public function getDateSent(){
        return $this->dateSent;
    }
    
    public function setIsCurrent($isCurrent){
        $this->isCurrent = $isCurrent;
    }
    
    public function getIsCurrent(){
        return $this->isCurrent;
    }
    
    public function setIsDataSent($isDataSent){
        $this->isDataSent = $isDataSent;
    }
    
    public function getIsDataSent(){
        return $this->isDataSent;
    }
    
    public function setMeetingDate($meetingDate){
        $this->meetingDate = $meetingDate;
    }
    
    public function getMeetingDate(){
        return $this->meetingDate;
    }
    
    public function setCountOfMembersPresent($countOfMembersPresent){
        $this->countOfMembersPresent = $countOfMembersPresent;
    }
    
    public function getCountOfMembersPresent(){
        return $this->countOfMembersPresent;
    }
    
    public function setSumOfSavings($sumOfSavings){
        $this->sumOfSavings = $sumOfSavings;
    }
    
    public function getSumOfSavings(){
        return $this->sumOfSavings;
    }
    
    public function setSumOfLoanIssues($sumOfLoanIssues){
        $this->sumOfLoanIssues = $sumOfLoanIssues;
    }
    
    public function getSumOfLoanIssues(){
        return $this->sumOfLoanIssues;
    }
    
    public function setSumOfLoanRepayments($sumOfLoanRepayments){
        $this->sumOfLoanRepayments = $sumOfLoanRepayments;
    }
    
    public function getSumOfLoanRepayments(){
        return $this->sumOfLoanRepayments;
    }
    
    public function setLoanFromBank($loanFromBank){
        $this->loanFromBank = $loanFromBank;
    }
    
    public function getLoanFromBank(){
        return $this->loanFromBank;
    }
    
    public function setBankLoanRepayment($bankLoanRepayment){
        $this->bankLoanRepayment = $bankLoanRepayment;
    }
    
    public function getBankLoanRepayment(){
        return $this->bankLoanRepayment;
    }
    
    public function setVslaCycle($vslaCycle){
        $this->vslaCycle = $vslaCycle;
    }
    
    public function getVslaCycle(){
        return $this->vslaCycle;
    }
}
