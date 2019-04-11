<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of SavingRepo
 *
 * @author JCapito
 */
use App\Model\Saving;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\MeetingRepo;
use App\Repository\MemberRepo;

class SavingRepo {
    //put your code here
    protected $ID;
    protected $saving;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->db = DatabaseHandler::getInstance();
        $this->saving = new Saving();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from saving where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->saving->setID($object["id"]);
            $this->saving->setSavingIdEx($object["SavingIdEx"]);
            $this->saving->setAmount($object["Amount"]);
            $this->saving->setMeeting((new MeetingRepo($object["Meeting_id"]))->getMeeting());
            $this->saving->setMember((new MemberRepo($object["Member_id"]))->getMember());
        }
    }
    
    public function getSaving(){
        return $this->saving;
    }
    
    protected function __save($saving){
        $savingId = $this->__getIDFromSavingIdEx($saving->getMeeting()->getID(), $saving->getSavingIdEx());
        if($savingId != null){
            $saving->setID($savingId);
            $this->update($saving);
        }else{
            $this->__add($saving);
        }
    }
    
    protected function __getIDFromSavingIdEx($meetingId, $savingIdEx){
        $statement = $this->db->prepare("select id from saving where Meeting_id = :Meeting_id and SavingIdEx = :SavingIdEx");
        $statement->bindValue(":Meeting_id", $meetingId, PDO::PARAM_INT);
        $statement->bindValue(":SavingIdEx", $savingIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return count($object) == 1 ? $object["id"] : null;
    }
    
    protected function __add($saving){
        $statement = $this->db->prepare("insert into saving values (0,"
                . ":SavingIdEx,"
                . ":Amount,"
                . ":Meeting_id,"
                . ":Member_id)");
        $statement->bindValue(":SavingIdEx", $saving->getSavingIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $saving->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $saving->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $saving->getMember()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($saving){
        $statement = $this->db->prepare("update saving set "
                . "SavingIdEx = :SavingIdEx,"
                . "Amount = :Amount,"
                . "Meeting_id = :Meeting_id,"
                . "Member_id = :Member_id where id = :id");
        $statement->bindValue(":SavingIdEx", $saving->getSavingIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $saving->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $saving->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $saving->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $saving->getID(), PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function save($saving){
        (new SavingRepo())->__save($saving);
    }
}
