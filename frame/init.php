<?php
namespace frame;

defined('CONTROLLER_PATH') or define('CONTROLLER_PATH',APP_PATH.'controller'.DS);
defined('MODEL_PATH') or define('MODEL_PATH',APP_PATH.'model'.DS);
defined('VIEW_PATH') or define('VIEW_PATH',APP_PATH.'view'.DS);


class Init{

    /*
     * 自动加载自定义方法
     */
    public static function autoload($class){
        if(strpos($class,'Controller')){   //由于不支持 new \application\controller\$crl(); 这样的形式，所以特殊处理控制器
            $file = CONTROLLER_PATH.$class.'.php';
        }elseif(strpos($class,'Log')){
            $file = FRAME_PATH.'Log.php';
        }elseif(strpos($class,'Exception')){
            $file = FRAME_PATH.'Exception.php';
        }else{
            $file = ROOT_PATH.str_replace("\\",'/',$class).'.php';
        }

        Method::frameRequire($file);
    }

    /*
     * 初始化启动
     */
    public static function start(){
        //载入错误处理机制
        self::setErrorReporting();

        $router = self::router();
        $crl = $router['crl'];
        $action = $router['action'];
        $file = CONTROLLER_PATH.$crl.'.php';
        try{
            if(!file_exists($file)){
                throw new UnKnownUrlException('控制器不存在！');
            }
            $class = new $crl();
            if (!method_exists($class,$action)) {
                throw new UnKnownUrlException('方法不存在');
            }
            $class->$action();
        } catch(UnKnownUrlException $e){
            $e->dealException();
        }
    }

    /*
     * 定义访问的路由规则
     */
    private static function router(){
        $request = strtolower($_SERVER['REQUEST_URI']);
        $crlName = 'index';
        $actionName = 'index';

        $arrRequest = explode('?',$request);
        $arrRouter = explode('/',$arrRequest[0]);

        //过滤掉url中//这种错误输入出来产生的空值
        $arrRouter = array_diff($arrRouter,array(''));


        //用处理后的数组的元素个数作为路由判定条件
        $size = sizeof($arrRouter);
        if($size == 1){
            $crlName = $arrRouter[1];
        }elseif($size > 1){
            $crlName = $arrRouter[1];
            $actionName = $arrRouter[2];
        }

        return ['crl'=>$crlName.'Controller','action'=>$actionName.'Action'];
    }

    /**
     * 错误处理机制，对于线上环境不报错，测试环境报错
     */
    private static function setErrorReporting(){
        if(ENV == 'online'){
            error_reporting(E_ALL & ~E_NOTICE & ~E_NOTICE);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', LOG_PATH. 'phpError.log');
        }else{
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        }
    }
}