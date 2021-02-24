<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of MeetingFactory
 *
 * @author JCapito
 */
use App\Model\Meeting;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;

class MeetingFactory {
    //put your code here
    protected $meetingInfo;
    
    protected function __construct($meetingInfo){
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->meetingInfo)){
            $meeting = new Meeting();
            if(array_key_exists("CycleId", $this->meetingInfo)){
                $cycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                if($cycleId != null){
                    $meeting->setVslaCycle((new VslaCycleRepo($cycleId))->getVslaCycle());
                    if(array_key_exists("MeetingId", $this->meetingInfo)){
                        $meeting->setMeetingIdEx($this->meetingInfo["MeetingId"]);
                    }
                    if(array_key_exists("MeetingDate", $this->meetingInfo)){
                        $meeting->setMeetingDate($this->meetingInfo["MeetingDate"]);
                    }
                    if(array_key_exists("OpeningBalanceBox", $this->meetingInfo)){
                        $meeting->setCashFromBox($this->meetingInfo["OpeningBalanceBox"]);
                    }
                    if(array_key_exists("Fines", $this->meetingInfo)){
                        $meeting->setCashFines($this->meetingInfo["Fines"]);
                    }
                    if(array_key_exists("MembersPresent", $this->meetingInfo)){
                        $meeting->setCountOfMembersPresent($this->meetingInfo["MembersPresent"]);
                    }
                    if(array_key_exists("Savings", $this->meetingInfo)){
                        $meeting->setSumOfSavings($this->meetingInfo["Savings"]);
                    }
                    if(array_key_exists("LoansPaid", $this->meetingInfo)){
                        $meeting->setSumOfLoanRepayments($this->meetingInfo["LoansPaid"]);
                    }
                    if(array_key_exists("LoansIssued", $this->meetingInfo)){
                        $meeting->setSumOfLoanIssues($this->meetingInfo["LoansIssued"]);
                    }
                    if(array_key_exists("LoanFromBank", $this->meetingInfo)){
                        $meeting->setLoanFromBank($this->meetingInfo["LoanFromBank"]);
                    }
                    if(array_key_exists("BankLoanRepayment", $this->meetingInfo)){
                        $meeting->setBankLoanRepayment($this->meetingInfo["BankLoanRepayment"]);
                    }
                    $meeting->setDateSent(date("Y-m-d H:i:s"));
                    return MeetingRepo::save($meeting);
                }
            }
        }
        return -1;
    }
    
    public static function process($meetingInfo, $targetVsla){
        return (new MeetingFactory($meetingInfo))->__process($targetVsla);
    }
}
