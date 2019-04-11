<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of FinancialInstitutionRepo
 *
 * @author JCapito
 */

use App\Helpers\DatabaseHandler;
use App\Model\FinancialInstitution;
use PDO;

class FinancialInstitutionRepo {
    //put your code here
    protected $ID;
    var $db;
    protected $financialInstitution;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->financialInstitution = new FinancialInstitution();
        $this->db = DatabaseHandler::getInstance();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from financialinstitution where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->financialInstitution->setID($object["id"]);
            $this->financialInstitution->setFinancialInstitutionIdEx($object["FinancialInstitutionIdEx"]);
            $this->financialInstitution->setName($object["Name"]);
            $this->financialInstitution->setCode($object["Code"]);
        }
    }
    
    public function getFinancialInstitution(){
        return $this->financialInstitution;
    }
    
}
