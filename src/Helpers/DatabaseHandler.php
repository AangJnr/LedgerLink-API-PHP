<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DatabaseHandler
 *
 * @author JCapito
 */
namespace App\Helpers;

use PDO;

class DatabaseHandler {
    //put your code here
    protected $pdo;
    
    protected function __construct(){
        $this->pdo = null;
    }
    
    protected function __getInstance(){
        try{
            $this->pdo = new PDO("mysql:host=localhost;port=3308;dbname=ledgerlink;charset=utf8", "grameen", "gram33n1234");
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
        return $this->pdo;
    }
    
    public static function getInstance(){ 
        return (new DatabaseHandler())->__getInstance();
    }
}