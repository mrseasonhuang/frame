<?php

use frame\Crl;
use application\model\testService;

class test1Controller extends Crl{
    public function indexAction(){
        echo 'test1.index';
    }

    public function test2Action(){
        echo 'test1.test2';
    }

}

