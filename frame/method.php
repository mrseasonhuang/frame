<?php
namespace frame;

/**
 * 常用的公共方法
 */

class method{

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

}