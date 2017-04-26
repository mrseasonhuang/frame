<?php
namespace frame;
/**
 * 该文件封装所有的自定义异常处理
 */

/**
 * Class FrameException
 * @package frame
 * 封装框架自身的异常处理基本类
 */
abstract class FrameException extends \Exception{

    protected  $_Log;

    /**
     * 初始化log对象
     */
    protected function initLog(){
        if(ENV == 'online'){
            $this->_Log = LogFactory::load(LOG_EXCEPTION,$this);
        }
    }

    final public function dealException(){
        if(ENV == 'online'){
            $this->initLog();
            $this->dealOnline();
        }else{
            //利用Exception 的 __toString
            echo $this."\n";
            exit;
        }
    }

    abstract protected function dealOnline();
}

/**
 * Class UnKnownUrlException
 * @package frame
 * 未知的url访问时的异常处理
 */
class UnKnownUrlException extends FrameException{

    protected function dealOnline(){
        if($this->_Log != false){
           $this->_Log->formatLog();
        }
        header('LOCATION:/index/index');
        exit;
    }

}

/**
 * Class UnKnownFileException
 * @package frame
 * 访问未知文件时的异常处理
 */
class UnKnownFileException extends FrameException{

    protected function dealOnline(){
        if($this->_Log != false){
            $this->_Log->formatLog();
        }
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        include_once '404.html';
        exit;
    }
}


class RedisException extends FrameException{

    protected function initLog(){
        if(ENV == 'online'){
            $this->_Log = LogFactory::load(LOG_STORAGE,'redis');
        }
    }

    protected function dealOnline(){
        if($this->_Log != false){
            $this->_Log->setMsg($this->getMessage());
            $this->_Log->formatLog();
        }
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        include_once '404.html';
        exit;
    }

}