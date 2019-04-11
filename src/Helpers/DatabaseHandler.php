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
    public static function getInstance(){ 
        return new PDO("mysql:host=localhost;dbname=ledgerlink;charset=utf8", "grameen", "gram33n1234$");
    }
}