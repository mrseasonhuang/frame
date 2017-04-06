<?php

use frame\Crl;
use application\model\testService;
use application\model\dbService;

class indexController extends Crl{
    public function indexAction(){
        //phpinfo();exit;
        $db = dbService::getInstance();
        $rowArr = array('tes1','test2');
        $valueArr = array(
            array('asgasga','ashshashas'),
            array('asgasha','sdhsdhsj')
        );
        $res = $db->insert('test',$valueArr,$rowArr);
        print_r($res);
    }

    public function testAction(){
        echo 1;
    }
}