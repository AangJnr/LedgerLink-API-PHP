<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factory;

/**
 * Description of VslaCycleFactory
 *
 * @author JCapito
 */
use App\Model\VslaCycle;
use App\Repository\VslaCycleRepo;

class VslaCycleFactory {
    //put your code here
    protected $vslaCycleInfo;
    protected $vslaCycle;
    
    protected function __construct($vslaCycleInfo){
        $this->vslaCycleInfo = $vslaCycleInfo;
        $this->vslaCycle = new VslaCycle();
    }
    
    protected function __process($targetVsla){
        if(is_array($this->vslaCycleInfo)){
            if(array_key_exists("CycleId", $this->vslaCycleInfo)){
                $this->vslaCycle->setCycleIdEx($this->vslaCycleInfo["CycleId"]);
            }
            if(array_key_exists("StartDate", $this->vslaCycleInfo)){
                $this->vslaCycle->setStartDate($this->vslaCycleInfo["StartDate"]);
            }
            if(array_key_exists("EndDate", $this->vslaCycleInfo)){
                $this->vslaCycle->setEndDate($this->vslaCycleInfo["EndDate"]);
            }
            if(array_key_exists("SharePrice", $this->vslaCycleInfo)){
                $this->vslaCycle->setSharePrice($this->vslaCycleInfo["SharePrice"]);
            }
            if(array_key_exists("MaxShareQty", $this->vslaCycleInfo)){
                $this->vslaCycle->setMaxShareQuantity($this->vslaCycleInfo["MaxShareQty"]);
            }
            if(array_key_exists("MaxStartShare", $this->vslaCycleInfo)){
                $this->vslaCycle->setMaxStartShare($this->vslaCycleInfo["MaxStartShare"]);
            }
            if(array_key_exists("InterestRate", $this->vslaCycleInfo)){
                $this->vslaCycle->setInterestRate($this->vslaCycleInfo["InterestRate"]);
            }
            $this->vslaCycle->setVsla($targetVsla);
            //ToDo migrate interest is not getting captured
            return VslaCycleRepo::save($this->vslaCycle);
        }
        return -1;
    }
    
    public static function process($vslaCycleInfo, $targetVsla){
        return (new VslaCycleFactory($vslaCycleInfo))->__process($targetVsla);
    }
}
