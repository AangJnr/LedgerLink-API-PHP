<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of Repo
 *
 * @author JCapito
 */
use App\Helpers\DatabaseHandler;
use PDO;

class Repo {
    //put your code here
    protected $ID;
    protected $tableFields;
    protected $tableName;
    var $db;
    
    public function __construct($ID = null, $tableName, $tableFields){
        $this->ID = $ID;
        $this->db = DatabaseHandler::getInstance();
        $this->tableName = $tableName;
        if(is_array($tableFields)){
            $this->tableFields = $tableFields;
        }
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from {$this->tableName} where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
        }
    }
}
