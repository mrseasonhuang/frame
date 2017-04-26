<?php
namespace application\model;

use frame\Method;
use frame\RedisException;

class redisService{
    //用于保存实例化对象
    private static $_instance;

    private static $_redis_con;


    private function __construct(){
        $redisConf = CONF_PATH.'redis.php';
        $_instance_conf = Method::frameRequire($redisConf,true);

        try{
            if(!extension_loaded('Redis')){
                throw new RedisException('redis扩展未安装！');
            }
            self::$_redis_con = new \Redis();
            if(!self::$_redis_con->connect($_instance_conf['host'],$_instance_conf['port'],$_instance_conf['timeout'])){
                //如果链接不成功，置空，以便于下次初始化的时候重新连接
                self::$_instance = '';
                self::$_redis_con = '';
                throw new RedisException('redis连接失败！');
            }
        }catch (RedisException $e){
            $e->dealException();
        }


    }

    private function __clone(){}

    public static function getInstance(){
        if(empty(self::$_instance) || empty(self::$_redis_con)){
            self::$_instance = new redisService();
        }
        return self::$_redis_con;
    }


}