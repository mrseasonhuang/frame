<?php
/**
 * 简单的学习一下php7的新特性
 */
use frame\Crl;
use application\model\learnPHP7Service;

class learnphp7Controller extends Crl{
    public function declareAction(){
        $service = new learnPHP7Service();
        $a =  20.5;
        $b = 3;
        var_dump($service->declareParamAndReturn($a,$b));
    }
}