<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of MemberFactory
 *
 * @author JCapito
 */
use App\Model\Member;
use App\Repository\MemberRepo;

class MemberFactory {
    //put your code here
    
    protected $memberInfo;
    protected $db;

    protected function __construct($db, $memberInfo){
        $this->memberInfo = $memberInfo;
        $this->db = $db;
    }
    
    protected function __process($targetVsla){
        if(is_array($this->memberInfo)){
            $index = 0;
            for($i = 0; $i < count($this->memberInfo); $i++){
                $member = new Member();
                $memberData = $this->memberInfo[$i];
                if(array_key_exists("MemberId", $memberData)){
                    $member->setMemberIdEx($memberData["MemberId"]);
                }
                if(array_key_exists("MemberNo", $memberData)){
                    $member->setMemberNumber($memberData["MemberNo"]);
                }
                if(array_key_exists("Surname", $memberData)){
                    $member->setSurname($memberData["Surname"]);
                }
                if(array_key_exists("OtherNames", $memberData)){
                    $member->setOtherNames($memberData["OtherNames"]);
                }
                if(array_key_exists("Gender", $memberData)){
                    $member->setGender($memberData["Gender"]);
                }
                if(array_key_exists("DateOfBirth", $memberData)){
                    $member->setDateOfBirth($memberData["DateOfBirth"]);
                }
                if(array_key_exists("Occupation", $memberData)){
                    $member->setOccupation($memberData["Occupation"]);
                }
                if(array_key_exists("PhoneNumber", $memberData)){
                    $member->setPhoneNumber($memberData["PhoneNumber"]);
                }
                if(array_key_exists("CyclesCompleted", $memberData)){
                    $member->setCyclesCompleted($memberData["CyclesCompleted"]);
                }
                if(array_key_exists("IsActive", $memberData)){
                    if($memberData["IsActive"]){
                        $member->setIsActive(1);
                    }else{
                        $member->setIsActive(0);
                    }
                }
                if(array_key_exists("IsArchived", $memberData)){
                    if($memberData["IsArchived"]){
                        $member->setIsArchived(1);
                    }else{
                        $member->setIsArchived(0);
                    }
                }
                $member->setVsla($targetVsla);
                if(MemberRepo::save($this->db, $member) > -1){
                    $index++;
                }
            }
            if($index == count($this->memberInfo)){
                return 1;
            }
        }
        return -1;
    }
    
    public static function process($db, $memberInfo, $targetVsla){
        return (new MemberFactory($db, $memberInfo))->__process($targetVsla);
    }
}
