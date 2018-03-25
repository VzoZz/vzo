<?php

namespace les\model;

use PDO;
use Exception;
use function Sodium\compare;

/**
 * 连接数据库的方法类
 * Class Base
 * @package les\model
 */
class Base
{
    private $table;//表名
    private $where;//where条件
    private $orderBy;//orderBy条件
    private $limit;//limit条件
    private $column = "*";//显示的列
//    private $operation = [['add', 'modify'], ['rename', 'drop']];//alter操作数组
    private $names = [];//表的所有字段名
    //定义静态成员属性$pdo 用于定义PDO对象
    //定义为静态为了保留上次的值,和只能被初始化一次
    //初始值为null为了用于第一次使用时判断是否初始化PDO对象
    private static $pdo = null;

    /**
     * 构造方法,用于初始化PDO对象和设定头部和错误模式
     * Base constructor.
     * @throws Exception
     */
    public function __construct($table)
    {
//        $this->table=$table;
//        p($this->table);//system\model\Student
        $this->table = strtolower(ltrim(strrchr($table, "\\"), "\\"));
//        p($this->table);
        //初始化和连接数据库
        self::connect();
    }

    /**
     * 截取
     * @param $limit 截取的数目
     * @return $this
     */
    public function limit($limit = "")
    {
        $this->limit = $limit ? " limit " . $limit : "";
//        p($this->limit);
        return $this;
    }

    /**
     * 排序
     * @param $by       排序的依据
     * @param $type     升序或降序
     * @return $this
     */
    public function orderBy($by = "", $type = 'asc')
    {
//        if($by){
//            $this->orderBy = " order by " . $by . " " . $type;
//        }else{
//            $this->orderBy = "";
//        }
        $this->orderBy = $by ? " order by " . $by . " " . $type : "";
//        p($this->orderBy);
        return $this;
    }

    /**
     * where条件
     * @param $where 传递的where语句
     * @return $this
     */
    public function where($where)
    {
//        echo $where;die;
        $this->where = " where " . $where;
        return $this;
    }

    /**
     * 通过主键查找数据
     */
    public function find($key)
    {
//        p($key);die;
        $priKey = $this->getPriKey();
        $sql = "select * from " . $this->table . " where " . $priKey . "=" . $key;
        return $this->query($sql);
    }

    /**
     * 获取主键对应的字段名
     * @return mixed
     * @throws Exception
     */
    public function getPriKey()
    {
        $res = $this->query('desc ' . $this->table);
//        p($res);die;
        foreach ($res as $v) {
            if ($v["Key"] == "PRI") {
                return $v["Field"];
            }
        }
    }

    /**
     * 删除主键
     * @return bool|null
     * @throws Exception
     */
    public function delPri()
    {
        //先获取主键的键名
        $pri = $this->getPriKey();
//        echo $pri;die;
        //判断键名是否存在,如果存在则删除主键
        if ($pri) {
//            $sql = "alter table ".$this->table." modify " . $pri . " int";
            $sql1 = "alter table ".$this->table." drop primary key";
//            $this->exec($sql);
            return $this->exec($sql1);
        } else {
            return null;
        }
    }

    /**
     * 添加主键
     * @param string $pri  要设置的主键名
     * @return int|null
     * @throws Exception
     */
    public function addPri($pri='')
    {
        //判断要设置主键的字段表中是否存在
        //如果存在则$pan为true 可以设置为主键,如果不存在则为null.不能设置为主键
        $pan = in_array($pri,$this->getNames()) ? :null;
//        p($pan); die;
        //判断表是否已经有主键,如果有则返回null
        //并且$pan为true时,设置主键
        if (is_null($this->getPriKey()) && $pan) {
            $sql1 = "alter table ".$this->table." add primary key(" . $pri . ")";
//            $sql = "alter table ".$this->table." modify " . $pri . " int auto_increment";
//            echo $sql1 . "---" . $sql;die;
            return $this->exec($sql1);
//            return $this->exec($sql);
        } else {
//            echo 1;
            return null;
        }
    }

    /**
     * 获取所有的字段名
     * @return array  返回所有的字段名
     * @throws Exception
     */
    public function getNames(){
        $res = $this->query('desc ' . $this->table);
//         p($res);die;
        foreach ($res as $v){
            $this->names[] = $v["Field"];
        }
//        p($this->names);die;
        return $this->names;
    }

