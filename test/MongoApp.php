<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace tests\units;
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../MongoApp.php';

use atoum;

/**
 * Description of newPHPClass
 *
 * @author etourg
 */
class MongoApp extends atoum {
    
    public function TestGetConnection() {
           
           $controller = new \atoum\mock\controller();
           $controller->__construct = function() {};
        
           $mongoClientMock = new \mock\MongoClient($controller);       
           $mongoClientMock->getMockController()->__construct = function() {};

           $this
            // création d'une nouvelle instance de la classe à tester
            ->given($this->newTestedInstance($mongoClientMock))
            ->object($this->testedInstance->getConnection())
            ->isInstanceOf("mock\MongoClient");
                        
    }
}

