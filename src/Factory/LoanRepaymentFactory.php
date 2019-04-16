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
    
    protected function __construct($loanRepaymentInfo, $meetingInfo){
        $this->loanRepaymentInfo = $loanRepaymentInfo;
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->loanRepaymentInfo)){
            for($i = 0; $i < count($this->loanRepaymentInfo); $i++){
                $loanRepaymentData = $this->loanRepaymentInfo[$i];
                $loanRepayment = new LoanRepayment();
                if(array_key_exists("MeetingId", $this->meetingInfo) && array_key_exists("CycleId", $this->meetingInfo)){
                    $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                    if($vslaCycleId != null){
                        $meetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $this->meetingInfo["MeetingId"]);
                        $meeting = (new MeetingRepo($meetingId))->getMeeting();
                        $loanRepayment->setMeeting($meeting);
                        
                        if(array_key_exists("LoanId", $loanRepaymentData)){
                            $loanId = LoanIssueRepo::getIDFromLoanIdEx($meeting->getID(), $loanRepaymentData["LoanId"]);
                            $loanIssue = (new LoanIssueRepo($loanId))->getLoanIssue();
                            $loanRepayment->setLoanIssue($loanIssue);
                        }
                    }
                }
                if(array_key_exists("MemberId", $loanRepaymentData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($targetVsla->getID(), $loanRepaymentData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($memberId))->getMember();
                        $loanRepayment->setMember($member);
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
                LoanRepaymentRepo::save($loanRepayment);
            }
        }
    }
    
    public static function process($loanRepaymentInfo, $meetingInfo, $targetVsla){
        return (new LoanRepaymentFactory($loanRepaymentInfo, $meetingInfo))->__process($targetVsla);
    }
}
