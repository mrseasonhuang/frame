<?php
namespace System\Init;

defined('CONTROLLER_PATH') or define('CONTROLLER_PATH',APP_PATH.'controller'.DS);
defined('MODEL_PATH') or define('MODEL_PATH',APP_PATH.'model'.DS);
defined('VIEW_PATH') or define('VIEW_PATH',APP_PATH.'view'.DS);

class init{

    private static $arrSYS = array('Action','init');
    /*
     * 自动加载自定义方法
     */
    public static function autoload($class){
        if(strpos($class,'Controller')){
            $file = CONTROLLER_PATH.$class.'.php';
        }elseif(in_array($class,self::$arrSYS)){
            $file = SYS_PATH.$class.'.php';
        }else{
            $file = CONTROLLER_PATH.'indexController.php';
        }
        require_once $file;

    }

    /*
     * 初始化启动
     */
    public static function start(){
        $router = self::router();
        $crl = $router['crl'];
        $action = $router['action'];
        $file = CONTROLLER_PATH.$crl.'.php';
        if(!file_exists($file)){
            $crl = 'indexController';
        }
        /*require_once SYS_PATH.'Action.php';*/
        $class = new $crl();
        if (!method_exists($class,$action)) {
            $action = 'indexAction';
        }
        $class->$action();
    }

    /*
     * 定义访问的路由规则
     */
    private static function router(){
        $request = $_SERVER['REQUEST_URI'];
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
}