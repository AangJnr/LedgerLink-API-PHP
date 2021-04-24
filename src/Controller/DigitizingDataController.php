<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DigitizingDataController
 *
 * @author JCapito
 */
namespace App\Controller;

use App\Controller\AppController;
use App\Factory\DataSubmissionFactory;
use App\Factory\VslaCycleFactory;
use App\Factory\MemberFactory;
use App\Factory\MeetingFactory;
use App\Factory\SavingFactory;
use App\Factory\FineFactory;
use App\Factory\AttendanceFactory;
use App\Factory\LoanIssueFactory;
use App\Factory\LoanRepaymentFactory;
use App\Factory\VslaDbActivationFactory;
use App\Factory\WelfareFactory;
use App\Factory\OutstandingWelfareFactory;
use App\Repository\VslaDbActivationRepo;
use App\Repository\VslaRepo;
use App\Repository\DataSubmissionRepo;
use App\Helpers\DatabaseHandler;

class DigitizingDataController extends AppController {
    //put your code here
    
    public function testing(){
        
    }
    
    public function activate(){
        $this->viewBuilder()->layout("blank");
        
        $jsonString = file_get_contents("php://input");
        if(strlen($jsonString) > 0){
             $submittedData = json_decode($jsonString, true);
             if(is_array($submittedData)){
                 $isProcessed = VslaDbActivationFactory::process($submittedData);
                 if($isProcessed){
                     $vslaId = VslaRepo::getVslaIdByVslaCode($submittedData["VslaCode"]);
                     $vsla = (new VslaRepo($vslaId))->getVsla();
                     $this->set("jsonData", json_encode(array("IsActivated" => $isProcessed, "PassKey" => "{$submittedData["PassKey"]}", "VslaName" => "{$vsla->getVslaName()}")));
                     return;
                 }
             }
         }
        $this->set("jsonData", json_encode(array("StatusCode" => "403", "MeetingId" => "0", "Message" => "Invalid Data Type")));
    }
    
    public function processSubmittedData(){
        $this->viewBuilder()->layout("blank");
        $db = DatabaseHandler::getInstance();
        
        $numberOfUnprocessedDataSubmissions = DataSubmissionRepo::getCountOfUnProcessedDataSubmissions($db);
        for($i = 0; $i < $numberOfUnprocessedDataSubmissions; $i++){
            $dataSubmissionID = DataSubmissionRepo::getIdAtIndex($db, $i);
            $dataSubmissionRepo = new DataSubmissionRepo($db, $dataSubmissionID);
            $dataSubmission = $dataSubmissionRepo->getDataSubmission();
            $jsonString = "{\"FileSubmission\":[".$dataSubmission->getData()."]}";
            
            $this->__processSubmittedRecords($db, $jsonString);
            
        }
        
    }
    
    
    protected function __authenticate($vslaCode, $passKey){
        return VslaDbActivationFactory::authenticate($vslaCode, $passKey) != null ? true : false;
    }
    
