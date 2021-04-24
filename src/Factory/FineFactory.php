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
    protected $db;
    
    protected function __construct($db, $fineInfo, $meetingInfo){
        $this->fineInfo = $fineInfo;
        $this->meetingInfo = $meetingInfo;
        $this->db = $db;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->fineInfo)){
            $index = 0;
            for($i = 0; $i < count($this->fineInfo); $i++){
                $fineData = $this->fineInfo[$i];
                $fine = new Fine();
                if(array_key_exists("FineId", $fineData)){
                    $fine->setFineIdEx($fineData["FineId"]);
                }
                if(array_key_exists("MemberId", $fineData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($this->db, $targetVsla->getID(), $fineData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($this->db, $memberId))->getMember();
                        $fine->setMember($member);
                        
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
                                $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($this->db, $targetVsla->getID(), $this->meetingInfo["CycleId"]);
                                if($vslaCycleId != null){
                                    $paidInMeetingId = MeetingRepo::getIDByMeetingIDEx($this->db, $vslaCycleId, $fineData["PaidInMeetingId"]);
                                    if($paidInMeetingId != null){
                                        $paidInMeeting = (new MeetingRepo($this->db, $paidInMeetingId))->getMeeting();
                                        $fine->setPaidInMeeting($paidInMeeting);
                                    }
                                }
                            }
                        }
                        if(array_key_exists("MeetingId", $fineData)){
                            $fine->setIssuedInMeetingIdEx($fineData["MeetingId"]);
                            if(array_key_exists("CycleId", $this->meetingInfo)){
                                $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($this->db, $targetVsla->getID(), $this->meetingInfo["CycleId"]);
                                if($vslaCycleId != null){
                                    $issuedInMeetingId = MeetingRepo::getIDByMeetingIDEx($this->db, $vslaCycleId, $fineData["MeetingId"]);
                                    if($issuedInMeetingId != null){
                                        $issuedInMeeting = (new MeetingRepo($this->db, $issuedInMeetingId))->getMeeting();
                                        $fine->setIssuedInMeeting($issuedInMeeting);
                                    }
                                }
                            }
                        }
                        if(FineRepo::save($this->db, $fine) > -1){
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
    
    public static function process($db, $fineInfo, $meetingInfo, $targetVsla){
        return (new FineFactory($db, $fineInfo, $meetingInfo))->__process($targetVsla);
    }
}
