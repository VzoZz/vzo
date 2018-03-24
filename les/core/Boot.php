<?php

namespace les\core;

use app\admin\controller\IndexController;

/**
 * 框架启动类
 */
class Boot
{
    /**
     * 调用Boot的静态方法
     * 用于初始化框架
     */
    public static function run()
    {
        self::error();
//        echo "run";
        self::init();
        self::appRun();
//        (new IndexController())->index();
    }

    /**
     * 初始化项目
     * 设置头部,时区,开启session
     */
    private static function init()
    {
//        echo "init";die;
        //头部
        header(c("header.header"));
        //设置时区
        date_default_timezone_set(c("header.timezone"));
        //短路写法 开启session
        //判断session_id(),如果有session_id()，说明已开启session，没有session_id，再开启session
        session_id() || session_start();
    }

    /**
     * 运行app
     */
    private static function appRun()
    {
//        echo "addRun";
        //  地址栏GET传参方式
        // 旧: ?m=admin&c=index&a=index(m:模块,c:控制器,a:方法)
        // 新: ?s=admin/index/index 一个参数
        //判断参数是否存在，并且是否可以拆分成有三个元素的数组
        //将拆分出的数组元素分别取出，用于实例化调用时拼接
        if (isset($_GET["s"])&&count(explode("/", $_GET["s"]))==3) {
//            $s = $_GET["s"];
//            echo $s; die;
            //拆分GET参数s
            //分别获取到$m ,$c,$a 的值
            //$m:模块,$c:控制器,$a:方法
            $info = explode("/", $_GET["s"]);
            $m = $info[0];
            $c = $info[1];
            $a = $info[2];
        } else {
            //如果没有参数传递，或者参数传递有误
            //则对$m,$c,$a 赋予初始值，跳转到首页
            $m = "home";
            $c = "index";
            $a = "index";
        }
        //定义常量,为了在后面加载模板使用0
        define('MODULE',$m);
        define('CONTROLLER',$c);
        define('ACTION',$a);
        //拼接命名空间和类
        //用于实例化调用
        $controller = "\app\\" . $m . "\controller\\" . ucfirst(strtolower($c)) . "controller";
//        (new $controller())->$a();
        //实例化类$controller，$a 为要执行的方法，[]为传递的参数
        echo call_user_func_array([new $controller, $a], []);

    }

    public static function error(){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}