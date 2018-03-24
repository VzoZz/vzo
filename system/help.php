<?php
/**
 * @author vzo(vzozz@outlook.com);
 */
function p($var)
{
    echo "<body style='margin: 0px;padding: 0px;'>";
    echo '<pre style="width: 100%; padding: 10px; background: #292D35 ;color:#4EB5C0 ;font-family: Source Code Pro Medium;font-size: 20px ;border-radius: 5px" >';
    if (is_bool($var) || is_null($var)) {
        var_dump($var);
    } else {
        print_r($var);
    }
    echo '</pre>';
    echo "</body>";
}

define("IS_POST", $_SERVER["REQUEST_METHOD"] == "POST" ? true : false);
define('IS_AJAX', (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false);


/**
 * 读取的配置项函数
 * @param null $var 要读取的配置项 默认为null
 * @return array|mixed|null
 */
function c($var = null)
{
    //没有传递参数的情况
    if (is_null($var)) {
        $files = glob("../system/config/*");
//        p($files);
//        [0] => ../system/config/database.php
//        [1] => ../system/config/view.php
        $data = [];
        foreach ($files as $filename) {
            //加载文件内容
//            $content = include $filename;
//            p($content);
//            $filename=basename($filename);
            //获取.php位置
//            $position = strpos($filename,".php");
//            p($position);
//            $index = substr($filename,0,$position);
            $index = substr(basename($filename), 0, strpos(basename($filename), ".php"));
//            p($index);
            //将对应文件名设定为索引,需要将目录中的文件名取出
//            $data[$index] = $content;
            $data[$index] = include $filename;
        }
        //返回所有配置文件的文件内容
        return $data;
    }
    //传递参数的情况
    $info = explode(".", $var);
    //传递了文件名的情况
    if (count($info) == 1) {
        //获取配置项文件名
        $file = "../system/config/" . $info[0] . ".php";
//        p($file);
        //判断此文件是否存在,如果存在则加载文件内容返回,如果不存在则返回null
        return is_file($file) ? include $file : null;
    }
    //传递了文件名和对应配置项
    if (count($info) == 2) {
        //获取配置项文件名
        $file = "../system/config/" . $info[0] . ".php";
        //判断此文件名是否存在于config里
        if (is_file($file)) {
//            echo "111";
            //加载文件内容
            $data = include $file;
            //判断对应索引的配置项是否存在,如果存在则返回,不存在则返回null
            return isset($data[$info[1]]) ? $data[$info[1]] : null;
        }else{
            //如果$file 不是文件,则返回null
            return null;
        }
    }
}


