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

    /**
     * @param $pattern
     * @return bool
     * 判断ip基本格式是否正确
     */
    public static function isIP($pattern){
        $subject = '/^(([0-9]|([1-9][0-9])|(1[0-9][0-9])|(2[0-4][0-9])|(25[0-5]))\.){3}([0-9]|([1-9][0-9])|(1[0-9][0-9])|(2[0-4][0-9])|(25[0-5]))$/';
        if(preg_match($subject,$pattern) == 1){
            return true;
        }else{
            return false;
        }
    }


    /**
     * @param $ip
     * @return mixed
     * 用来判断该ipv4 ip地址是否是一个外网合法ip
     * A类IP范围：0.0.0.0 - 127.255.255.255
     * B类IP范围：128.0.0.0 - 191.255.255.255
     * C类范围：192.0.0.0 - 223.255.255.255
     */
    public static function checkIP($ip){
        $ip = trim($ip);
        if(!self::isIP($ip)){
            return '非法ip';
        }
        if($ip == '127.0.0.1'){
            return '本机回环IP';
        }
        $ipArr = explode('.',$ip);
        foreach($ipArr as $key=>$value){
            $ipArr[$key] = intval($value);
        }
        //判断是否是个内网地址
        //内网地址分段：A类：10.0.0.0-10.255.255.255  B类：172.16.0.0-172.31.255.255 C类：192.168.0.0-192.168.255.255
        if($ipArr[0]==10 || ($ipArr[0]==172 && $ipArr[1]>15 && $ipArr[1]<32) || ($ipArr[0]==192 && $ipArr[1]==168)){
            return '内网IP';
        }
        return true;

    }

    /**
     * @return string
     * 获取ip地址
     */
    public static function getIp(){
        $ip='未知IP';
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $checkRes = self::checkIP($_SERVER['HTTP_CLIENT_IP']);
            if($checkRes === true){
                return $_SERVER['HTTP_CLIENT_IP'];
            }
            $ip = $checkRes.':'.$_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $checkRes = self::checkIP($_SERVER['HTTP_X_FORWARDED_FOR']);
            if($checkRes === true){
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            $ip = $checkRes.':'.$_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(!empty($_SERVER['REMOTE_ADDR'])){
            $checkRes = self::checkIP($_SERVER['REMOTE_ADDR']);
            if($checkRes === true){
                return $_SERVER['REMOTE_ADDR'];
            }
            $ip = $checkRes.':'.$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


}