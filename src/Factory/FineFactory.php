<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of FineFactory
 *
 * @author JCapito
 */
use App\Model\Fine;
use App\Repository\MemberRepo;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\FineRepo;

class FineFactory {
    //put your code here
    protected $fineInfo;
    protected $meetingInfo;
    
    protected function __construct($fineInfo, $meetingInfo){
        $this->fineInfo = $fineInfo;
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->fineInfo)){
            for($i = 0; $i < count($this->fineInfo); $i++){
                $fineData = $this->fineInfo[$i];
                $fine = new Fine();
                if(array_key_exists("FineId", $fineData)){
                    $fine->setFineIdEx($fineData["FineId"]);
                }
                if(array_key_exists("MemberId", $fineData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($targetVsla->getID(), $fineData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($memberId))->getMember();
                        $fine->setMember($member);
                    }
                }
                if(array_key_exists("Amount", $fineData)){
                    $fine->setAmount($fineData["Amount"]);
                }
                if(array_key_exists("FineTypeId", $fineData)){
                    $fine->setFineTypeId($fineData["FineTypeId"]);
                }
                if(array_key_exists("DateCleared", $fineData)){
                    $fine->setDateCleared($fineData["DateCleared"]);
                }
                if(array_key_exists("IsCleared", $fineData)){
                    if($fineData["IsCleared"]){
                        $fine->setIsCleared(1);
                    }else{
                        $fine->setIsCleared(0);
                    }
                }
                if(array_key_exists("PaidInMeetingId", $fineData)){
                    $fine->setPaidInMeetingIdEx($fineData["PaidInMeetingId"]);
                    if(array_key_exists("CycleId", $this->meetingInfo)){
                        $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                        if($vslaCycleId != null){
                            $paidInMeetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $fineData["PaidInMeetingId"]);
                            $paidInMeeting = (new MeetingRepo($paidInMeetingId))->getMeeting();
                            $fine->setPaidInMeeting($paidInMeeting);
                        }
                    }
                }
                if(array_key_exists("MeetingId", $fineData)){
                    $fine->setIssuedInMeetingIdEx($fineData["MeetingId"]);
                    if(array_key_exists("CycleId", $this->meetingInfo)){
                        $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                        if($vslaCycleId != null){
                            $issuedInMeetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $fineData["MeetingId"]);
                            $issuedInMeeting = (new MeetingRepo($issuedInMeetingId))->getMeeting();
                            $fine->setIssuedInMeeting($issuedInMeeting);
                        }
                    }
                }
                FineRepo::save($fine);
            }
        }
    }
    
    public static function process($fineInfo, $meetingInfo, $targetVsla){
        (new FineFactory($fineInfo, $meetingInfo))->__process($targetVsla);
    }
}
