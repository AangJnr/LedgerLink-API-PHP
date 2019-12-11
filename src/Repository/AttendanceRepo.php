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
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->db = DatabaseHandler::getInstance();
        $this->attendance = new Attendance();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from attendance where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->attendance->setID($object["id"]);
            $this->attendance->setAttendanceIdEx($object["AttendanceIdEx"]);
            $this->attendance->setComments($object["Comments"]);
            $this->attendance->setIsPresent($object["IsPresent"]);
            $this->attendance->setMeeting((new MeetingRepo($object["Meeting_id"]))->getMeeting());
            $this->attendance->setMember((new MemberRepo($object["Member_id"]))->getMember());
        }
    }
    
    public function getAttendance(){
        return $this->attendance;
    }
    
    protected function __save($attendance){
        $attendanceId = $this->__getIDFromAttendanceIdEx($attendance->getMeeting()->getID(), $attendance->getAttendanceIdEx());
        if($attendanceId != null){
            $attendance->setID($attendanceId);
            $this->update($attendance);
        }else{
            $this->__add($attendance);
        }
    }
    
    protected function __getIDFromAttendanceIdEx($meetingId, $attendanceIdEx){
        $statement = $this->db->prepare("select id from attendance where Meeting_id = :Meeting_id and AttendanceIdEx = :AttendanceIdEx");
        $statement->bindValue(":Meeting_id", $meetingId, PDO::PARAM_INT);
        $statement->bindValue(":AttendanceIdEx", $attendanceIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return count($object) == 1 ? $object["id"] : null;
    }
    
    protected function __add($attendance){
        var_dump($attendance);
        $statement = $this->db->prepare("insert into attendance values (0,"
                . ":AttendanceIdEx,"
                . ":Comments,"
                . ":IsPresent,"
                . ":Meeting_id,"
                . ":Member_id)");
        $statement->bindValue(":AttendanceIdEx", $attendance->getAttendanceIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Comments", $attendance->getComments(), PDO::PARAM_INT);
        $statement->bindValue(":IsPresent", $attendance->getIsPresent(), PDO::PARAM_INT);
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
        $statement->bindValue(":Comments", $attendance->getComments(), PDO::PARAM_INT);
        $statement->bindValue(":IsPresent", $attendance->getIsPresent(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $attendance->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $attendance->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $attendance->getID(), PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function save($attendance){
        (new AttendanceRepo())->__save($attendance);
    }
}