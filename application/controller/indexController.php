<?php

use frame\Crl;
use application\model\testService;
use application\model\dbService;

class indexController extends Crl{
    public function indexAction(){
        //phpinfo();exit;
        $db = dbService::getInstance();
        $where = 'tes1 = ?';
        $params = array('gg');
        $res = $db->selectOne('test','*',$where,$params);
        print_r($res);
    }

    public function testAction(){
        $url = 'http://local.frame.com:8080/index/index?ccc=ccc&ddd=ddd&hfdhrdhsdfsdhsd';
        $url2 = 'http://www.myframe.com/index/index';
        $ch = curl_init($url);
        $post_data = array(
            'aaa'=>'aaa',
            'bbb'=>'bbb'
        );
        $post_json = json_encode($post_data);
        $header = array(
            //'Content-Type:application/json',
            //'Content-Length:'.strlen($post_json),
            //'Accept : application/json'
        );

        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_TIMEOUT,5);
        $res = curl_exec($ch);
        curl_close($ch);
        print_r($res);
    }
}