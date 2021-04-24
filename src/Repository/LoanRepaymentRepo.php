<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of LoanRepaymentRepo
 *
 * @author JCapito
 */
use App\Model\LoanRepayment;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\MeetingRepo;
use App\Repository\MemberRepo;
use App\Repository\LoanIssueRepo;

class LoanRepaymentRepo {
    //put your code here
    protected $ID;
    protected $loanRepayment;
    var $db;
    
    public function __construct($db, $ID = null){
        $this->ID = $ID;
        $this->db = $db;
        $this->loanRepayment = new LoanRepayment();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from loanrepayment where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            if($object != false){
                $this->loanRepayment->setID($object["id"]);
                $this->loanRepayment->setLoanRepaymentIdEx($object["RepaymentIdEx"]);
                $this->loanRepayment->setAmount($object["Amount"]);
                $this->loanRepayment->setBalanceAfter($object["BalanceAfter"]);
                $this->loanRepayment->setBalanceBefore($object["BalanceBefore"]);
                $this->loanRepayment->setComments($object["Comments"]);
                $this->loanRepayment->setLastDueDate($object["LastDueDate"]);
                $this->loanRepayment->setNextDueDate($object["NextDueDate"]);
                $this->loanRepayment->setInterestAmount($object["InterestAmount"]);
                $this->loanRepayment->setRolloverAmount($object["RolloverAmount"]);
                $this->loanRepayment->setLoanIssue((new LoanIssueRepo($object["IsDefaulted"]))->getLoanIssue());;
                $this->loanRepayment->setMeeting((new MeetingRepo($object["Meeting_id"]))->getMeeting());
                $this->loanRepayment->setMember((new MemberRepo($object["Member_id"]))->getMember());
            }
        }
    }
    
    public function getLoanRepayment(){
        return $this->loanRepayment;
    }
    
    protected function __save($loanRepayment){
        $loanRepaymentId = $this->__getIDFromLoanRepaymentIdEx($loanRepayment->getMeeting()->getID(), $loanRepayment->getLoanRepaymentIdEx());
        if($loanRepaymentId != null){
            $loanRepayment->setID($loanRepaymentId);
            return $this->update($loanRepayment);
        }else{
            return $this->__add($loanRepayment);
        }
        return -1;
    }
    
    protected function __add($loanRepayment){
        $statement = $this->db->prepare("insert into loanrepayment values (0,"
                . ":RepaymentIdEx,"
                . ":Amount,"
                . ":BalanceAfter,"
                . ":BalanceBefore,"
                . ":Comments,"
                . ":LastDueDate,"
                . ":NextDueDate,"
                . ":InterestAmount,"
                . ":RolloverAmount,"
                . ":LoanIssue_id,"
                . ":Meeting_id,"
                . ":Member_id)");
        $statement->bindValue(":RepaymentIdEx", $loanRepayment->getLoanRepaymentIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $loanRepayment->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":BalanceAfter", $loanRepayment->getBalanceAfter(), PDO::PARAM_INT);
        $statement->bindValue(":BalanceBefore", $loanRepayment->getBalanceBefore(), PDO::PARAM_INT);
        $statement->bindValue(":Comments", $loanRepayment->getComments() == null ? NULL : $loanRepayment->getComments(), PDO::PARAM_INT);
        $statement->bindValue(":LastDueDate", $loanRepayment->getLastDueDate() == null ? NULL : $loanRepayment->getLastDueDate(), PDO::PARAM_STR);
        $statement->bindValue(":NextDueDate", $loanRepayment->getNextDueDate() == null ? NULL : $loanRepayment->getNextDueDate(), PDO::PARAM_STR);
        $statement->bindValue(":InterestAmount", $loanRepayment->getInterestAmount(), PDO::PARAM_STR);
        $statement->bindValue(":RolloverAmount", $loanRepayment->getRolloverAmount(), PDO::PARAM_INT);
        $statement->bindValue(":LoanIssue_id", $loanRepayment->getLoanIssue()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $loanRepayment->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $loanRepayment->getMember()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($loanRepayment){
        $statement = $this->db->prepare("update loanRepayment set "
                . "RepaymentIdEx = :RepaymentIdEx,"
                . "Amount = :Amount,"
                . "BalanceAfter = :BalanceAfter,"
                . "BalanceBefore = :BalanceBefore,"
                . "Comments = :Comments,"
                . "LastDueDate = :LastDueDate,"
                . "NextDueDate = :NextDueDate,"
                . "InterestAmount = :InterestAmount,"
                . "RolloverAmount = :RolloverAmount,"
                . "LoanIssue_id = :LoanIssue_id,"
                . "Meeting_id = :Meeting_id,"
                . "Member_id = :Member_id where id = :id");
        $statement->bindValue(":RepaymentIdEx", $loanRepayment->getLoanRepaymentIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":Amount", $loanRepayment->getAmount(), PDO::PARAM_INT);
        $statement->bindValue(":BalanceAfter", $loanRepayment->getBalanceAfter(), PDO::PARAM_INT);
        $statement->bindValue(":BalanceBefore", $loanRepayment->getBalanceBefore(), PDO::PARAM_INT);
        $statement->bindValue(":Comments", $loanRepayment->getComments() == null ? NULL : $loanRepayment->getComments(), PDO::PARAM_INT);
        $statement->bindValue(":LastDueDate", $loanRepayment->getLastDueDate() == null ? NULL : $loanRepayment->getLastDueDate(), PDO::PARAM_STR);
        $statement->bindValue(":NextDueDate", $loanRepayment->getNextDueDate() == null ? NULL : $loanRepayment->getNextDueDate(), PDO::PARAM_STR);
        $statement->bindValue(":InterestAmount", $loanRepayment->getInterestAmount(), PDO::PARAM_STR);
        $statement->bindValue(":RolloverAmount", $loanRepayment->getRolloverAmount(), PDO::PARAM_INT);
        $statement->bindValue(":LoanIssue_id", $loanRepayment->getLoanIssue()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $loanRepayment->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $loanRepayment->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $loanRepayment->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }
    
    protected function __getIDFromLoanRepaymentIdEx($meetingId, $loanRepaymentIdEx){
        $statement = $this->db->prepare("select id from loanRepayment where Meeting_id = :Meeting_id and RepaymentIdEx = :RepaymentIdEx");
        $statement->bindValue(":Meeting_id", $meetingId, PDO::PARAM_INT);
        $statement->bindValue(":RepaymentIdEx", $loanRepaymentIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    public static function getIDFromLoanRepaymentIdEx($db, $meetingId, $loanRepaymentIdEx){
        return (new LoanRepaymentRepo($db))->__getIDFromLoanRepaymentIdEx($meetingId, $loanRepaymentIdEx);
    }
    
    public static function save($db, $loanRepayment){
        return (new LoanRepaymentRepo($db))->__save($loanRepayment);
    }
}
