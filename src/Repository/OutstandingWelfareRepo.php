<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of OutstandingWelfareRepo
 *
 * @author JCapito
 */
use App\Model\OutstandingWelfare;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\MeetingRepo;
use App\Repository\MemberRepo;

class OutstandingWelfareRepo {
    //put your code here
    protected $ID;
    protected $outstandingWelfare;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->db = DatabaseHandler::getInstance();
        $this->outstandingWelfare= new OutstandingWelfare();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from outstandingwelfare where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->outstandingwelfare->setID($object["id"]);
            $this->outstandingwelfare->setOutstandingWelfareIdEx($object["OutstandingWelfareIdEx"]);
            $this->outstandingwelfare->setAmount($object["Amount"]);
            $this->outstandingwelfare->setExpectedDate($object["ExpectedDate"]);
            $this->outstandingwelfare->setIsCleared($object["IsCleared"]);
            $this->outstandingwelfare->setDateCleared($object["DateCleared"]);
            $this->outstandingwelfare->setComment($object["Comment"]);
            $this->outstandingwelfare->setMeeting((new MeetingRepo($object["Meeting_id"]))->getMeeting());
            $this->outstandingwelfare->setMember((new MemberRepo($object["Member_id"]))->getMember());
            $this->outstandingwelfare->setPaidInMeeting((new MeetingRepo($object["PaidInMeeting_id"]))->getMeeting());
        }
    }
    
    public function getOutstandingWelfare(){
        return $this->outstandingwelfare;
    }
    
    protected function __save($outstandingwelfare){
        $outstandingwelfareId = $this->__getIDFromOutstandingWelfareIdEx($outstandingwelfare->getMeeting()->getID(), $outstandingwelfare->getOutstandingWelfareIdEx());
        if($outstandingwelfareId != null){
            $outstandingwelfare->setID($outstandingwelfareId);
            $this->update($outstandingwelfare);
        }else{
            $this->__add($outstandingwelfare);
        }
    }
    
    protected function __getIDFromOutstandingWelfareIdEx($MeetingId, $outstandingwelfareIdEx){
        $statement = $this->db->prepare("select id from outstandingwelfare where Meeting_id = :Meeting_id and OutstandingWelfareIdEx = :OutstandingWelfareIdEx");
        $statement->bindValue(":Meeting_id", $MeetingId, PDO::PARAM_INT);
        $statement->bindValue(":OutstandingWelfareIdEx", $outstandingwelfareIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return count($object) == 1 ? $object["id"] : null;
    }
    
    protected function __add($outstandingwelfare){
        $statement = $this->db->prepare("insert into outstandingwelfare values (0,"
                . ":OutstandingWelfareIdEx,"
                . ":Amount,"
                . ":ExpectedDate,"
                . ":IsCleared,"
                . ":DateCleared,"
                . ":Comment,"
                . ":Meeting_id,"
                . ":Member_id,"
                . ":PaidInMeeting_id)");
        $statement->bindValue(":OutstandingWelfareIdEx", $outstandingwelfare->getOutstandingWelfareIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $outstandingwelfare->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":ExpectedDate", $outstandingwelfare->getExpectedDate(), PDO::PARAM_STR);
        $statement->bindValue(":IsCleared", $outstandingwelfare->getIsCleared(), PDO::PARAM_INT);
        $statement->bindValue(":DateCleared", $outstandingwelfare->getDateCleared(), PDO::PARAM_STR);
        $statement->bindValue(":Comment", $outstandingwelfare->getComment(), PDO::PARAM_STR);
        $statement->bindValue(":Meeting_id", $outstandingwelfare->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $outstandingwelfare->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":PaidInMeeting_id", $outstandingwelfare->getPaidInMeeting()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($outstandingwelfare){
        $statement = $this->db->prepare("update outstandingwelfare set "
                . "OutstandingWelfareIdEx = :OutstandingWelfareIdEx,"
                . "Amount = :Amount,"
                . "ExpectedDate = :ExpectedDate,"
                . "IsCleared = :IsCleared,"
                . "DateCleared = :DateCleared,"
                . "Comment = :Comment,"
                . "Meeting_id = :Meeting_id,"
                . "Member_id = :Member_id,"
                . "PaidInMeeting_id = :PaidInMeeting_id where id = :id");
        $statement->bindValue(":OutstandingWelfareIdEx", $outstandingwelfare->getOutstandingWelfareIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $outstandingwelfare->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":ExpectedDate", $outstandingwelfare->getExpectedDate(), PDO::PARAM_STR);
        $statement->bindValue(":IsCleared", $outstandingwelfare->getIsCleared(), PDO::PARAM_INT);
        $statement->bindValue(":DateCleared", $outstandingwelfare->getDateCleared(), PDO::PARAM_STR);
        $statement->bindValue(":Comment", $outstandingwelfare->getComment(), PDO::PARAM_STR);
        $statement->bindValue(":Meeting_id", $outstandingwelfare->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $outstandingwelfare->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":PaidInMeeting_id", $outstandingwelfare->getPaidInMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $outstandingwelfare->getID(), PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function save($outstandingwelfare){
        (new OutstandingWelfareRepo())->__save($outstandingwelfare);
    }
}
