<?php
namespace frame;

class UnKnownUrlException extends \Exception{
    public function jump(){
        header('LOCATION:/index/index');
    }
}