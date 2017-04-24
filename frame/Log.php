<?php
namespace frame;
/**
 * 框架Log处理模块，扩展其他log记录方式直接添加
 * 类名为：$logType.'Log' 并继承 LogBase
 * 如 异常处理日志类   ExceptionLog
 */

//此处定义工厂实例化类型，便于使用，增加可读性 用LOG_开头
defined('LOG_EXCEPTION') or define('LOG_EXCEPTION','Exception');
defined('LOG_REQUEST') or define('LOG_REQUEST','Request');
defined('LOG_STORAGE') or define('LOG_STORAGE','Storage');

/**
 * Class LogBase
 * @package frame
 * 抽象，规范，约定产品，实现公共方法
 */
abstract class LogBase{

    protected $_logPath = '';

    /**
     * @return mixed
     * 约定不同类型的log必须实现的log形式函数
     */
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
 * Class LogFactory
 * @package frame
 * log 工厂类
 */
class LogFactory{
    /**
     * @param $logType
     * @param $params
     * @return bool
     * 动态加载的工厂方法
     */
    public static function load($logType,$params=''){
        $logType .= 'Log';
        //在命名空间下class_exists中的类名必须用全称
        $className = "\\frame\\{$logType}";
        //由于只针对本文件下的类，所有设置 bool $autoload = false 关闭自动调用autoload
        if(!class_exists($className,false)){
            return false;
        }
        return new $className($params);
    }
}


/*-------------------------------------------实际LOG类写下面--------------------------------------------------*/

/**
 * Class ExceptionLog
 * @package frame
 * 异常处理日志类
 */
class ExceptionLog extends  LogBase{

    private $exception;

    public function __construct($e){
        $this->_logPath = LOG_PATH.'exception'.DS;
        if(!is_object($e)){
            return false;
        }
        $this->exception = $e;
    }

    public function formatLog(){
        $content = date('Y-m-d H:i:s').' '.$this->exception."\n";
        $this->saveLog($content,$this->_logPath,'exception-'.date('Ymd').'.log');
    }
}

/**
 * Class RequestLog
 * @package frame
 * 外部请求的log，包括访问url和参数记录
 */
class RequestLog extends LogBase{


    public function __construct(){
        $this->_logPath = LOG_PATH.'request'.DS;
    }

    public function formatLog(){
        $requestType = $_SERVER['REQUEST_METHOD'];
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        $requestUrl = $_SERVER['REQUEST_URI'];
        $time = date('Y-m-d H:i:s');
        $requestData = '';
        if($requestType == 'POST'){
            $requestData = Method::getInput();
        }
        $content = "$time $requestType $requestUrl $requestData $contentType\n";
        $this->saveLog($content,$this->_logPath,'request-'.date('Ymd').'.log');
    }


}

/**
 * Class StorageLog
 * @package frame
 * redis或者DB的错误日志
 */
class StorageLog extends LogBase{

    private static $statement; //语句
    private static $msg;  //错误提示语
    private static $params; //参数

    private $type;  //类型 db 和 redis  默认db

    public function __construct($type){
        $this->_logPath = LOG_PATH.'storage'.DS;
        $this->type = isset($type)&&!empty($type) ? $type : 'db';
    }

    public function formatLog(){
        $time = date('Y-m-d H:i:s');
        if(empty(self::$msg) || empty(self::$statement)){
            $content = "$time 异常信息缺失\n";
        }else{
            $statement = self::$statement;
            $msg = self::$msg;
            $params = self::$params;
            $content = "$time $statement $params $msg";
        }
        $this->saveLog($content,$this->_logPath,$this->type.'-'.date('Ymd').'.log');
        //初始化状态
        self::$statement = '';
        self::$msg ='';
        self::$params ='';
    }

    /**
     * @param $sql
     * @param string $params
     * 记录出错sql和参数
     */
    public  function setSql($sql,$params=''){
        self::$statement = $sql;
        if(!empty($params)){
            self::$params = $params;
        }
    }

    /**
     * @param $msg
     * 记录错误提示语
     */
    public  function setMsg($msg){
        self::$msg = $msg;
    }
}

class CommonLog extends LogBase{

    public function formatLog(){

    }
}
