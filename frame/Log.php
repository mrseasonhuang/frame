<?php
namespace frame;

/**
 * Class LogBase
 * @package frame
 */
abstract class LogBase{

    protected $_logPath = '';

    abstract protected function formatLog();

    public function __construct(){
        //若不重写，默认日志记录在debug里面
        $this->_logPath = LOG_PATH.'debug'.DS;
    }

    protected function saveLog($contents,$file){
        error_log($contents."\n",3,$file);
    }
}

class ExceptionLog extends  LogBase{

    private $exception;

    public function __construct($e){
        $this->_logPath = LOG_PATH.'exception'.DS;
        $this->exception = $e;
    }

    public function formatLog(){
        $content = date('Y-m-d H:i:s').' '.$this->exception."\n";
        $this->saveLog($content,$this->_logPath.'exception-'.date('Ymd').'.log');
    }
}