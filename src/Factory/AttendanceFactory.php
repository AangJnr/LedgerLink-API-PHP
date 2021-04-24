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
    protected $db;
    
    protected function __construct($db, $attendanceInfo, $meetingInfo){
        $this->attendanceInfo = $attendanceInfo;
        $this->meetingInfo = $meetingInfo;
        $this->db = $db;
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
                    $memberId = MemberRepo::getIDByMemberIdEx($this->db, $targetVsla->getID(), $attendanceData["MemberId"]);
                    if($memberId != null){
                        $member = (new MemberRepo($this->db, $memberId))->getMember();
                        $attendance->setMember($member);
                        if(is_array($this->meetingInfo)){
                            if(array_key_exists("CycleId", $this->meetingInfo)){
                                $vslaCycleId = VslaCycleRepo::getIDByCycleIdEx($this->db, $targetVsla->getID(), $this->meetingInfo["CycleId"]);
                                if($vslaCycleId != null){
                                    if(array_key_exists("MeetingId", $this->meetingInfo)){
                                        $meetingId = MeetingRepo::getIDByMeetingIDEx($this->db, $vslaCycleId, $this->meetingInfo["MeetingId"]);
                                        $meeting = (new MeetingRepo($this->db, $meetingId))->getMeeting();
                                        $attendance->setMeeting($meeting);
                                    }
                                }
                            }
                        }
//                        var_dump($attendance);
                        if(AttendanceRepo::save($this->db, $attendance) > -1){
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
    
    public static function process($db, $attendanceInfo, $meetingInfo, $targetVsla){
        return (new AttendanceFactory($db, $attendanceInfo, $meetingInfo))->__process($targetVsla);
    }
}