    /**
     * 选择显示的列
     * @param $column   显示的列
     * @return $this
     */
    public function column($column="*")
    {
//        $this->column = implode(",",$column);
        $this->column = $column ?: "*";
//        p($this->column);die;
        return $this;
    }

    /**
     *获取数据
     * @return array      返回获取的数据
     * @throws Exception
     */
    public function get()
    {
        $sql = "select " . $this->column . " from " . $this->table . $this->where . $this->orderBy . $this->limit;
//        echo $sql;die;
        return $this->query($sql);
    }

    /**
     * 删除数据
     */
    public function del()
    {
//        $sql = "delete from student where age>20 order by id>2 limit 1";
        $sql = "delete from " . $this->table . $this->where . $this->orderBy . $this->limit;
//        echo $sql;die;
        return $this->exec($sql);
    }

    /**
     * 修改数据
     * @param $update   修改后的数据
     * @return int      返回受影响的条数
     * @throws Exception
     */
    public function update($update)
    {
//        $sql = "update student set name='呵' where id=1";
        $sql = "update " . $this->table . " set " . $update . $this->where;
//        echo $sql;die;
        return $this->exec($sql);
    }

    /**
     * 执行有结果集的sql语句
     * @param $sql      要执行的sql语句
     * @return array    返回结果集数组
     * @throws Exception 抛出异常
     */
    public function query($sql)
    {
        /**
         * try catch 代码块用于抛出异常和接受异常
         * Exception 为异常的基类 $e为异常对象
         */
        try {
            //执行PDO对象的query方法 并执行$sql对应的SQL语句
            //并不是自调用
            $res = self::$pdo->query($sql);
            //取出结果集, 数据为关联数组
            //返回结果集 用于在调用时打印
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            //抛出异常 包括php错误
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 执行没有结果集的sql语句
     * @param $sql      要执行的SQL语句
     * @return int      返回被影响的数据条数
     * @throws Exception    抛出异常
     */
    public function exec($sql)
    {
        /**
         * try catch 代码块用于抛出异常和接受异常
         * Exception 为异常的基类 $e为异常对象
         */
        try {
            //执行PDO对象里的exec方法,并执行$sql对应的SQL语句
            //并不是自调用
            return self::$pdo->exec($sql);
        } catch (Exception $e) {
            //抛出异常 包括PHP错误
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 连接初始化
     * @throws Exception
     */
    public static function connect()
    {
        //如果没有初始化,则进行初始化
        if (is_null(self::$pdo)) {
            /**
             * try catch 代码块用于抛出异常和接受异常
             * Exception 为异常的基类 $e为异常对象
             */
            try {
                //设定数据库连接信息
                $dsn = "mysql:host=" . c('database.host') . ";dbname=" . c('database.name');
                //定义数据库PDO对象
                //用静态成员$pdo接收对象
                self::$pdo = new PDO($dsn, c('database.user'), c('database.pwd'));
                //设置字符集,和客户端保持一致
                self::$pdo->query("set names utf8");
                //设置错误模式
                //PDO::ERRMODE_EXCEPTION 抛出异常
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                //抛出异常 包括PHP错误
                throw new Exception($e->getMessage());
            }
        }
    }

//    //未完成 修改字段
//    public function alter($operate, $keyWord, $type = "int", $notnull = "1", $default = 0)
//    {
//
////        if(in_array($operate,$this->operation)){
////            if($operate=="rename"){
////                $sql = "alter table ".$this->table." rename ".$keyWord;
////            }
////        }else{
////            return null;
////        }
//
//        switch ($operate) {
//            case in_array($operate, $this->operation[1]):
//                $sql = "alter table " . $this->table . " " . $operate . " " . $keyWord;
//                echo $sql;
//                die;
//                return $this->query($sql);
//            case "add":
//                $sql = "alter table " . $this->table . " add " . $keyWord;
//        }
//    }
//
//    /**
//     * 未完成 重命名
//     * @param string $newname
//     * @return int|null
//     * @throws Exception
//     */
//    public function rename($newname = '')
//    {
//        $sql = " alter table " . $this->table . " rename " . $newname;
////        echo $sql;die;
//        return $newname ? $this->exec($sql) : null;
//    }
//
//    //未完成 添加auto_increment
//    public function auto($bool = true){
//        if($bool){
//
//        }
//    }
}