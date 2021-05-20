<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of LoanIssueRepo
 *
 * @author JCapito
 */
use App\Model\LoanIssue;
use App\Helpers\DatabaseHandler;
use PDO;
use App\Repository\MeetingRepo;
use App\Repository\MemberRepo;

class LoanIssueRepo {
    //put your code here
    
    protected $ID;
    protected $loanIssue;
    var $db;
    
    public function __construct($db, $ID = null){
        $this->ID = $ID;
        $this->db = $db;
        $this->loanIssue = new LoanIssue();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from loanissue where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            if($object != false){
                $this->loanIssue->setID($object["id"]);
                $this->loanIssue->setLoanIdEx($object["LoanIdEx"]);
                $this->loanIssue->setLoanNumber($object["LoanNo"]);
                $this->loanIssue->setPrincipalAmount($object["PrincipalAmount"]);
                $this->loanIssue->setInterestAmount($object["InterestAmount"]);
                $this->loanIssue->setBalance($object["Balance"]);
                $this->loanIssue->setComments($object["Comments"]);
                $this->loanIssue->setDateCleared($object["DateCleared"]);
                $this->loanIssue->setDueDate($object["DueDate"]);
                $this->loanIssue->setIsCleared($object["IsCleared"]);
                $this->loanIssue->setIsDefaulted($object["IsDefaulted"]);
                $this->loanIssue->setTotalRepaid($object["TotalRepaid"]);
                $this->loanIssue->setIsWrittenOff($object["IsWrittenOff"]);
                $this->loanIssue->setMeeting((new MeetingRepo($object["Meeting_id"]))->getMeeting());
                $this->loanIssue->setMember((new MemberRepo($object["Member_id"]))->getMember());
            }
        }
    }
    
    public function getLoanIssue(){
        return $this->loanIssue;
    }
    
    protected function __save($loanIssue){
        $loanIssueId = $this->__getIDFromLoanIdEx($loanIssue->getMeeting()->getID(), $loanIssue->getLoanIdEx());
        if($loanIssueId != null){
            $loanIssue->setID($loanIssueId);
            return $this->update($loanIssue);
        }else{
            return $this->__add($loanIssue);
        }
        return -1;
    }
    
    protected function __add($loanIssue){
        $statement = $this->db->prepare("insert into loanIssue values (0,"
                . ":LoanIdEx,"
                . ":LoanNo,"
                . ":PrincipalAmount,"
                . ":InterestAmount,"
                . ":Balance,"
                . ":Comments,"
                . ":DateCleared,"
                . ":DueDate,"
                . ":IsCleared,"
                . ":IsDefaulted,"
                . ":TotalRepaid,"
                . ":IsWrittenOff,"
                . ":Meeting_id,"
                . ":Member_id)");
        $statement->bindValue(":LoanIdEx", $loanIssue->getLoanIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":LoanNo", $loanIssue->getLoanNumber() == null ? 0 : $loanIssue->getLoanNumber(), PDO::PARAM_INT);
        $statement->bindValue(":PrincipalAmount", $loanIssue->getPrincipalAmount() == null ? 0 : $loanIssue->getPrincipalAmount(), PDO::PARAM_INT);
        $statement->bindValue(":InterestAmount", $loanIssue->getInterestAmount() == null ? 0 : $loanIssue->getInterestAmount(), PDO::PARAM_INT);
        $statement->bindValue(":Balance", $loanIssue->getBalance() == null ? 0 : $loanIssue->getBalance(), PDO::PARAM_INT);
        $statement->bindValue(":Comments", $loanIssue->getComments() == null ? NULL : $loanIssue->getComments(), PDO::PARAM_STR);
        $statement->bindValue(":DateCleared", $loanIssue->getDateCleared() == null ? NULL : $loanIssue->getDateCleared(), PDO::PARAM_STR);
        $statement->bindValue(":DueDate", $loanIssue->getDueDate() == null ? NULL : $loanIssue->getDueDate(), PDO::PARAM_STR);
        $statement->bindValue(":IsCleared", $loanIssue->getIsCleared() == null ? 0 : $loanIssue->getIsCleared(), PDO::PARAM_INT);
        $statement->bindValue(":IsDefaulted", $loanIssue->getIsDefaulted() == null ? 0 : $loanIssue->getIsDefaulted(), PDO::PARAM_INT);
        $statement->bindValue(":TotalRepaid", $loanIssue->getTotalRepaid() == null ? 0 : $loanIssue->getTotalRepaid(), PDO::PARAM_INT);
        $statement->bindValue(":IsWrittenOff", $loanIssue->getIsWrittenOff() == null ? 0 : $loanIssue->getIsWrittenOff(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $loanIssue->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $loanIssue->getMember()->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($loanIssue){
        $statement = $this->db->prepare("update loanIssue set "
                . "LoanIdEx = :LoanIdEx,"
                . "LoanNo = :LoanNo,"
                . "PrincipalAmount = :PrincipalAmount,"
                . "InterestAmount = :InterestAmount,"
                . "Balance = :Balance,"
                . "Comments = :Comments,"
                . "DateCleared = :DateCleared,"
                . "DueDate = :DueDate,"
                . "IsCleared = :IsCleared,"
                . "IsDefaulted = :IsDefaulted,"
                . "TotalRepaid = :TotalRepaid,"
                . "IsWrittenOff = :IsWrittenOff,"
                . "Meeting_id = :Meeting_id,"
                . "Member_id = :Member_id where id = :id");
        $statement->bindValue(":LoanIdEx", $loanIssue->getLoanIdEx(), PDO::PARAM_INT);
        $statement->bindValue(":LoanNo", $loanIssue->getLoanNumber(), PDO::PARAM_INT);
        $statement->bindValue(":PrincipalAmount", $loanIssue->getPrincipalAmount(), PDO::PARAM_INT);
        $statement->bindValue(":InterestAmount", $loanIssue->getInterestAmount(), PDO::PARAM_INT);
        $statement->bindValue(":Balance", $loanIssue->getBalance(), PDO::PARAM_INT);
        $statement->bindValue(":Comments", $loanIssue->getComments() == null ? NULL : $loanIssue->getComments(), PDO::PARAM_STR);
        $statement->bindValue(":DateCleared", $loanIssue->getDateCleared() == null ? NULL : $loanIssue->getDateCleared(), PDO::PARAM_STR);
        $statement->bindValue(":DueDate", $loanIssue->getDueDate() == null ? NULL : $loanIssue->getDueDate(), PDO::PARAM_STR);
        $statement->bindValue(":IsCleared", $loanIssue->getIsCleared() == null ? 0 : $loanIssue->getIsCleared(), PDO::PARAM_INT);
        $statement->bindValue(":IsDefaulted", $loanIssue->getIsDefaulted() == null ? 0 : $loanIssue->getIsDefaulted(), PDO::PARAM_INT);
        $statement->bindValue(":TotalRepaid", $loanIssue->getTotalRepaid(), PDO::PARAM_INT);
        $statement->bindValue(":IsWrittenOff", $loanIssue->getIsWrittenOff() == null ? 0 : $loanIssue->getIsWrittenOff(), PDO::PARAM_INT);
        $statement->bindValue(":Meeting_id", $loanIssue->getMeeting()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":Member_id", $loanIssue->getMember()->getID(), PDO::PARAM_INT);
        $statement->bindValue(":id", $loanIssue->getID(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }
    
    protected function __getIDFromLoanIdEx($meetingId, $loanIdEx){
        $statement = $this->db->prepare("select id from loanIssue where Meeting_id = :Meeting_id and LoanIdEx = :LoanIdEx");
        $statement->bindValue(":Meeting_id", $meetingId, PDO::PARAM_INT);
        $statement->bindValue(":LoanIdEx", $loanIdEx, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    protected function __getIDFromVslaIdAndLoanIdEx($vslaId, $loanIdEx){
        $statement = $this->db->prepare("select a.id from loanissue as a inner join meeting as b on b.id = a.Meeting_id where a.LoanIdEx = :LoanIdEx and b.VslaCycle_id = :VslaCycle_id");
        $statement->bindValue(":LoanIdEx", $loanIdEx, PDO::PARAM_INT);
        $statement->bindValue(":VslaCycle_id", $vslaId, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["id"];
    }
    
    public static function getIDFromVslaIdAndLoanIdEx($db, $vslaId, $loanIdEx){
        return (new LoanIssueRepo($db))->__getIDFromVslaIdAndLoanIdEx($vslaId, $loanIdEx);
    }
    
    public static function getIDFromLoanIdEx($db, $meetingId, $loanIdEx){
        return (new LoanIssueRepo($db))->__getIDFromLoanIdEx($meetingId, $loanIdEx);
    }
    
    public static function save($db, $loanIssue){
        return (new LoanIssueRepo($db))->__save($loanIssue);
    }
    
    protected function __getLoanFundUtilization($cycleID){
        $statement = $this->db->prepare("select round((DisbursedAmount/TotalSavings), 2) LoanFundUtilization from
                                        (
                                                select SUM(a.PrincipalAmount) DisbursedAmount, v.VslaCode from loanissue a
                                                inner join meeting m on a.Meeting_id = m.id
                                                inner join vslacycle vc on m.VslaCycle_id = vc.id
                                            inner join vsla v on vc.Vsla_id = v.id
                                                where vc.id = :id
                                        ) as ab
                                        inner join
                                        (
                                                select SUM(a.Amount) TotalSavings, v.VslaCode from saving a 
                                            inner join meeting m on a.Meeting_id = m.id
                                                inner join vslacycle vc on m.VslaCycle_id = vc.id
                                            inner join vsla v on vc.Vsla_id = v.id
                                                where vc.id = id 
                                        ) as bc
                                        on ab.VslaCode = bc.VslaCode");
        $statement->bindValue(":id", $cycleID, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["LoanFundUtilization"];
    }
    
    public static function getLoanFundUtilization($db, $cycleID){
        return (new LoanIssueRepo($db))->__getLoanFundUtilization($cycleID);
    }
    
    protected function __getPercentageOfMembersWithActiveLoan($vslaID, $cycleID){
        $statement = $this->db->prepare("select round((ba.MembersTakenLoans/bb.NumberofMembers), 2) PercentageOfMembersWithActiveLoans from
                                        (
                                                select count(ab.Member_id) as MembersTakenLoans, '1' as id 
                                                from
                                                (
                                                        select distinct a.Member_id as Member_id from loanissue a
                                                        inner join meeting m on a.Meeting_id = m.id
                                                        inner join vslacycle vc on m.VslaCycle_id = vc.id
                                                        where vc.id = :cycleID and a.Balance > 0
                                                ) as ab
                                        ) as ba
                                        inner join
                                        (
                                                select count(m.id) as NumberOfMembers, '1' as id from ledgerlink.member m
                                                inner join vsla v on m.Vsla_id = v.id
                                                where v.id = :vslaID and m.MemberNo != 0
                                        ) as bb
                                        on bb.id = ba.id");
        $statement->bindValue(":cycleID", $cycleID, PDO::PARAM_INT);
        $statement->bindValue(":vslaID", $vslaID, PDO::PARAM_INT);
        $statement->execute();
        $object = $statement->fetch(PDO::FETCH_ASSOC);
        return $object == false ? null : $object["PercentageOfMembersWithActiveLoans"];
    }
    
    public static function getPercentageOfMembersWithActiveLoan($db, $vslaID, $cycleID){
        return (new LoanIssueRepo($db))->__getPercentageOfMembersWithActiveLoan($vslaID, $cycleID);
    }
}
