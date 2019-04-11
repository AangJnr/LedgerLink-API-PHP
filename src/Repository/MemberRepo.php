<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of MemberRepo
 *
 * @author JCapito
 */
use App\Model\Member;
use App\Repository\VslaRepo;
use App\Helpers\DatabaseHandler;
use PDO;

class MemberRepo{
    //put your code here
    protected $ID;
    protected $member;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->member = new Member();
        $this->db = DatabaseHandler::getInstance();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from member where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->member->setID($object["id"]);
            $this->member->setMemberIdEx($object["MemberIdEx"]);
            $this->member->setMemberNumber($object["MemberNo"]);
            $this->member->setCyclesCompleted($object["CyclesCompleted"]);
            $this->member->setSurname($object["Surname"]);
            $this->member->setOtherNames($object["OtherNames"]);
            $this->member->setGender($object["Gender"]);
            $this->member->setOccupation($object["Occupation"]);
            $this->member->setDateArchived($object["DateArchived"]);
            $this->member->setDateOfBirth($object["DateOfBirth"]);
            $this->member->setIsActive($object["IsActive"]);
            $this->member->setIsActive($object["IsActive"]);
            $this->member->setIsArchived($object["IsArchived"]);
            $this->member->setPhoneNumber($object["PhoneNo"]);
            $this->member->setVsla((new VslaRepo($object["Vsla_id"]))->getVsla());
        }
    }
    
    public function getMember(){
        return $this->member;
    }
    
    protected function __save($member){
        $memberId = $this->__getIDByMemberIdEx($member->getVsla()->getID(), $member->getMemberIdEx());
        if($memberId != null){
            $member->setID($memberId);
            $this->update($member);
        }else{
            $this->__add($member);
        }
    }
    
    protected function __getIDByMemberIdEx($vslaId, $memberIdEx){
        $statement = $this->db->prepare("select id from member where MemberIdEx = :MemberIdEx and Vsla_id = :Vsla_id");
        $statement->bindValue(":MemberIdEx", $memberIdEx, PDO::PARAM_INT);
        $statement->bindValue(":Vsla_id", $vslaId, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return count($object) == 1 ? $object["id"] : null;
    }
    
    public static function getIDByMemberIdEx($vslaId, $memberIdEx){
        return (new MemberRepo())->__getIDByMemberIdEx($vslaId, $memberIdEx);
    }
    
    protected function __add($member){
        $statement = $this->db->prepare("insert into member values (0, :MemberIdEx, "
                . ":MemberNo, :CyclesCompleted, :Surname, :OtherNames, :Gender, :Occupation, :DateArchived,"
                . ":DateOfBirth, :IsActive, :IsArchived, :PhoneNo, :Vsla_id)");
        $statement->bindValue(":MemberIdEx", $member->getMemberIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":MemberNo", $member->getMemberNumber(), PDO::PARAM_INT);
        $statement->bindValue(":CyclesCompleted", $member->getCyclesCompleted(), PDO::PARAM_INT);
        $statement->bindValue(":Surname", $member->getSurname(), PDO::PARAM_STR);
        $statement->bindValue(":OtherNames", $member->getOtherNames(), PDO::PARAM_STR);
        $statement->bindValue(":Gender", $member->getGender(), PDO::PARAM_STR);
        $statement->bindValue(":Occupation", $member->getOccupation(), PDO::PARAM_STR);
        $statement->bindValue(":DateArchived", $member->getDateArchived(), PDO::PARAM_STR);
        $statement->bindValue(":DateOfBirth", $member->getDateOfBirth(), PDO::PARAM_STR);
        $statement->bindValue(":IsActive", $member->getIsActive(), PDO::PARAM_INT);
        $statement->bindValue(":IsArchived", $member->getIsArchived(), PDO::PARAM_INT);
        $statement->bindValue(":PhoneNo", $member->getPhoneNumber(), PDO::PARAM_STR);
        $statement->bindValue(":Vsla_id", $member->getVsla()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($member){
        $statement = $this->db->prepare("update member set MemberIdEx = :MemberIdEx, "
                . "MemberNo = :MemberNo, "
                . "CyclesCompleted = :CyclesCompleted, "
                . "Surname = :Surname, "
                . "OtherNames = :OtherNames, "
                . "Gender = :Gender, "
                . "Occupation = :Occupation, "
                . "DateArchived = :DateArchived, "
                . "DateOfBirth = :DateOfBirth, "
                . "IsActive = :IsActive, "
                . "IsArchived = :IsArchived, "
                . "PhoneNo = :PhoneNo, "
                . "Vsla_id = :Vsla_id where id = :id");
        $statement->bindValue(":MemberIdEx", $member->getMemberIdEx());
        $statement->bindValue(":MemberNo", $member->getMemberNumber());
        $statement->bindValue(":CyclesCompleted", $member->getCyclesCompleted());
        $statement->bindValue(":Surname", $member->getSurname());
        $statement->bindValue(":OtherNames", $member->getOtherNames());
        $statement->bindValue(":Gender", $member->getGender());
        $statement->bindValue(":Occupation", $member->getOccupation());
        $statement->bindValue(":DateArchived", $member->getDateArchived());
        $statement->bindValue(":DateOfBirth", $member->getDateOfBirth());
        $statement->bindValue(":IsActive", $member->getIsActive());
        $statement->bindValue(":IsArchived", $member->getIsArchived());
        $statement->bindValue(":PhoneNo", $member->getPhoneNumber());
        $statement->bindValue(":Vsla_id", $member->getVsla()->getID());
        $statement->bindValue(":id", $member->getID());
        $statement->execute();
    }
    
    public static function save($member){
        (new MemberRepo())->__save($member);
    }
}
