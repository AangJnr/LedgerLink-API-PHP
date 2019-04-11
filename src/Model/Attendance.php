<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of Attendance
 *
 * @author JCapito
 */
class Attendance {
    //put your code here
    protected $ID;
    protected $attendanceIdEx;
    protected $comments;
    protected $isPresent;
    protected $meeting;
    protected $member;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setAttendanceIdEx($attendanceIdEx){
        $this->attendanceIdEx = $attendanceIdEx;
    }
    
    public function getAttendanceIdEx(){
        return $this->attendanceIdEx;
    }
    
    public function setComments($comments){
        $this->comments = $comments;
    }
    
    public function getComments(){
        return $this->comments;
    }
    
    public function setIsPresent($isPresent){
        $this->isPresent = $isPresent;
    }
    
    public function getIsPresent(){
        return $this->isPresent;
    }
    
    public function setMeeting($meeting){
        $this->meeting = $meeting;
    }
    
    public function getMeeting(){
        return $this->meeting;
    }
    
    public function setMember($member){
        $this->member = $member;
    }
    
    public function getMember(){
        return $this->member;
    }
}
