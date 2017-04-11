<?php
/**
 * 数据哭的基本配置信息写在这里，根据ENV参数分为三个不同的配置
 */

if(ENV=='debug'){
    $db = [
        'dsn'=>'mysql:host=127.0.0.1;dbname=frame',
        'user'=>'root',
        'password'=>''
    ];
}elseif(ENV=='test'){
    $db = [
        'dsn'=>'mysql:host=127.0.0.1;dbname=mydb',
        'user'=>'root',
        'password'=>''
    ];
}elseif(ENV=='online'){  //数据库优化，线上往往采用读写分离的方式，如果线上只有一个库，那就配置成一样的参数
    $db = [
        'write'=>[
            'dsn'=>'mysql:host=127.0.0.1;dbname=frame',
            'user'=>'root',
            'password'=>''
        ],
        'read'=>[
            'dsn'=>'mysql:host=127.0.0.1;dbname=frame',
            'user'=>'root',
            'password'=>''
        ],
    ];
}

return $db;