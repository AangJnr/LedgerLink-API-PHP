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

class DigitizingDataController extends AppController {
    //put your code here
    
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
    
    public function submitdata(){
        $this->viewBuilder()->layout("blank");
        
        $postData = filter_input_array(INPUT_GET);
        if(is_array($postData)){
            if(count($postData) > 0){
//                $jsonString = file_get_contents("php://input");
                $jsonString = "{\"FileSubmission\":[{"
                        . "\"HeaderInfo\":{\"FinancialInstitution\":\"POST_BANK_UGANDA\",\"VslaCode\":\"VS123456\",\"PhoneImei\":\"656661478122498\",\"NetworkOperator\":\"O2\",\"NetworkType\":\"LTE\",\"PassKey\":\"123456\",\"AppVersion\":\"Version 2016112501\"},"
                        . "\"VslaCycleInfo\":{\"CycleId\":\"1\",\"StartDate\":\"2019-03-19\",\"EndDate\":\"2020-03-18\",\"SharePrice\":\"10000.0\",\"MaxShareQty\":\"5\",\"MaxStartShare\":\"0.0\",\"InterestRate\":\"10.0\"},"
                        . "\"MembersInfo\":[{\"MemberId\":\"1\",\"MemberNo\":\"1\",\"Surname\":\"Capito\",\"OtherNames\":\"Joseph\",\"Gender\":\"Male\",\"DateOfBirth\":\"1993-03-19\",\"Occupation\":\"Farmer\",\"PhoneNumber\":\"0781926927\",\"CyclesCompleted\":\"0\",\"IsActive\":true,\"IsArchived\":\"false\"},{\"MemberId\":\"2\",\"MemberNo\":\"2\",\"Surname\":\"Aguga\",\"OtherNames\":\"Judith\",\"Gender\":\"Female\",\"DateOfBirth\":\"1990-03-19\",\"Occupation\":\"Painter\",\"PhoneNumber\":null,\"CyclesCompleted\":\"1\",\"IsActive\":true,\"IsArchived\":\"false\"},{\"MemberId\":\"3\",\"MemberNo\":\"3\",\"Surname\":\"Kago\",\"OtherNames\":\"Cecil\",\"Gender\":\"Female\",\"DateOfBirth\":\"1999-03-19\",\"Occupation\":\"Welder\",\"PhoneNumber\":null,\"CyclesCompleted\":\"1\",\"IsActive\":true,\"IsArchived\":\"false\"}],"
                        . "\"MeetingInfo\":{\"CycleId\":\"1\",\"MeetingId\":\"2\",\"MeetingDate\":\"2019-03-19\",\"OpeningBalanceBox\":\"0.0\",\"OpeningBalanceBank\":\"0.0\",\"Fines\":\"0.0\",\"MembersPresent\":\"3\",\"Savings\":\"150000.0\",\"LoansPaid\":\"0.0\",\"LoansIssued\":\"0.0\",\"ClosingBalanceBox\":\"0.0\",\"ClosingBalanceBank\":\"0.0\",\"IsCashBookBalanced\":\"false\",\"IsDataSent\":\"false\",\"LoanFromBank\":\"0.0\",\"BankLoanRepayment\":\"0.0\",\"AttendanceRate\":\"100.0\",\"SavingsRate\":\"100.0\",\"Welfare\":\"3000.0\",\"OutstandingWelfare\":\"0.0\"},"
                        . "\"AttendanceInfo\":[{\"AttendanceId\":\"1\",\"MemberId\":\"1\",\"IsPresentFlag\":\"1\",\"Comments\":null},{\"AttendanceId\":\"2\",\"MemberId\":\"2\",\"IsPresentFlag\":\"1\",\"Comments\":null},{\"AttendanceId\":\"3\",\"MemberId\":\"3\",\"IsPresentFlag\":\"1\",\"Comments\":null}],"
                        . "\"SavingInfo\":[{\"SavingId\":\"4\",\"MemberId\":\"1\",\"Amount\":\"50000.0\"},{\"SavingId\":\"5\",\"MemberId\":\"2\",\"Amount\":\"50000.0\"},{\"SavingId\":\"6\",\"MemberId\":\"3\",\"Amount\":\"50000.0\"}],"
                        . "\"FinesInfo\":[],"
                        . "\"RepaymentsInfo\":[],"
                        . "\"LoansInfo\":[],"
                        . "\"WelfareInfo\":[{\"WelfareId\":\"4\",\"MeetingId\":\"2\",\"MemberId\":\"1\",\"Amount\":\"1000.0\",\"Comment\":\"\"},{\"WelfareId\":\"5\",\"MeetingId\":\"2\",\"MemberId\":\"2\",\"Amount\":\"1000.0\",\"Comment\":\"\"},{\"WelfareId\":\"6\",\"MeetingId\":\"2\",\"MemberId\":\"3\",\"Amount\":\"1000.0\",\"Comment\":\"\"}],"
                        . "\"OutstandingWelfareInfo\":[]}]}";
                
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
                                            $vslaDbActivation = (new VslaDbActivationRepo($vslaDbActivationID))->getVslaDbActivation();
                                            $targetVsla = $vslaDbActivation->getVsla();
                                            if(array_key_exists("VslaCycleInfo", $fileSubmission)){
                                                VslaCycleFactory::process($fileSubmission["VslaCycleInfo"], $targetVsla);
                                            }

                                            if(array_key_exists("MembersInfo", $fileSubmission)){
                                                MemberFactory::process($fileSubmission["MembersInfo"], $targetVsla);
                                            }

                                            if(array_key_exists("MeetingInfo", $fileSubmission)){
                                                array_push($jsonResponse, array("StatusCode" => "0", "MeetingId" => "{$fileSubmission["MeetingInfo"]["MeetingId"]}"));
                                                MeetingFactory::process($fileSubmission["MeetingInfo"], $targetVsla);
                                            }

                                            if(array_key_exists("AttendanceInfo", $fileSubmission)){
                                                AttendanceFactory::process($fileSubmission["AttendanceInfo"], $fileSubmission["MeetingInfo"], $targetVsla);
                                            }

                                            if(array_key_exists("SavingInfo", $fileSubmission)){
                                                SavingFactory::process($fileSubmission["SavingInfo"], $fileSubmission["MeetingInfo"], $targetVsla);
                                            }

                                            if(array_key_exists("FinesInfo", $fileSubmission)){
                                                FineFactory::process($fileSubmission["FinesInfo"], $fileSubmission["MeetingInfo"], $targetVsla);
                                            }

                                            if(array_key_exists("LoansInfo", $fileSubmission)){
                                                LoanIssueFactory::process($fileSubmission["LoansInfo"], $fileSubmission["MeetingInfo"], $targetVsla);
                                            }

                                            if(array_key_exists("RepaymentInfo", $fileSubmission)){
                                                LoanRepaymentFactory::process($fileSubmission["RepaymentInfo"], $fileSubmission["MeetingInfo"], $targetVsla);
                                            }
                                            if(array_key_exists("WelfareInfo", $fileSubmission)){
                                                WelfareFactory::process($fileSubmission["WelfareInfo"], $fileSubmission["MeetingInfo"], $targetVsla);
                                            }
                
                                            if(array_key_exists("OutstandingWelfareInfo", $fileSubmission)){
                                                OutstandingWelfareFactory::process($fileSubmission["OutstandingWelfareInfo"], $fileSubmission["MeetingInfo"], $targetVsla);
                                            }
                                        }else{
                                            $this->set("jsonData", "Encountered an internal error " . $submissionResult);
                                            break;
                                        }

                                    }else{
                                        $this->set("jsonData", json_encode(array_push($jsonResponse, array("StatusCode" => "404", "MeetingId" => "0"))));
                                        break;
                                    }
                                }else{
                                    //$this->set("jsonData", "Invalid Vsla");
                                }
                            }
                        if(count($jsonResponse) > 0){
                            $this->set("jsonData", json_encode($jsonResponse));
                        }else{
                            $this->set("jsonData", json_encode(array_push($jsonResponse, array("StatusCode" => "1", "MeetingId" => "0"))));
                        }
                    }
                    }
                }
            }else{
                $this->set("jsonData", json_encode(array(array("StatusCode" => "2", "MeetingId" => "0"))));
            }
        }else{
            $this->set("jsonData", json_encode(array(array("StatusCode" => "3", "MeetingId" => "0"))));
        }
    }
}
