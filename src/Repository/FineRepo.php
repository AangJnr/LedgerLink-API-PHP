<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of FineRepo
 *
 * @author JCapito
 */
use App\Model\Fine;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\MeetingRepo;
use App\Repository\MemberRepo;

class FineRepo {
    //put your code here
    protected $ID;
    protected $fine;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->db = DatabaseHandler::getInstance();
        $this->fine = new Fine();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from fine where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->fine->setID($object["id"]);
            $this->fine->setFineIdEx($object["FineIdEx"]);
            $this->fine->setAmount($object["Amount"]);
            $this->fine->setExpectedDate($object["ExpectedDate"]);
            $this->fine->setIsCleared($object["IsCleared"]);
            $this->fine->setDateCleared($object["DateCleared"]);
            $this->fine->setIssuedInMeetingIdEx($object["IssuedInMeetingIdEx"]);
            $this->fine->setIssuedInMeeting((new MeetingRepo($object["IssuedInMeeting_id"]))->getMeeting());
            $this->fine->setPaidInMeetingIdEx($object["PaidInMeetingIdEx"]);
            $this->fine->setFineTypeId($object["FineTypeId"]);
            $this->fine->setMember((new MemberRepo($object["Member_id"]))->getMember());
            $this->fine->setPaidInMeeting((new MeetingRepo($object["PaidInMeeting_id"]))->getMeeting());
        }
    }
    
    public function getFine(){
        return $this->fine;
    }
    
    protected function __save($fine){
        $fineId = $this->__getIDFromFineIdEx($fine->getIssuedInMeeting()->getID(), $fine->getFineIdEx());
        if($fineId != null){
            $fine->setID($fineId);
            $this->update($fine);
        }else{
            $this->__add($fine);
        }
    }
    
    protected function __getIDFromFineIdEx($issuedInMeetingId, $fineIdEx){
        $statement = $this->db->prepare("select id from fine where IssuedInMeeting_id = :IssuedInMeeting_id and FineIdEx = :FineIdEx");
        $statement->bindValue(":IssuedInMeeting_id", $issuedInMeetingId, PDO::PARAM_INT);
        $statement->bindValue(":FineIdEx", $fineIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return count($object) == 1 ? $object["id"] : null;
    }
    
    protected function __add($fine){
        $statement = $this->db->prepare("insert into fine values (0,"
                . ":FineIdEx,"
                . ":Amount,"
                . ":ExpectedDate,"
                . ":IsCleared,"
                . ":DateCleared,"
                . ":IssuedInMeetingIdEx,"
                . ":PaidInMeetingIdEx,"
                . ":FineTypeId,"
                . ":IssuedInMeeting_id,"
                . ":Member_id,"
                . ":PaidInMeeting_id)");
        $statement->bindValue(":FineIdEx", $fine->getFineIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $fine->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":ExpectedDate", $fine->getExpectedDate(), PDO::PARAM_STR);
        $statement->bindValue(":IsCleared", $fine->getIsCleared(), PDO::PARAM_INT);
        $statement->bindValue(":DateCleared", $fine->getDateCleared(), PDO::PARAM_STR);
        $statement->bindValue(":IssuedInMeetingIdEx", $fine->getIssuedInMeetingIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":PaidInMeetingIdEx", $fine->getPaidInMeetingIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":FineTypeId", $fine->getFineTypeId(), PDO::PARAM_INT);
        $statement->bindValue(":IssuedInMeeting_id", $fine->getIssuedInMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $fine->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":PaidInMeeting_id", $fine->getPaidInMeeting()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($fine){
        $statement = $this->db->prepare("update fine set "
                . "FineIdEx = :FineIdEx,"
                . "Amount = :Amount,"
                . "ExpectedDate = :ExpectedDate,"
                . "IsCleared = :IsCleared,"
                . "DateCleared = :DateCleared,"
                . "IssuedInMeetingIdEx = :IssuedInMeetingIdEx,"
                . "PaidInMeetingIdEx = :PaidInMeetingIdEx,"
                . "FineTypeId = :FineTypeId,"
                . "IssuedInMeeting_id = :IssuedInMeeting_id,"
                . "Member_id = :Member_id,"
                . "PaidInMeeting_id = :PaidInMeeting_id where id = :id");
        $statement->bindValue(":FineIdEx", $fine->getFineIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $fine->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":ExpectedDate", $fine->getExpectedDate(), PDO::PARAM_STR);
        $statement->bindValue(":IsCleared", $fine->getIsCleared(), PDO::PARAM_INT);
        $statement->bindValue(":DateCleared", $fine->getDateCleared(), PDO::PARAM_STR);
        $statement->bindValue(":IssuedInMeetingIdEx", $fine->getIssuedInMeetingIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":PaidInMeetingIdEx", $fine->getPaidInMeetingIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":FineTypeId", $fine->getFineTypeId(), PDO::PARAM_INT);
        $statement->bindValue(":IssuedInMeeting_id", $fine->getIssuedInMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $fine->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":PaidInMeeting_id", $fine->getPaidInMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $fine->getID(), PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function save($fine){
        (new FineRepo())->__save($fine);
    }
}
