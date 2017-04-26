<?php
/**
 *
 * redis 配置文件
 * 目前就一个，需要扩展时再扩展
 *
 */
if(ENV == 'debug'){
    $redis = [
        'host'=>'127.0.0.1',
        'port'=> 6379,
        'timeout'=>10
    ];
}elseif(ENV == 'test'){
    $redis = [
        'host'=>'127.0.0.1',
        'port'=> 6379,
        'timeout'=>10
    ];
}elseif(ENV == 'online'){
    $redis = [
        'host'=>'127.0.0.1',
        'port'=> 6379,
        'timeout'=>10
    ];
}

return $redis;