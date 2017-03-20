<?php
namespace System\Action;

class Action{
    public function __call($name,$arguments){
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        include_once '404.html';
        exit;
    }
}