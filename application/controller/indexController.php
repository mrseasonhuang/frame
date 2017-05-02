<?php

use frame\Crl;
use application\model\testService;
use application\model\dbService;
use application\model\redisService;

class indexController extends Crl{
    public function indexAction(){
        echo \frame\Method::getIp();

    }

    public function testAction(){
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=frame','root','');
        $pdo->beginTransaction();
        $pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT,0);
        $sql1 = "select * from test2 where id = 1 for update";
        $sql = "update test2 set data = '5555dd55' where id = 1";
        $query = $pdo->prepare($sql1);
        $query2 = $pdo->prepare($sql);
        /*var_dump($pdo->exec($sql1));
        var_dump($pdo->exec($sql));*/
        $query->execute();
        $query2->execute();
        //$pdo->commit();

    }

}