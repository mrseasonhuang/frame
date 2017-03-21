<?php
namespace frame;

defined('CONTROLLER_PATH') or define('CONTROLLER_PATH',APP_PATH.'controller'.DS);
defined('MODEL_PATH') or define('MODEL_PATH',APP_PATH.'model'.DS);
defined('VIEW_PATH') or define('VIEW_PATH',APP_PATH.'view'.DS);

class init{

    //private static $arrSYS = array('Action','init');
    /*
     * 自动加载自定义方法
     */
    public static function autoload($class){
        if(strpos($class,'Controller')){
            $file = CONTROLLER_PATH.$class.'.php';
        }elseif(strpos($class,'Exception')){
            $file = FRAME_PATH.'exception.php';
        }else{
            echo str_replace("\\",'/',$class).'<br>';
            $file = ROOT_PATH.str_replace("\\",'/',$class).'.php';
        }
        echo ROOT_PATH.'<br>';
        echo $file.'<br>';
        if(file_exists($file)){
            require_once $file;
        }else{
            throw new \Exception($class.' is not found!');
        }
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
            throw new UnKnownUrlException('您访问的页面不存在');
        }

        $class = new $crl();
        if (!method_exists($class,$action)) {
            throw new UnKnownUrlException('您访问的页面不存在');
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