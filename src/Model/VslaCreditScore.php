<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VslaCreditScore
 *
 * @author JCapito
 */
namespace App\Model;

class VslaCreditScore {
    //put your code here
    protected $ID;
    protected $requestedLoanAmount;
    protected $daysInArrears;
    protected $amountInArrears;
    protected $loanRepaymentHistory;
    protected $loanRepaymentRate;
    protected $numberOfLoansDisbursed;
    protected $numberOfYearsOfOperation;
    protected $averageAttendanceRate;
    protected $volumeOfSavingsInPreviousCycle;
    protected $loanFundUtilization;
    protected $volumeOfSavingsInCurrentCycle;
    protected $percentageOfMembersWithActiveLoan;
    protected $loanWriteOffInPreviousCycle;
    protected $portfolioAtRisk;
    protected $finalScore;
    protected $decision;
    protected $assessmentAmount;
    protected $vsla;
    protected $clientCode;
    protected $previousLoanNumber;
    protected $loanStatus;
    protected $loanAmountDue;
    protected $averageLoanAmount;
    protected $dateProcessed;
    protected $previousLoanAmount;
    
    public function setPreviousLoanAmount($previousLoanAmount){
        $this->previousLoanAmount = $previousLoanAmount;
    }
    
    public function getPreviousLoanAmount(){
        return $this->previousLoanAmount;
    }
    
    public function setDateProcessed($dateProcessed){
        $this->dateProcessed = $dateProcessed;
    }
    
    public function getDateProcessed(){
        return $this->dateProcessed;
    }
    
    public function setAverageLoanAmount($averageLoanAmount){
        $this->averageLoanAmount = $averageLoanAmount;
    }
    
    public function getAverageLoanAmount(){
        return $this->averageLoanAmount;
    }
    
    public function setLoanAmountDue($loanAmountDue){
        $this->loanAmountDue = $loanAmountDue;
    }
    
    public function getLoanAmountDue(){
        return $this->loanAmountDue;
    }
    
    public function setLoanStatus($loanStatus){
        $this->loanStatus = $loanStatus;
    }
    
    public function getLoanStatus(){
        return $this->loanStatus;
    }
    
    public function setPreviousLoanNumber($previousLoanNumber){
        $this->previousLoanNumber = $previousLoanNumber;
    }
    
    public function getPreviousLoanNumber(){
        return $this->previousLoanNumber;
    }
    
    public function setClientCode($clientCode){
        $this->clientCode = $clientCode;
    }
    
    public function getClientCode(){
        return $this->clientCode;
    }
    
    public function setVsla($vsla){
        $this->vsla = $vsla;
    }
    
    public function getVsla(){
        return $this->vsla;
    }
    
    public function setAssessmentAmount($assessmentAmount){
        $this->assessmentAmount = $assessmentAmount;
    }
    
    public function getAssessmentAmount(){
        return $this->assessmentAmount;
    }
    
    public function setDecision($decision){
        $this->decision = $decision;
    }
    
    public function getDecision(){
        return $this->decision;
    }
    
    public function setFinalScore($finalScore){
        $this->finalScore = $finalScore;
    }
    
    public function getFinalScore(){
        return $this->finalScore;
    }
    
    public function setPortfolioAtRisk($portfolioAtRisk){
        $this->portfolioAtRisk = $portfolioAtRisk;
    }
    
    public function getPortfolioAtRisk(){
        return $this->portfolioAtRisk;
    }
    
    public function setLoanWriteOffInPreviousCycle($loanWriteOffInPreviousCycle){
        $this->loanWriteOffInPreviousCycle = $loanWriteOffInPreviousCycle;
    }
    
    public function getLoanWriteOffInPreviousCycle(){
        return $this->loanWriteOffInPreviousCycle;
    }
    
    public function setPercentageOfMembersWithActiveLoans($percentageOfMembersWithActiveLoan){
        $this->percentageOfMembersWithActiveLoan = $percentageOfMembersWithActiveLoan;
    }
    
    public function getPercentageOfMembersWithActiveLoans(){
        return $this->percentageOfMembersWithActiveLoan;
    }
    
    public function setVolumeOfSavingsInCurrentCycle($volumeOfSavingsInCurrentCycle){
        $this->volumeOfSavingsInCurrentCycle = $volumeOfSavingsInCurrentCycle;
    }
    
    public function getVolumeOfSavingsInCurrentCycle(){
        return $this->volumeOfSavingsInCurrentCycle;
    }
    
    public function setLoanFundUtilization($loanFundUtilization){
        $this->loanFundUtilization = $loanFundUtilization;
    }
    
    public function getLoanFundUtilization(){
        return $this->loanFundUtilization;
    }
    
    public function setVolumeOfSavingsInPreviousCycle($volumeOfSavingsInPreviousCycle){
        $this->volumeOfSavingsInPreviousCycle = $volumeOfSavingsInPreviousCycle;
    }
    
    public function getVolumeOfSavingsInPreviousCycle(){
        return $this->volumeOfSavingsInPreviousCycle;
    }
    
    public function setAverageAttendanceRate($averageAttendanceRate){
        $this->averageAttendanceRate = $averageAttendanceRate;
    }
    
    public function getAverageAttendanceRate(){
        return $this->averageAttendanceRate;
    }
    
    public function setNumberOfYearsOfOperation($numberOfYearsOfOperation){
        $this->numberOfYearsOfOperation = $numberOfYearsOfOperation;
    }
    
    public function getNumberOfYearsOfOperation(){
        return $this->numberOfYearsOfOperation;
    }
    
    public function setNumberOfLoansDisbursed($numberOfLoansDisbursed){
        $this->numberOfLoansDisbursed = $numberOfLoansDisbursed;
    }
    
    public function getNumberOfLoansDisbursed(){
       return $this->numberOfLoansDisbursed; 
    }
    
    public function setLoanRepaymentRate($loanRepaymentRate){
        $this->loanRepaymentRate = $loanRepaymentRate;
    }
    
    public function getLoanRepaymentRate(){
        return $this->loanRepaymentRate;
    }
    
    public function setLoanRepaymentHistory($loanRepaymentHistory){
        $this->loanRepaymentHistory = $loanRepaymentHistory;
    }
    
    public function getLoanRepaymentHistory(){
        return $this->loanRepaymentHistory;
    }
    
    public function setAmountInArrears($amountInArrears){
        $this->amountInArrears = $amountInArrears;
    }
    
    public function getAmountInArrears(){
        return $this->amountInArrears;
    }
    
    public function setDaysInArrears($daysInArrears){
        $this->daysInArrears = $daysInArrears;
    }
    
    public function getDaysInArrears(){
        return $this->daysInArrears;
    }
    
    public function setRequestedLoanAmount($requestedLoanAmount){
        $this->requestedLoanAmount = $requestedLoanAmount;
    }
    
    public function getRequestedLoanAmount(){
        return $this->requestedLoanAmount;
    }
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
}
