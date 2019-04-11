<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of VslaRegion
 *
 * @author JCapito
 */
class VslaRegion {
    //put your code here
    protected $id;
    protected $regionCode;
    protected $regionName;
    
    public function setID($id){
        $this->id = $id;
    }
    
    public function getID(){
        return $this->id;
    }
    
    public function setRegionCode($regionCode){
        $this->regionCode = $regionCode;
    }
    
    public function getRegionCode(){
        return $this->regionCode;
    }
    
    public function setRegionName($regionName){
        $this->regionName = $regionName;
    }
    
    public function getRegionName(){
        return $this->regionName;
    }
}
