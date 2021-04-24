<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of LoanRepaymentFactory
 *
 * @author JCapito
 */
use App\Model\LoanRepayment;
use App\Repository\MemberRepo;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\LoanRepaymentRepo;
use App\Repository\LoanIssueRepo;

class LoanRepaymentFactory {
    //put your code here
    protected $loanRepaymentInfo;
    protected $meetingInfo;
    protected $db;
    
    protected function __construct($db, $loanRepaymentInfo, $meetingInfo){
        $this->loanRepaymentInfo = $loanRepaymentInfo;
        $this->meetingInfo = $meetingInfo;
        $this->db = $db;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->loanRepaymentInfo)){
            $index = 0;
            for($i = 0; $i < count($this->loanRepaymentInfo); $i++){
                $loanRepaymentData = $this->loanRepaymentInfo[$i];
                $loanRepayment = new LoanRepayment();
                
                if(array_key_exists("MemberId", $loanRepaymentData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($this->db, $targetVsla->getID(), $loanRepaymentData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($this->db, $memberId))->getMember();
                        $loanRepayment->setMember($member);
                        
                        if(array_key_exists("MeetingId", $this->meetingInfo) && array_key_exists("CycleId", $this->meetingInfo)){
                            $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($this->db, $targetVsla->getID(), $this->meetingInfo["CycleId"]);
                            if($vslaCycleId != null){
                                $meetingId = MeetingRepo::getIDByMeetingIDEx($this->db, $vslaCycleId, $this->meetingInfo["MeetingId"]);
                                $meeting = (new MeetingRepo($this->db, $meetingId))->getMeeting();
                                $loanRepayment->setMeeting($meeting);

                                if(array_key_exists("LoanId", $loanRepaymentData)){
                                    $loanId = LoanIssueRepo::getIDFromVslaIdAndLoanIdEx($this->db, $vslaCycleId, $loanRepaymentData["LoanId"]);
                                    $loanIssue = (new LoanIssueRepo($this->db, $loanId))->getLoanIssue();
                                    $loanRepayment->setLoanIssue($loanIssue);
                                }
                            }
                        }

                        if(array_key_exists("Amount", $loanRepaymentData)){
                            $loanRepayment->setAmount($loanRepaymentData["Amount"]);
                        }
                        if(array_key_exists("BalanceBefore", $loanRepaymentData)){
                            $loanRepayment->setBalanceBefore($loanRepaymentData["BalanceBefore"]);
                        }
                        if(array_key_exists("BalanceAfter", $loanRepaymentData)){
                            $loanRepayment->setBalanceAfter($loanRepaymentData["BalanceAfter"]);
                        }
                        if(array_key_exists("InterestAmount", $loanRepaymentData)){
                            $loanRepayment->setInterestAmount($loanRepaymentData["InterestAmount"]);
                        }
                        if(array_key_exists("RolloverAmount", $loanRepaymentData)){
                            $loanRepayment->setRolloverAmount($loanRepaymentData["RolloverAmount"]);
                        }
                        if(array_key_exists("Comments", $loanRepaymentData)){
                            $loanRepayment->setComments($loanRepaymentData["Comments"]);
                        }
                        if(array_key_exists("LastDateDue", $loanRepaymentData)){
                            $loanRepayment->setLastDueDate($loanRepaymentData["LastDateDue"]);
                        }
                        if(array_key_exists("NextDateDue", $loanRepaymentData)){
                            $loanRepayment->setNextDueDate($loanRepaymentData["NextDateDue"]);
                        }
                        if(array_key_exists("RepaymentId", $loanRepaymentData)){
                            $loanRepayment->setLoanRepaymentIdEx($loanRepaymentData["RepaymentId"]);
                        }
                        if(LoanRepaymentRepo::save($this->db, $loanRepayment) > -1){
                            $index++;
                        }
                    }
                }
            }
            if($index > 0){
                return 1;
            }
        }
        return 0;
    }
    
    public static function process($db, $loanRepaymentInfo, $meetingInfo, $targetVsla){
        return (new LoanRepaymentFactory($db, $loanRepaymentInfo, $meetingInfo))->__process($targetVsla);
    }
}
