<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of SavingFactory
 *
 * @author JCapito
 */
use App\Model\Saving;
use App\Repository\MemberRepo;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\SavingRepo;

class SavingFactory {
    //put your code here
    protected $savingInfo;
    protected $meetingInfo;
    
    protected function __construct($savingInfo, $meetingInfo){
        $this->savingInfo = $savingInfo;
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->savingInfo)){
            $index = 0;
            for($i = 0; $i < count($this->savingInfo); $i++){
                $savingData = $this->savingInfo[$i];
                $saving = new Saving();
                if(array_key_exists("SavingId", $savingData)){
                    $saving->setSavingIdEx($savingData["SavingId"]);
                }
                if(array_key_exists("Amount", $savingData)){
                    $saving->setAmount($savingData["Amount"]);
                }
                if(array_key_exists("MemberId", $savingData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($targetVsla->getID(), $savingData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($memberId))->getMember();
                        $saving->setMember($member);
                    }
                }
                if(is_array($this->meetingInfo)){
                    if(array_key_exists("CycleId", $this->meetingInfo)){
                        $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                        if($vslaCycleId != null){
                            if(array_key_exists("MeetingId", $this->meetingInfo)){
                                $meetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $this->meetingInfo["MeetingId"]);
                                $meeting = (new MeetingRepo($meetingId))->getMeeting();
                                $saving->setMeeting($meeting);
                            }
                        }
                    }
                }
                if(SavingRepo::save($saving) > -1){
                    $index++;
                }
            }
            if($index == count($this->savingInfo)){
                return 1;
            }
        }
        return 0;
    }
    
    public static function process($savingInfo, $meetingInfo, $targetVsla){
        return (new SavingFactory($savingInfo, $meetingInfo))->__process($targetVsla);
    }
}
