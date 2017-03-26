<?php
namespace frame;

/**
 * Class crl
 * @package frame
 * controller的基础类，定义一些公共处理方法
 */
class Crl{
    public function __call($name,$arguments){
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        include_once '404.html';
        exit;
    }
}