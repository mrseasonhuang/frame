<?php

use frame\Crl;
use application\model\testService;
use application\model\dbService;

class indexController extends Crl{
    public function indexAction(){
        //phpinfo();exit;
        $db = dbService::getInstance();
        $table = 'testd';
        $updateArr = array(
            'tesss1'=>'bbbcc',
            'test2'=>'cccfffffcc'
        );
        $where = "tes1 = 'bbb'";
        $res = $db->updateOne($table,$updateArr,$where);
        var_dump($res);
    }

    public function testAction(){
        echo 1;
    }
}