    protected function __processSubmittedRecords($db, $jsonString){
        if(strlen($jsonString) > 0){
            $submittedData= json_decode($jsonString, true);
            if(is_array($submittedData)){
                if(array_key_exists("FileSubmission", $submittedData)){
                    $jsonResponse = array();
                    for($i=0; $i < count($submittedData["FileSubmission"]); $i++){
                        $fileSubmission = $submittedData["FileSubmission"][$i];
                        if(array_key_exists("HeaderInfo", $fileSubmission)){
                            $headerInfo = $fileSubmission["HeaderInfo"];
                            $vslaDbActivationID = VslaDbActivationFactory::authenticate($db, $headerInfo["VslaCode"], $headerInfo["PassKey"]);
                            if($vslaDbActivationID != null){
                                $vslaDbActivation = (new VslaDbActivationRepo($db, $vslaDbActivationID))->getVslaDbActivation();
                                $targetVsla = $vslaDbActivation->getVsla();
                                $submissionResult = DataSubmissionFactory::process($db, $headerInfo, json_encode($fileSubmission));
                                if($submissionResult > 0){
                                    if(array_key_exists("VslaCycleInfo", $fileSubmission)){
                                        if(VslaCycleFactory::process($db, $fileSubmission["VslaCycleInfo"], $targetVsla) > -1){
                                            if(array_key_exists("MembersInfo", $fileSubmission)){
                                                if(MemberFactory::process($db, $fileSubmission["MembersInfo"], $targetVsla) > -1){
                                                    if(array_key_exists("MeetingInfo", $fileSubmission)){
                                                        if(MeetingFactory::process($db, $fileSubmission["MeetingInfo"], $targetVsla) > -1){
                                                            if(array_key_exists("AttendanceInfo", $fileSubmission)){
                                                                if(AttendanceFactory::process($db, $fileSubmission["AttendanceInfo"], $fileSubmission["MeetingInfo"], $targetVsla) > 0){
                                                                    $this->set("jsonData", "Attendance Processed");
                                                                }else{
                                                                    $this->set("jsonData", "Attendance Not Processed");
                                                                }
                                                                if(array_key_exists("SavingInfo", $fileSubmission)){
                                                                    if(SavingFactory::process($db, $fileSubmission["SavingInfo"], $fileSubmission["MeetingInfo"], $targetVsla) > 0){
                                                                        $this->set("jsonData", "Saving Processed");
                                                                    }else{
                                                                        $this->set("jsonData", "Saving Not Processed");
                                                                    }
                                                                }
                                                                if(array_key_exists("LoansInfo", $fileSubmission)){
                                                                    if(LoanIssueFactory::process($db, $fileSubmission["LoansInfo"], $fileSubmission["MeetingInfo"], $targetVsla) > 0){
                                                                        $this->set("jsonData", "Loan Issue Processed");
                                                                    }else{
                                                                        $this->set("jsonData", "Loan Issue Not Processed");
                                                                    }
                                                                }
                                                                if(array_key_exists("RepaymentsInfo", $fileSubmission)){
                                                                    if(LoanRepaymentFactory::process($db, $fileSubmission["RepaymentsInfo"], $fileSubmission["MeetingInfo"], $targetVsla) > 0){
                                                                        $this->set("jsonData", "Loan Repayment Processed");
                                                                    }else{
                                                                        $this->set("jsonData", "Loan Repayment Not Processed");
                                                                    }
                                                                }
                                                                if(array_key_exists("WelfareInfo", $fileSubmission)){
                                                                    if(WelfareFactory::process($db, $fileSubmission["WelfareInfo"], $fileSubmission["MeetingInfo"], $targetVsla) > 0){
                                                                        $this->set("jsonData", "Welfare Processed");
                                                                    }else{
                                                                        $this->set("jsonData", "Welfare Not Processed");
                                                                    }
                                                                }
                                                                if(array_key_exists("OutstandingWelfareInfo", $fileSubmission)){
                                                                    if(OutstandingWelfareFactory::process($db, $fileSubmission["OutstandingWelfareInfo"], $fileSubmission["MeetingInfo"], $targetVsla) > 0){
                                                                        $this->set("jsonData", "Outstanding Welfare Processed");
                                                                    }else{
                                                                        $this->set("jsonData", "Outstanding Welfare Not Processed");
                                                                    }
                                                                }
                                                                if(array_key_exists("FinesInfo", $fileSubmission)){
                                                                    if(FineFactory::process($db, $fileSubmission["FinesInfo"], $fileSubmission["MeetingInfo"], $targetVsla) > 0){
                                                                        $this->set("jsonData", "Fines Processed");
                                                                    }else{
                                                                        $this->set("jsonData", "Fines Not Processed");
                                                                    }
                                                                }
                                                                array_push($jsonResponse, array("StatusCode" => "0", "MeetingId" => "{$fileSubmission["MeetingInfo"]["MeetingId"]}", "SavedRecord" => "True", "Authenticated" => "True"));
                                                            }
                                                        }
                                                        else{
                                                            $this->set("jsonData", "Meeting Not Processed");
                                                        }
                                                    }
                                                }else{
                                                    array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0", "SavedRecord" => "False", "Authenticated" => "True"));
                                                }
                                            }
                                        }else{
                                            $this->set("jsonData", "Cycle Not Processed");
                                        }
                                    }
                                }else{
                                    array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0", "SavedRecord" => "False", "Authenticated" => "True"));
                                }
                            }else{
                                array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0", "Authenticated" => "False"));
                            }
                        }
                    }
                    if(count($jsonResponse) > 0){
                        $this->set("jsonData", json_encode($jsonResponse));
                    }else{
                        $this->set("jsonData", json_encode(array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0"))));
                    }
                }else{
                    $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0", "Message" => "No Records Captured"))));
                }
            }else{
                $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0", "Message" => "Is Not Array"))));
            }
        }else{
            $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0", "Message" => "No Data Sent"))));
        }
    }
    
    public function submitdata(){
        $this->viewBuilder()->layout("blank");
        $db = DatabaseHandler::getInstance();
        
        $jsonString = file_get_contents("php://input");
//        $dataSubmissionRepo = new DataSubmissionRepo($db, 1861);
//        $dataSubmission = $dataSubmissionRepo->getDataSubmission();
//        $jsonString = "{\"FileSubmission\":[".$dataSubmission->getData()."]}";
        $this->__processSubmittedRecords($db, $jsonString);
    }
}
