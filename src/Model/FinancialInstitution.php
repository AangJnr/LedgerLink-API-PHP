<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of FinancialInstitution
 *
 * @author JCapito
 */
class FinancialInstitution {
    //put your code here
    protected $ID;
    protected $financialInstitutionIdEx;
    protected $name;
    protected $code;
    
    public function setID($ID){
        $this->ID = $ID;
    }
    
    public function getID(){
        return $this->ID;
    }
    
    public function setFinancialInstitutionIdEx($financialInstitutionIdEx){
        $this->financialInstitutionIdEx = $financialInstitutionIdEx;
    }
    
    public function getFinancialInstitutionIdEx(){
        return $this->financialInstitutionIdEx;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setCode($code){
        $this->code = $code;
    }
    
    public function getCode(){
        return $this->code;
    }
}
