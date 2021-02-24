<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

use App\Model\Attendance;
use App\Repository\MemberRepo;
use App\Repository\MeetingRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\AttendanceRepo;
/**
 * Description of AttendanceFactory
 *
 * @author JCapito
 */
class AttendanceFactory {
    //put your code here
    protected $attendanceInfo;
    protected $meetingInfo;
    
    protected function __construct($attendanceInfo, $meetingInfo){
        $this->attendanceInfo = $attendanceInfo;
        $this->meetingInfo = $meetingInfo;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->attendanceInfo)){
            $index = 0;
            for($i = 0; $i < count($this->attendanceInfo); $i++){
                $attendanceData = $this->attendanceInfo[$i];
                $attendance = new Attendance();
                if(array_key_exists("AttendanceId", $attendanceData)){
                    $attendance->setAttendanceIdEx($attendanceData["AttendanceId"]);
                }
                if(array_key_exists("IsPresentFlag", $attendanceData)){
                    $attendance->setIsPresent($attendanceData["IsPresentFlag"]);
                }
                if(array_key_exists("Comments", $attendanceData)){
                    $attendance->setComments($attendanceData["Comments"]);
                }
                if(array_key_exists("MemberId", $attendanceData)){
                    $memberId = MemberRepo::getIDByMemberIdEx($targetVsla->getID(), $attendanceData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($memberId))->getMember();
                        $attendance->setMember($member);
                    }else{
                        
                    }
                }
                if(is_array($this->meetingInfo)){
                    if(array_key_exists("CycleId", $this->meetingInfo)){
                        $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($targetVsla->getID(), $this->meetingInfo["CycleId"]);
                        if($vslaCycleId != null){
                            if(array_key_exists("MeetingId", $this->meetingInfo)){
                                $meetingId = MeetingRepo::getIDByMeetingIDEx($vslaCycleId, $this->meetingInfo["MeetingId"]);
                                $meeting = (new MeetingRepo($meetingId))->getMeeting();
                                $attendance->setMeeting($meeting);
                            }
                        }
                    }
                }
                
                if(AttendanceRepo::save($attendance) > -1){
                    $index++;
                }
            }
            if($index == count($this->attendanceInfo)){
                return 1;
            }
        }
        return 0;
    }
    
    public static function process($attendanceInfo, $meetingInfo, $targetVsla){
        return (new AttendanceFactory($attendanceInfo, $meetingInfo))->__process($targetVsla);
    }
}
