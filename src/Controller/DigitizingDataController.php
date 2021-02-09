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

class DigitizingDataController extends AppController {
    //put your code here
    
    public function testing(){
        
    }
    
    public function activate(){
        $this->viewBuilder()->layout("blank");
        
        $postData = filter_input_array(INPUT_POST);
        if(is_array($postData)){
            if(count($postData) > 0){
                $jsonString = file_get_contents("php://input");
//               $jsonString = "{\"PhoneImei\":\"656661478122498\",\"SimImsi\":\"272026984663440\",\"SimSerialNo\":\"8935302399698466344\",\"NetworkOperatorName\":\"O2\",\"NetworkType\":\"LTE\",\"VslaCode\":\"VS123456\",\"PassKey\":\"123456\"}";
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
            }
        }
        $this->set("jsonData", json_encode(array("StatusCode" => "403", "MeetingId" => "0", "Message" => "Invalid Data Type")));
    }
    
    public function processSubmittedData(){
        $this->viewBuilder()->layout("blank");
        $numberOfUnprocessedDataSubmissions = DataSubmissionRepo::getCountOfUnProcessedDataSubmissions();
        for($i = 0; $i < $numberOfUnprocessedDataSubmissions; $i++){
            $dataSubmissionID = DataSubmissionRepo::getIdAtIndex($i);
            $dataSubmissionRepo = new DataSubmissionRepo($dataSubmissionID);
            $dataSubmission = $dataSubmissionRepo->getDataSubmission();
            $submittedData = json_decode($dataSubmission->getData(), true);
            if(is_array($submittedData)){
                if(array_key_exists("HeaderInfo", $submittedData)){
                    $headerInfo = $submittedData["HeaderInfo"];
                    $vslaDbActivationID = VslaDbActivationFactory::authenticate($headerInfo["VslaCode"], $headerInfo["PassKey"]);
                    if($vslaDbActivationID != null){
                        $vslaDbActivation = (new VslaDbActivationRepo($vslaDbActivationID))->getVslaDbActivation();
                        $targetVsla = $vslaDbActivation->getVsla();
                        
                        if(array_key_exists("VslaCycleInfo", $submittedData)){
                            VslaCycleFactory::process($submittedData["VslaCycleInfo"], $targetVsla);
                        }

                        if(array_key_exists("MembersInfo", $submittedData)){
                            MemberFactory::process($submittedData["MembersInfo"], $targetVsla);
                        }
                        
                        if(array_key_exists("MeetingInfo", $submittedData)){
                            MeetingFactory::process($submittedData["MeetingInfo"], $targetVsla);
                        }
                        
                        if(array_key_exists("AttendanceInfo", $submittedData)){
                            AttendanceFactory::process($submittedData["AttendanceInfo"], $submittedData["MeetingInfo"], $targetVsla);
                        }
                        
                        if(array_key_exists("SavingInfo", $submittedData)){
                            SavingFactory::process($submittedData["SavingInfo"], $submittedData["MeetingInfo"], $targetVsla);
                        }

                        if(array_key_exists("FinesInfo", $submittedData)){
                            FineFactory::process($submittedData["FinesInfo"], $submittedData["MeetingInfo"], $targetVsla);
                        }

                        if(array_key_exists("LoansInfo", $submittedData)){
                            LoanIssueFactory::process($submittedData["LoansInfo"], $submittedData["MeetingInfo"], $targetVsla);
                        }

                        if(array_key_exists("RepaymentsInfo", $submittedData)){
                            LoanRepaymentFactory::process($submittedData["RepaymentsInfo"], $submittedData["MeetingInfo"], $targetVsla);
                        }
                        if(array_key_exists("WelfareInfo", $submittedData)){
                            WelfareFactory::process($submittedData["WelfareInfo"], $submittedData["MeetingInfo"], $targetVsla);
                        }

                        if(array_key_exists("OutstandingWelfareInfo", $submittedData)){
                            OutstandingWelfareFactory::process($submittedData["OutstandingWelfareInfo"], $submittedData["MeetingInfo"], $targetVsla);
                        }
                        $dataSubmissionRepo->updateProcessedFlag(true);
                    }
                }
            }
        }
        
    }
    
    public function submitdata(){
        $this->viewBuilder()->layout("blank");
        
        $postData = filter_input_array(INPUT_POST);
        if(is_array($postData)){
            if(count($postData) > 0){
                $jsonString = file_get_contents("php://input");
                if(strlen($jsonString) > 0){
                    $submittedData= json_decode($jsonString, true);
                    if(is_array($submittedData)){
                        if(array_key_exists("FileSubmission", $submittedData)){
                            $jsonResponse = array();
                            for($i=0; $i < count($submittedData["FileSubmission"]); $i++){
                                $fileSubmission = $submittedData["FileSubmission"][$i];
                                if(array_key_exists("HeaderInfo", $fileSubmission)){
                                    $headerInfo = $fileSubmission["HeaderInfo"];
                                    $vslaDbActivationID = VslaDbActivationFactory::authenticate($headerInfo["VslaCode"], $headerInfo["PassKey"]);
                                    if($vslaDbActivationID != null){
                                        $submissionResult = DataSubmissionFactory::process($headerInfo, json_encode($fileSubmission));
                                        if($submissionResult > 0){
                                            if(array_key_exists("MeetingInfo", $fileSubmission)){
                                                array_push($jsonResponse, array("StatusCode" => "0", "MeetingId" => "{$fileSubmission["MeetingInfo"]["MeetingId"]}"));
                                            }
                                        }else{
                                            array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0"));
                                        }
                                    }else{
                                        array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0"));
                                    }
                                }
                            }
                            if(count($jsonResponse) > 0){
                                $this->set("jsonData", json_encode($jsonResponse));
                            }else{
                                $this->set("jsonData", json_encode(array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0"))));
                            }
                        }else{
                            $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0"))));
                        }
                    }else{
                        $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0"))));
                    }
                }else{
                    $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0"))));
                }
            }else{
                $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0"))));
            }
        }else{
            $this->set("jsonData", json_encode(array(array("StatusCode" => "1", "MeetingId" => "0"))));
        }
    }
}
