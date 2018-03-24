<?php
//加载vendor里的autoload.php文件
//用于自动加载
require_once "../vendor/autoload.php";
//\app\admin\controller\IndexController::index();
//静态调用调用初始化函数
\les\core\Boot::run();