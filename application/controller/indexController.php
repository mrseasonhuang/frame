<?php

use System\Action\Action;

class indexController extends Action{
    public function indexAction(){
        echo 'index.index';
    }

    public function testAction(){
        echo 'index.test';
    }
}