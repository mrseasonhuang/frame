<?php
namespace frame;
/**
 * 框架Log处理模块，扩展其他log记录方式直接添加
 * 类名为：$logType.'Log' 并继承 LogBase
 * 如 异常处理日志类   ExceptionLog
 */


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

    protected function saveLog($contents,$path,$file){
        //确认路径可写再写，以免出现error报错
        if(Method::checkPath($path)){
            error_log($contents."\n",3,$path.$file);
        }
    }

}

/**
 * Class ExceptionLog
 * @package frame
 * 异常处理日志类
 */
class ExceptionLog extends  LogBase{

    private $exception;

    public function __construct($e){
        $this->_logPath = LOG_PATH.'exception'.DS;
        $this->exception = $e;
    }

    public function formatLog(){
        $content = date('Y-m-d H:i:s').' '.$this->exception."\n";
        $this->saveLog($content,$this->_logPath,'exception-'.date('Ymd').'.log');
    }
}

class LogFactory{
    public static function load($logType){
        $logType .= 'Log';
    }
}