<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

/**
 * Description of VslaRegionRepo
 *
 * @author JCapito
 */
use App\Model\VslaRegion;
use PDO;
use App\Helpers\DatabaseHandler;

class VslaRegionRepo {
    //put your code here
    protected $ID;
    protected $vslaRegion;
    var $db;
    
    public function __construct($ID = null){
        $this->ID = $ID;
        $this->vslaRegion = new VslaRegion();
        $this->db = DatabaseHandler::getInstance();
        $this->__load();
    }
    
    protected function __load(){
        if($this->ID != null){
            $statement = $this->db->prepare("select * from vslaregion where id = :id");
            $statement->bindValue(":id", $this->ID, PDO::PARAM_INT);
            $statement->execute();
            $object = $statement->fetch(PDO::FETCH_ASSOC);
            $this->vslaRegion->setID($object["id"]);
            $this->vslaRegion->setRegionCode($object["RegionCode"]);
            $this->vslaRegion->setRegionName($object["RegionName"]);
        }
    }
    
    public function getVslaRegion(){
        return $this->vslaRegion;
    }
}
