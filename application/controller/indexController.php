<?php

use frame\Crl;
use application\model\testService;
use application\model\dbService;

class indexController extends Crl{
    public function indexAction(){
        //phpinfo();exit;
        $db = dbService::getInstance();
        $insertArr = array(
            'tes1'=>'gg',
            'test2'=>'valssue2',
        );
        $res = $db->insertOne('test',$insertArr);
        $res2 = $db->selectOne('test','*',' tes1 = ?',array('gg'));
        var_dump($res2);
        var_dump($res);
    }

    public function testAction(){
        echo 1;
    }
}