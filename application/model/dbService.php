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
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
            if(ENV == 'online'){
                LogFactory::load(LOG_EXCEPTION,$e)->formatLog();
            }else{
                echo $e;
            }
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
     * @param $table 表名
     * @param $content 需要查找出的字段
     * @param $where 查询条件
     * @param array $params 条件参数
     * 查找一条数据
     */
    public function selectOne($table,$content,$where='',$params=array()){
        if(ENV == 'online'){
            $connection = self::initConnection('read');
        }else{
            $connection = self::initConnection();
        }
        $sql = "select $content from $table";
        if($where) $sql .= ' where '.$where;
        $res = $this->executeQuery($connection,$sql,$params)->fetch(\PDO::FETCH_ASSOC);;
        return $res;
    }

    /**
     * @param $table 表名
     * @param $content 需要查找出的字段
     * @param $where 查询条件
     * @param array $params 条件参数
     * @return mixed
     * 查找满足条件的所有数据
     */
    public function selectAll($table,$content,$where='',$params=array()){
        if(ENV == 'online'){
            $connection = self::initConnection('read');
        }else{
            $connection = self::initConnection();
        }
        $sql = "select $content from $table";
        if($where) $sql .= ' where '.$where;
        $res = $this->executeQuery($connection,$sql,$params)->fetchAll(\PDO::FETCH_ASSOC);;
        return $res;
    }

    /**
     * @param $table 表名
     * @param $insertArr 需插入数组
     * @return bool 插入成功返回true 否则返回false
     * 根据输入数组插入数据。
     * 其中插入数组中key为表中的column,value为要插入的值
     */
    public function insertOne($table,$insertArr){
        if(ENV == 'online'){
            $connection = self::initConnection('write');
        }else{
            $connection = self::initConnection();
        }
        if(empty($insertArr) || !is_array($insertArr)){
            return false;
        }
        $column = implode(',',array_keys($insertArr));
        $count = count($insertArr);
        $tempStr = '';
        while($count > 0){
            $tempStr .= '?,';
            $count --;
        }
        $tempStr = rtrim($tempStr,',');
        $sql = "insert into $table ($column) values ($tempStr)";
        $value = array_values($insertArr);
        $res = $this->executeQuery($connection,$sql,$value);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function updateOne($table,$updateArr,$where){
        if(ENV == 'online'){
            $connection = self::initConnection('write');
        }else{
            $connection = self::initConnection();
        }
        if(empty($insertArr) || !is_array($insertArr)){
            return false;
        }
        
    }

    private function executeQuery($connection,$sql,$params=array()){
        try{
            if(substr_count($sql,'?') != count($params)){
                throw new \PDOException('参数数量不匹配');
            }
            $query = $connection->prepare($sql);
            $query->execute($params);
            return $query;
        }catch (\PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }


}
