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
    
    public function __construct($db, $ID = null){
        $this->ID = $ID;
        $this->member = new Member();
        $this->db = $db;
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from ledgerlink.member where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            if($object != false){
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
    }
    
    public function getMember(){
        return $this->member;
    }
    
    protected function __save($member){
        $memberId = $this->__getIDByMemberIdEx($member->getVsla()->getID(), $member->getMemberIdEx());
        if($memberId != null){
            $member->setID($memberId);
            return $this->update($member);
        }else{
            return $this->__add($member);
        }
        return -1;
    }
    
    protected function __getIDByMemberIdEx($vslaId, $memberIdEx){
        $statement = $this->db->prepare("select id from ledgerlink.member where MemberIdEx = :MemberIdEx and Vsla_id = :Vsla_id");
        $statement->bindValue(":MemberIdEx", $memberIdEx, PDO::PARAM_INT);
        $statement->bindValue(":Vsla_id", $vslaId, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    public static function getIDByMemberIdEx($db, $vslaId, $memberIdEx){
        return (new MemberRepo($db))->__getIDByMemberIdEx($vslaId, $memberIdEx);
    }
    
    protected function __add($member){
        $lastInsertId = 0;
        if($member != null){
            $statement = $this->db->prepare("insert into ledgerlink.member values (0, :MemberIdEx, "
                    . ":MemberNo, :CyclesCompleted, :Surname, :OtherNames, :Gender, :Occupation, :DateArchived,"
                    . ":DateOfBirth, :IsActive, :IsArchived, :PhoneNo, :Vsla_id)");
            $statement->bindValue(":MemberIdEx", $member->getMemberIdEx(), PDO::PARAM_INT);
            $statement->bindValue(":MemberNo", $member->getMemberNumber() == null ? 0 : $member->getMemberNumber(), PDO::PARAM_INT);
            $statement->bindValue(":CyclesCompleted", $member->getCyclesCompleted() == null ? 0 : $member->getCyclesCompleted(), PDO::PARAM_INT);
            $statement->bindValue(":Surname", $member->getSurname() == null ? NULL : $member->getSurname(), PDO::PARAM_STR);
            $statement->bindValue(":OtherNames", $member->getOtherNames() == null ? NULL : $member->getOtherNames(), PDO::PARAM_STR);
            $statement->bindValue(":Gender", $member->getGender() == null ? NULL : $member->getGender(), PDO::PARAM_STR);
            $statement->bindValue(":Occupation", $member->getOccupation() == null ? NULL : $member->getOccupation(), PDO::PARAM_STR);
            $statement->bindValue(":DateArchived", $member->getDateArchived() == null ? NULL : $member->getDateArchived(), PDO::PARAM_STR);
            $statement->bindValue(":DateOfBirth", $member->getDateOfBirth() == null ? NULL : $member->getDateOfBirth(), PDO::PARAM_STR);
            $statement->bindValue(":IsActive", $member->getIsActive() == null ? 1 : $member->getIsActive(), PDO::PARAM_INT);
            $statement->bindValue(":IsArchived", $member->getIsArchived() == null ? 0 : $member->getIsArchived(), PDO::PARAM_INT);
            $statement->bindValue(":PhoneNo", $member->getPhoneNumber() == null ? NULL : $member->getPhoneNumber(), PDO::PARAM_STR);
            $statement->bindValue(":Vsla_id", $member->getVsla()->getID(), PDO::PARAM_INT);
            $statement->execute();
            $lastInsertId = $this->db->lastInsertId();
        }
        return $lastInsertId;
    }
    
    public function update($member){
        $statement = $this->db->prepare("update ledgerlink.member set MemberIdEx = :MemberIdEx, "
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
        $statement->bindValue(":MemberNo", $member->getMemberNumber() == null ? 0 : $member->getMemberNumber(), PDO::PARAM_INT);
        $statement->bindValue(":CyclesCompleted", $member->getCyclesCompleted() == null ? 0 : $member->getCyclesCompleted(), PDO::PARAM_INT);
        $statement->bindValue(":Surname", $member->getSurname() == null ? NULL : $member->getSurname(), PDO::PARAM_STR);
        $statement->bindValue(":OtherNames", $member->getOtherNames() == null ? NULL : $member->getOtherNames(), PDO::PARAM_STR);
        $statement->bindValue(":Gender", $member->getGender() == null ? NULL : $member->getGender(), PDO::PARAM_STR);
        $statement->bindValue(":Occupation", $member->getOccupation() == null ? NULL : $member->getOccupation(), PDO::PARAM_STR);
        $statement->bindValue(":DateArchived", $member->getDateArchived() == null ? NULL : $member->getDateArchived(), PDO::PARAM_STR);
        $statement->bindValue(":DateOfBirth", $member->getDateOfBirth() == null ? NULL : $member->getDateOfBirth(), PDO::PARAM_STR);
        $statement->bindValue(":IsActive", $member->getIsActive() == null ? 1 : $member->getIsActive(), PDO::PARAM_INT);
        $statement->bindValue(":IsArchived", $member->getIsArchived() == null ? 0 : $member->getIsArchived(), PDO::PARAM_INT);
        $statement->bindValue(":PhoneNo", $member->getPhoneNumber() == null ? NULL : $member->getPhoneNumber(), PDO::PARAM_STR);
        $statement->bindValue(":Vsla_id", $member->getVsla()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $member->getID());
        $statement->execute();
        return $statement->rowCount();
    }
    
    public static function save($db, $member){
        return (new MemberRepo($db))->__save($member);
    }
}
