<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of WelfareRepo
 *
 * @author JCapito
 */

use App\Model\Welfare;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\MeetingRepo;
use App\Repository\MemberRepo;


class WelfareRepo {
    //put your code here
    protected $ID;
    protected $welfare;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->db = DatabaseHandler::getInstance();
        $this->welfare = new Welfare();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from welfare where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            if($object != false){
                $this->welfare->setID($object["id"]);
                $this->welfare->setWelfareIdEx($object["WelfareIdEx"]);
                $this->welfare->setAmount($object["Amount"]);
                $this->welfare->setMeeting((new MeetingRepo($object["Meeting_id"]))->getMeeting());
                $this->welfare->setMember((new MemberRepo($object["Member_id"]))->getMember());
            }
        }
    }
    
    public function getWelfare(){
        return $this->welfare;
    }
    
    protected function __save($welfare){
        $welfareId = $this->__getIDFromWelfareIdEx($welfare->getMeeting()->getID(), $welfare->getWelfareIdEx());
        if($welfareId != null){
            $welfare->setID($welfareId);
            return $this->update($welfare);
        }else{
            return $this->__add($welfare);
        }
        return -1;
    }
    
    protected function __getIDFromWelfareIdEx($meetingId, $welfareIdEx){
        $statement = $this->db->prepare("select id from welfare where Meeting_id = :Meeting_id and WelfareIdEx = :WelfareIdEx");
        $statement->bindValue(":Meeting_id", $meetingId, PDO::PARAM_INT);
        $statement->bindValue(":WelfareIdEx", $welfareIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    protected function __add($welfare){
        $statement = $this->db->prepare("insert into welfare values (0,"
                . ":WelfareIdEx,"
                . ":Amount,"
                . ":Comment,"
                . ":Meeting_id,"
                . ":Member_id)");
        $statement->bindValue(":WelfareIdEx", $welfare->getWelfareIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $welfare->getAmount() == null ? 0 : $welfare->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":Comment", $welfare->getComment() == null ? NULL : $welfare->getComment(), PDO::PARAM_STR);
        $statement->bindValue(":Meeting_id", $welfare->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $welfare->getMember()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($welfare){
        $statement = $this->db->prepare("update welfare set "
                . "WelfareIdEx = :WelfareIdEx,"
                . "Amount = :Amount,"
                . "Comment = :Comment,"
                . "Meeting_id = :Meeting_id,"
                . "Member_id = :Member_id where id = :id");
        $statement->bindValue(":WelfareIdEx", $welfare->getWelfareIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $welfare->getAmount() == null ? 0 : $welfare->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":Comment", $welfare->getComment() == null ? NULL : $welfare->getComment(), PDO::PARAM_STR);
        $statement->bindValue(":Meeting_id", $welfare->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $welfare->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $welfare->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }
    
    public static function save($welfare){
        return (new WelfareRepo())->__save($welfare);
    }
}
