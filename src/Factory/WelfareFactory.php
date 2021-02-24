<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of WelfareFactory
 *
 * @author JCapito
 */

use App\Model\Welfare;
use App\Repository\MemberRepo;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\WelfareRepo;

class WelfareFactory {
    //put your code here
    protected $welfareInfo;
    protected $meetingInfo;
    
    protected function __construct($welfareInfo, $meetingInfo){
        $this->welfareInfo = $welfareInfo;
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->welfareInfo)){
            $index = 0;
            for($i = 0; $i < count($this->welfareInfo); $i++){
                $welfareData = $this->welfareInfo[$i];
                $welfare = new Welfare();
                if(array_key_exists("WelfareId", $welfareData)){
                    $welfare->setWelfareIdEx($welfareData["WelfareId"]);
                }
                if(array_key_exists("Amount", $welfareData)){
                    $welfare->setAmount($welfareData["Amount"]);
                }
                if(array_key_exists("MemberId", $welfareData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($targetVsla->getID(), $welfareData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($memberId))->getMember();
                        $welfare->setMember($member);
                    }
                }
                if(is_array($this->meetingInfo)){
                    if(array_key_exists("CycleId", $this->meetingInfo)){
                        $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                        if($vslaCycleId != null){
                            if(array_key_exists("MeetingId", $this->meetingInfo)){
                                $meetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $this->meetingInfo["MeetingId"]);
                                $meeting = (new MeetingRepo($meetingId))->getMeeting();
                                $welfare->setMeeting($meeting);
                            }
                        }
                    }
                }
                if(WelfareRepo::save($welfare) > -1){
                    $index++;
                }
            }
            if($index == count($this->welfareInfo)){
                return 1;
            }
        }
        return 0;
    }
    
    public static function process($welfareInfo, $meetingInfo, $targetVsla){
        return (new WelfareFactory($welfareInfo, $meetingInfo))->__process($targetVsla);
    }
}
