<?php
namespace application\model;

use frame\method;
use frame\LogFactory;

class dbService{
    //用于保存实例化对象
    private static $_instance;

    //用于保存数据库连接
    private static $_DbLink = array();

    //用于引入配置文件
    private static $_instance_conf = array();


    /*
     * 私有化构造函数
     * 加载数据库配置
     *
     */
    private function __construct(){
        if(empty(self::$_instance_conf)){
            $dbConf = CONF_PATH.'db.php';
            self::$_instance_conf = method::frameRequire($dbConf,true);
        }
    }

    /*
     * 私有化克隆
     */
    private function __clone(){

    }

    /*
     * 用于外部使用实例的入口，如果该对象已经被实例化过了，就直接返回静态成员对象
     */
    public static function getInstance(){
        if(empty(self::$_instance)){
            self::$_instance = new dbService();
        }
        return self::$_instance;
    }

    /*
     * 初始化连接 ，放在需要调用到mysql的方法中，对外部透明
     * 也可以想作是动态加载数据库连接，只有真的需要该连接的时候才会实例化连接
     */
    private static function initConnection($type=''){
        if(ENV == 'online'){
            if(!empty(self::$_DbLink[$type])){
                return self::$_DbLink[$type];
            }
            $conf = self::$_instance_conf[$type];
            return self::$_DbLink[$type] = self::doConnection($conf);
        }else{
            if(!empty(self::$_DbLink)){
                return self::$_DbLink;
            }
            $conf = self::$_instance_conf;
            return self::$_DbLink = self::doConnection($conf);
        }
    }

    /*
     * 实施数据库连接，new PDO的时候 后面还有许多可扩展的自选项，可以配置在conf/db.php里面在此引入
     */
    private static function doConnection($conf){
        try{
            $pdo = new \PDO($conf['dsn'],$conf['user'],$conf['password']);
        }catch(\PDOException $e){
            LogFactory::load(LOG_EXCEPTION)->formatLog();
            exit;
        }
        return $pdo;
    }

    /*-------------------------------------------具体数据库操作写下面--------------------------------------------------*/

    /*
     * 简单的一个select例子，要用于实际框架中需要改写成灵活的查询方法
     * 这里为了方便写死了查询操作
     */
    public function select(){
        if(ENV == 'online'){
            $connection = self::initConnection('read');
        }else{
            $connection = self::initConnection();
        }
        $sql = 'select * from test';
        $query = $connection->prepare($sql);
        $query->execute();
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * @param $table
     * @param $content
     * @param $where
     * @param array $params
     */
    public function selectOne($table,$content,$where,$params=array()){
        if(ENV == 'online'){
            $connection = self::initConnection('read');
        }else{
            $connection = self::initConnection();
        }
        $sql = "select $content from $table";
        if($where) $sql .= ' where '.$where;
        $res = $this->executeQuery($connection,$sql,$params);
        return $res;
    }

    private function executeQuery($connection,$sql,$params=array()){
        try{
            $query = $connection->prepare($sql);
            $queryRes = $query->execute($params);
            if($queryRes === false){
                $msg = 'SQL:'.$sql.' PARAMS:'.json_encode($params).' was wrong!';
                throw new \PDOException($msg);
            }
        }catch (\PDOException $e){
            echo $e->getMessage();
        }
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }


}
