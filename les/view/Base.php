<?php

namespace les\view;
/**
 * 连接模板类
 * Class Base
 * @package les\view
 */
class Base
{
    //属性$file为了接收拼接的模板地址
    private $file;
    //$data为了接收传递的变量
    //给初始值为了应对不传参的情况
    private $data = [];

    /**
     * 生成模板地址方法
     * @param string $tpl 要加载的模板名
     * @return $this      返回一个Base对象 用于链式操作
     */
    public function make($tpl = '')
    {
//        p($tpl);//welcome
//        return $this;
        //路径相对于入口文件index.php
        //需要用到Boot类里的三个参数,则要定义为常量
        //判断是否为空,如果为空则为没传递参数,跳转首页
        $tpl = $tpl ?: ACTION;
        //拼接模板路径
        //在toString方法用到
        $this->file = "../app/" . MODULE . "/view/" . CONTROLLER . "/" . $tpl . ".".c("view.extension");
        //返回一个base对象 ,用于链式操作
        return $this;
    }

    /**
     * 传递变量方法
     * @param array $var  接收传递的变量
     * @return $this      返回一个Base对象,用于链式操作
     */
    public function with($var = [])
    {
//        p($var);//变量
        //用属性data接收要传递的变量
        //在toString方法用到
        $this->data = $var;
        //返回一个base对象,用于链式操作
        return $this;
    }

    /**
     * 打印base对象时此方法调用
     * @return string   返回一个base对象,用于链式操作且可以调顺序
     */
    public function __toString()
    {
//        echo "1";
        //解析传递的变量
        //用于显示在模板页面中
        extract($this->data);
        //if 判断是为了应对不调用make方法时
        //如果不调用make()方法则不进行加载模板
        if (!is_null($this->file)) {
            //加载模板文件
            include $this->file;
        }
        //不返回toString方法会报错
        return "";
    }

}