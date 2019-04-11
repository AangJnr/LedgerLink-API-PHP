<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of LoanIssueFactory
 *
 * @author JCapito
 */

use App\Model\LoanIssue;
use App\Repository\MemberRepo;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\LoanIssueRepo;

class LoanIssueFactory {
    //put your code here
    protected $loanIssueInfo;
    protected $meetingInfo;
    
    protected function __construct($loanIssueInfo, $meetingInfo){
        $this->loanIssueInfo = $loanIssueInfo;
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->loanIssueInfo)){
            for($i = 0; $i < count($this->loanIssueInfo); $i++){
                $loanIssueData = $this->loanIssueInfo[$i];
                $loanIssue = new LoanIssue();
                if(array_key_exists("LoanId", $loanIssueData)){
                    $loanIssue->setLoanIdEx($loanIssueData["LoanId"]);
                }
                if(array_key_exists("MemberId", $loanIssueData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($targetVsla->getID(), $loanIssueData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($memberId))->getMember();
                        $loanIssue->setMember($member);
                    }
                }
                if(array_key_exists("PrincipalAmount", $loanIssueData)){
                    $loanIssue->setPrincipalAmount($loanIssueData["PrincipalAmount"]);
                }
                if(array_key_exists("InterestAmount", $loanIssueData)){
                    $loanIssue->setInterestAmount($loanIssueData["InterestAmount"]);
                }
                if(array_key_exists("TotalRepaid", $loanIssueData)){
                    $loanIssue->setTotalRepaid($loanIssueData["TotalRepaid"]);
                }
                if(array_key_exists("LoanBalance", $loanIssueData)){
                    $loanIssue->setBalance($loanIssueData["LoanBalance"]);
                }
                if(array_key_exists("DateDue", $loanIssueData)){
                    $loanIssue->setDueDate($loanIssueData["DateDue"]);
                }
                if(array_key_exists("Comments", $loanIssueData)){
                    $loanIssue->setComments($loanIssueData["Comments"]);
                }
                if(array_key_exists("DateCleared", $loanIssueData)){
                    $loanIssue->setDateCleared($loanIssueData["DateCleared"]);
                }
                if(array_key_exists("IsCleared", $loanIssueData)){
                    if($loanIssueData["IsCleared"]){
                        $loanIssue->setIsCleared(1);
                    }else{
                        $loanIssue->setIsCleared(0);
                    }
                }
                if(array_key_exists("IsDefaulted", $loanIssueData)){
                    if($loanIssueData["IsDefaulted"]){
                        $loanIssue->setIsDefaulted(1);
                    }else{
                        $loanIssue->setIsDefaulted(0);
                    }
                }
                if(array_key_exists("IsWrittenOff", $loanIssueData)){
                    if($loanIssueData["IsWrittenOff"]){
                        $loanIssue->setIsWrittenOff(1);
                    }else{
                        $loanIssue->setIsWrittenOff(0);
                    }
                }
                if(array_key_exists("MeetingId", $this->meetingInfo) && array_key_exists("CycleId", $this->meetingInfo)){
                    $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                    if($vslaCycleId != null){
                        $meetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $this->meetingInfo["MeetingId"]);
                        $meeting = (new MeetingRepo($meetingId))->getMeeting();
                        $loanIssue->setMeeting($meeting);
                    }
                }
                LoanIssueRepo::save($loanIssue);
            }
        }
    }
    
    public static function process($loanIssueInfo, $meetingInfo, $targetVsla){
        (new LoanIssueFactory($loanIssueInfo, $meetingInfo))->__process($targetVsla);
    }
}
