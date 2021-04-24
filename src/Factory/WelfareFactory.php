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
    protected $db;
    
    protected function __construct($db, $welfareInfo, $meetingInfo){
        $this->welfareInfo = $welfareInfo;
        $this->meetingInfo = $meetingInfo;
        $this->db = $db;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->welfareInfo)){
            $index = 0;
            for($i = 0; $i < count($this->welfareInfo); $i++){
                $welfareData = $this->welfareInfo[$i];
                $welfare = new Welfare();
                if(array_key_exists("MemberId", $welfareData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($this->db, $targetVsla->getID(), $welfareData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($this->db, $memberId))->getMember();
                        $welfare->setMember($member);
                        
                        if(array_key_exists("WelfareId", $welfareData)){
                            $welfare->setWelfareIdEx($welfareData["WelfareId"]);
                        }
                        if(array_key_exists("Amount", $welfareData)){
                            $welfare->setAmount($welfareData["Amount"]);
                        }

                        if(is_array($this->meetingInfo)){
                            if(array_key_exists("CycleId", $this->meetingInfo)){
                                $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($this->db, $targetVsla->getID(), $this->meetingInfo["CycleId"]);
                                if($vslaCycleId != null){
                                    if(array_key_exists("MeetingId", $this->meetingInfo)){
                                        $meetingId = MeetingRepo::getIDByMeetingIDEx($this->db, $vslaCycleId, $this->meetingInfo["MeetingId"]);
                                        $meeting = (new MeetingRepo($this->db, $meetingId))->getMeeting();
                                        $welfare->setMeeting($meeting);
                                    }
                                }
                            }
                        }
                        if(WelfareRepo::save($this->db, $welfare) > -1){
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
    
    public static function process($db, $welfareInfo, $meetingInfo, $targetVsla){
        return (new WelfareFactory($db, $welfareInfo, $meetingInfo))->__process($targetVsla);
    }
}
