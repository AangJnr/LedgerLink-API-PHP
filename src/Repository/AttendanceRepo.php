<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of AttendanceRepo
 *
 * @author JCapito
 */
use App\Model\Attendance;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\MeetingRepo;
use App\Repository\MemberRepo;

class AttendanceRepo {
    //put your code here
    protected $ID;
    protected $attendance;
    var $db;
    
    public function __construct($db, $ID = null){
        $this->ID = $ID;
        $this->db = $db;
        $this->attendance = new Attendance();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            if($this->db != null){
                $statement = $this->db->prepare("select * from attendance where id = :id");
                $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
                $statement->execute();
                $object = $statement->fetch(PDO::FETCH_ASSOC);
                if($object != false){
                    $this->attendance->setID($object["id"]);
                    $this->attendance->setAttendanceIdEx($object["AttendanceIdEx"]);
                    $this->attendance->setComments($object["Comments"]);
                    $this->attendance->setIsPresent($object["IsPresent"]);
                    $this->attendance->setMeeting((new MeetingRepo($object["Meeting_id"]))->getMeeting());
                    $this->attendance->setMember((new MemberRepo($object["Member_id"]))->getMember());
                }
            }
        }
    }
    
    public function getAttendance(){
        return $this->attendance;
    }
    
    protected function __save($attendance){
        if($attendance != null){
            $attendanceId = $this->__getIDFromAttendanceIdEx($attendance->getMeeting()->getID(), $attendance->getAttendanceIdEx());
            if($attendanceId != null){
                $attendance->setID($attendanceId);
                return $this->update($attendance);
            }else{
                return $this->__add($attendance);
            }
        }
        return -1;
    }
    
    protected function __getIDFromAttendanceIdEx($meetingId, $attendanceIdEx){
        $statement = $this->db->prepare("select id from attendance where Meeting_id = :Meeting_id and AttendanceIdEx = :AttendanceIdEx");
        $statement->bindValue(":Meeting_id", $meetingId, PDO::PARAM_INT);
        $statement->bindValue(":AttendanceIdEx", $attendanceIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    protected function __add($attendance){
        $statement = $this->db->prepare("insert into attendance values (0,"
                . ":AttendanceIdEx,"
                . ":Comments,"
                . ":IsPresent,"
                . ":Meeting_id,"
                . ":Member_id)");
        $statement->bindValue(":AttendanceIdEx", $attendance->getAttendanceIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Comments", $attendance->getComments() == null ? NULL : $attendance->getComments(), PDO::PARAM_INT);
        $statement->bindValue(":IsPresent", $attendance->getIsPresent() == null ? NULL : $attendance->getIsPresent(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $attendance->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $attendance->getMember()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($attendance){
        $statement = $this->db->prepare("update attendance set "
                . "AttendanceIdEx = :AttendanceIdEx,"
                . "Comments = :Comments,"
                . "IsPresent = :IsPresent,"
                . "Meeting_id = :Meeting_id,"
                . "Member_id = :Member_id where id = :id");
        $statement->bindValue(":AttendanceIdEx", $attendance->getAttendanceIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Comments", $attendance->getComments() == null ? NULL : $attendance->getComments(), PDO::PARAM_INT);
        $statement->bindValue(":IsPresent", $attendance->getIsPresent() == null ? NULL : $attendance->getIsPresent(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $attendance->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $attendance->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $attendance->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }
    
    public static function save($db, $attendance){
        return (new AttendanceRepo($db))->__save($attendance);
    }
}
