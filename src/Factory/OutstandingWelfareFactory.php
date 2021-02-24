<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of OutstandingWelfareFactory
 *
 * @author JCapito
 */
use App\Model\OutstandingWelfare;
use App\Repository\MemberRepo;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\OutstandingWelfareRepo;

class OutstandingWelfareFactory {
    //put your code here
    protected $outstandingWelfareInfo;
    protected $meetingInfo;
    
    protected function __construct($outstandingWelfareInfo, $meetingInfo){
        $this->outstandingWelfareInfo = $outstandingWelfareInfo;
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->outstandingWelfareInfo)){
            $index = 0;
            for($i = 0; $i < count($this->outstandingWelfareInfo); $i++){
                $outstandingWelfareData = $this->outstandingWelfareInfo[$i];
                $outstandingWelfare = new OutstandingWelfare();
                if(array_key_exists("OutstandingWelfareId", $outstandingWelfareData)){
                    $outstandingWelfare->setOutstandingWelfareIdEx($outstandingWelfareData["OutstandingWelfareId"]);
                }
                if(array_key_exists("MemberId", $outstandingWelfareData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($targetVsla->getID(), $outstandingWelfareData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($memberId))->getMember();
                        $outstandingWelfare->setMember($member);
                    }
                }
                if(array_key_exists("Amount", $outstandingWelfareData)){
                    $outstandingWelfare->setAmount($outstandingWelfareData["Amount"]);
                }
                if(array_key_exists("ExpectedDate", $outstandingWelfareData)){
                    $outstandingWelfare->setExpectedDate($outstandingWelfareData["ExpectedDate"]);
                }
                if(array_key_exists("DateCleared", $outstandingWelfareData)){
                    $outstandingWelfare->setDateCleared($outstandingWelfareData["DateCleared"]);
                }
                if(array_key_exists("Comment", $outstandingWelfareData)){
                    $outstandingWelfare->setComment($outstandingWelfareData["Comment"]);
                }
                if(array_key_exists("IsCleared", $outstandingWelfareData)){
                    if($outstandingWelfareData["IsCleared"]){
                        $outstandingWelfare->setIsCleared(1);
                    }else{
                        $outstandingWelfare->setIsCleared(0);
                    }
                }
                if(array_key_exists("PaidInMeetingId", $outstandingWelfareData)){
                    if(array_key_exists("CycleId", $this->meetingInfo)){
                        $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                        if($vslaCycleId != null){
                            $paidInMeetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $outstandingWelfareData["PaidInMeetingId"]);
                            $paidInMeeting = (new MeetingRepo($paidInMeetingId))->getMeeting();
                            $outstandingWelfare->setPaidInMeeting($paidInMeeting);
                        }
                    }
                }
                if(array_key_exists("MeetingId", $outstandingWelfareData)){
                    if(array_key_exists("CycleId", $this->meetingInfo)){
                        $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                        if($vslaCycleId != null){
                            $issuedInMeetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $outstandingWelfareData["MeetingId"]);
                            $meeting = (new MeetingRepo($issuedInMeetingId))->getMeeting();
                            $outstandingWelfare->setMeeting($meeting);
                        }
                    }
                }
                if(OutstandingWelfareRepo::save($outstandingWelfare) > -1){
                    $index++;
                }
            }
            if($index == count($this->outstandingWelfareInfo)){
                return 1;
            }
        }
        return 0;
    }
    
    public static function process($outstandingWelfareInfo, $meetingInfo, $targetVsla){
        return (new OutstandingWelfareFactory($outstandingWelfareInfo, $meetingInfo))->__process($targetVsla);
    }
}
