<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of MeetingRepo
 *
 * @author JCapito
 */
use PDO;
use App\Model\Meeting;
use App\Repository\VslaCycleRepo;
use App\Helpers\DatabaseHandler;

class MeetingRepo {
    //put your code here
    protected $ID;
    protected $meeting;
    protected $db;
    
    public function __construct($db, $ID = null){
        $this->ID = $ID;
        $this->meeting = new Meeting();
        $this->db = $db;
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from meeting where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            if($object != false){
                $this->meeting->setID($object["id"]);
                $this->meeting->setMeetingIdEx($object["MeetingIdEx"]);
                $this->meeting->setCashExpenses($object["CashExpenses"]);
                $this->meeting->setCashFines($object["CashFines"]);
                $this->meeting->setCashFromBox($object["CashFromBox"]);
                $this->meeting->setCashSavedBank($object["CashSavedBank"]);
                $this->meeting->setCashSavedBox($object["CashSavedBox"]);
                $this->meeting->setCashWelfare($object["CashWelfare"]);
                $this->meeting->setDateSent($object["DateSent"]);
                $this->meeting->setIsCurrent($object["IsCurrent"]);
                $this->meeting->setIsDataSent($object["IsDataSent"]);
                $this->meeting->setMeetingDate($object["MeetingDate"]);
                $this->meeting->setCountOfMembersPresent($object["CountOfMembersPresent"]);
                $this->meeting->setSumOfSavings($object["SumOfSavings"]);
                $this->meeting->setSumOfLoanIssues($object["SumOfLoanIssues"]);
                $this->meeting->setSumOfLoanRepayments($object["SumOfLoanRepayments"]);
                $this->meeting->setLoanFromBank($object["LoanFromBank"]);
                $this->meeting->setBankLoanRepayment($object["BankLoanRepayment"]);
                $this->meeting->setVslaCycle((new VslaCycleRepo($object["VslaCycle_id"]))->getVslaCycle());
            }
        }
    }
    
    public function getMeeting(){
        return $this->meeting;
    }
    
    protected function __save($meeting){
        $meetingId = $this->__getIDByMeetingIdEx($meeting->getVslaCycle()->getID(), $meeting->getMeetingIdEx());
        if($meetingId != null){
            $meeting->setID($meetingId);
            return $this->update($meeting);
        }else{
            return $this->__add($meeting);
        }
        return -1;
    }
    
    protected function __getIDByMeetingIdEx($vslaCycleId, $meetingIdEx){
        $statement = $this->db->prepare("select id from meeting where VslaCycle_id = :VslaCycle_id and MeetingIdEx = :MeetingIdEx");
        $statement->bindValue(":VslaCycle_id", $vslaCycleId, PDO::PARAM_INT);
        $statement->bindValue(":MeetingIdEx", $meetingIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    public static function getIDByMeetingIDEx($db, $vslaCycleId, $meetingIdEx){
        return (new MeetingRepo($db))->__getIDByMeetingIdEx($vslaCycleId, $meetingIdEx);
    }
    
    protected function __add($meeting){
        $statement = $this->db->prepare("insert into meeting values (0,"
                . ":MeetingIdEx,"
                . ":CashExpenses,"
                . ":CashFines,"
                . ":CashFromBox,"
                . ":CashSavedBank,"
                . ":CashSavedBox,"
                . ":CashWelfare,"
                . ":DateSent,"
                . ":IsCurrent,"
                . ":IsDataSent,"
                . ":MeetingDate,"
                . ":CountOfMembersPresent,"
                . ":SumOfSavings,"
                . ":SumOfLoanIssues,"
                . ":SumOfLoanRepayments,"
                . ":LoanFromBank,"
                . ":BankLoanRepayment,"
                . ":VslaCycle_id)");
        $statement->bindValue(":MeetingIdEx", $meeting->getMeetingIdEx(),PDO::PARAM_INT);
        $statement->bindValue(":CashExpenses", $meeting->getCashExpenses() == null ? 0 : $meeting->getCashExpenses(),PDO::PARAM_INT);
        $statement->bindValue(":CashFines", $meeting->getCashFines() == null ? 0 : $meeting->getCashFines(),PDO::PARAM_INT);
        $statement->bindValue(":CashFromBox", $meeting->getCashFromBox() == null ? 0 : $meeting->getCashFromBox(),PDO::PARAM_INT);
        $statement->bindValue(":CashSavedBank", $meeting->getCashSavedBank() == null ? 0 : $meeting->getCashSavedBank(),PDO::PARAM_INT);
        $statement->bindValue(":CashSavedBox", $meeting->getCashSavedBox() == null ? 0 : $meeting->getCashSavedBox(),PDO::PARAM_INT);
        $statement->bindValue(":CashWelfare", $meeting->getCashWelfare() == null ? 0 : $meeting->getCashWelfare(),PDO::PARAM_INT);
        $statement->bindValue(":DateSent", $meeting->getDateSent() == null ? NULL : $meeting->getDateSent(),PDO::PARAM_STR);
        $statement->bindValue(":IsCurrent", $meeting->getIsCurrent() == null ? 0 : $meeting->getIsCurrent(),PDO::PARAM_INT);
        $statement->bindValue(":IsDataSent", $meeting->getIsDataSent() == null ? 1 : $meeting->getIsDataSent(),PDO::PARAM_INT);
        $statement->bindValue(":MeetingDate", $meeting->getMeetingDate() == null ? NULL : $meeting->getMeetingDate(),PDO::PARAM_STR);
        $statement->bindValue(":CountOfMembersPresent", $meeting->getCountOfMembersPresent() == null ? 0: $meeting->getCountOfMembersPresent(),PDO::PARAM_INT);
        $statement->bindValue(":SumOfSavings", $meeting->getSumOfSavings() == null ? 0 : $meeting->getSumOfSavings(),PDO::PARAM_INT);
        $statement->bindValue(":SumOfLoanIssues", $meeting->getSumOfLoanIssues() == null ? 0 : $meeting->getSumOfLoanIssues(),PDO::PARAM_INT);
        $statement->bindValue(":SumOfLoanRepayments", $meeting->getSumOfLoanRepayments() == null ? 0 : $meeting->getSumOfLoanRepayments(),PDO::PARAM_INT);
        $statement->bindValue(":LoanFromBank", $meeting->getLoanFromBank() == null ? 0 : $meeting->getLoanFromBank(),PDO::PARAM_INT);
        $statement->bindValue(":BankLoanRepayment", $meeting->getBankLoanRepayment() == null ? 0 : $meeting->getBankLoanRepayment(),PDO::PARAM_INT);
        $statement->bindValue(":VslaCycle_id", $meeting->getVslaCycle()->getID(),PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($meeting){
        $statement = $this->db->prepare("update meeting set "
                . "MeetingIdEx = :MeetingIdEx,"
                . "CashExpenses = :CashExpenses,"
                . "CashFines = :CashFines,"
                . "CashFromBox = :CashFromBox,"
                . "CashSavedBank = :CashSavedBank,"
                . "CashSavedBox = :CashSavedBox,"
                . "CashWelfare = :CashWelfare,"
                . "DateSent = :DateSent,"
                . "IsCurrent = :IsCurrent,"
                . "IsDataSent = :IsDataSent,"
                . "MeetingDate = :MeetingDate,"
                . "CountOfMembersPresent = :CountOfMembersPresent,"
                . "SumOfSavings = :SumOfSavings,"
                . "SumOfLoanIssues = :SumOfLoanIssues,"
                . "SumOfLoanRepayments = :SumOfLoanRepayments,"
                . "LoanFromBank = :LoanFromBank,"
                . "BankLoanRepayment = :BankLoanRepayment,"
                . "VslaCycle_id = :VslaCycle_id where "
                . "id = :id");
        $statement->bindValue(":MeetingIdEx", $meeting->getMeetingIdEx(),PDO::PARAM_INT);
        $statement->bindValue(":CashExpenses", $meeting->getCashExpenses() == null ? 0 : $meeting->getCashExpenses(),PDO::PARAM_INT);
        $statement->bindValue(":CashFines", $meeting->getCashFines() == null ? 0 : $meeting->getCashFines(),PDO::PARAM_INT);
        $statement->bindValue(":CashFromBox", $meeting->getCashFromBox() == null ? 0 : $meeting->getCashFromBox(),PDO::PARAM_INT);
        $statement->bindValue(":CashSavedBank", $meeting->getCashSavedBank() == null ? 0 : $meeting->getCashSavedBank(),PDO::PARAM_INT);
        $statement->bindValue(":CashSavedBox", $meeting->getCashSavedBox() == null ? 0 : $meeting->getCashSavedBox(),PDO::PARAM_INT);
        $statement->bindValue(":CashWelfare", $meeting->getCashWelfare() == null ? 0 : $meeting->getCashWelfare(),PDO::PARAM_INT);
        $statement->bindValue(":DateSent", $meeting->getDateSent() == null ? NULL : $meeting->getDateSent(),PDO::PARAM_STR);
        $statement->bindValue(":IsCurrent", $meeting->getIsCurrent() == null ? 0 : $meeting->getIsCurrent(),PDO::PARAM_INT);
        $statement->bindValue(":IsDataSent", $meeting->getIsDataSent() == null ? 1 : $meeting->getIsDataSent(),PDO::PARAM_INT);
        $statement->bindValue(":MeetingDate", $meeting->getMeetingDate() == null ? NULL : $meeting->getMeetingDate(),PDO::PARAM_STR);
        $statement->bindValue(":CountOfMembersPresent", $meeting->getCountOfMembersPresent() == null ? 0: $meeting->getCountOfMembersPresent(),PDO::PARAM_INT);
        $statement->bindValue(":SumOfSavings", $meeting->getSumOfSavings() == null ? 0 : $meeting->getSumOfSavings(),PDO::PARAM_INT);
        $statement->bindValue(":SumOfLoanIssues", $meeting->getSumOfLoanIssues() == null ? 0 : $meeting->getSumOfLoanIssues(),PDO::PARAM_INT);
        $statement->bindValue(":SumOfLoanRepayments", $meeting->getSumOfLoanRepayments() == null ? 0 : $meeting->getSumOfLoanRepayments(),PDO::PARAM_INT);
        $statement->bindValue(":LoanFromBank", $meeting->getLoanFromBank() == null ? 0 : $meeting->getLoanFromBank(),PDO::PARAM_INT);
        $statement->bindValue(":BankLoanRepayment", $meeting->getBankLoanRepayment() == null ? 0 : $meeting->getBankLoanRepayment(),PDO::PARAM_INT);
        $statement->bindValue(":VslaCycle_id", $meeting->getVslaCycle()->getID(),PDO::PARAM_INT);
        $statement->bindValue(":id", $meeting->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }
    
    public static function save($db, $meeting){
        return (new MeetingRepo($db))->__save($meeting);
    }
}
