<?php
namespace frame;

/**
 * 常用的公共方法
 */

class Method{

    /**
     * @param $file
     * @param bool $return true 返回require文件
     * @return mixed
     * 读取文件
     */
    public static function frameRequire($file,$return = false){
        try{
            if(file_exists($file)){
                if($return){
                    return require_once $file;
                }else{
                    require_once $file;
                }
            }else{
                throw new UnKnownFileException($file.' is not found');
            }
        }catch (UnKnownFileException $e){
            $e->dealException();
        }
    }


    /**
     * @param $path
     * @return bool
     * 检查该目录是否是可写目录,并且当目录不存在时创建目录
     */
    public static function checkPath($path){
        if(is_dir($path)){
            return is_writable($path);
        }
        else{
            return mkdir($path, 777, true);
        }
    }

    /**
     * @return string
     * 获取post请求的参数
     */
    public static function getInput(){
        if($_POST){
            $input = http_build_query($_POST);
        }else{
            $input = file_get_contents("php://input");
        }
        return $input;
    }


}