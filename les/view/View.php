<?php
namespace les\view;
/**
 * 加载模板的类
 * Class View
 * @package les\view
 */
class  View{
    /**
     * 非静态调用View里没有的方法时,触发
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        //返回runParse()方法的结果
        //结果类型为les\view\base对象 用于链式操作和toString方法
        return self::runParse($name,$arguments);
    }

    /**
     * 静态调用View里没有的方法时,触发
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        //返回runParse()方法的结果
        //结果类型为les\view\base 用于链式操作和toString方法
        return self::runParse($name,$arguments);
    }

    /**
     * 创建les\view\Base对象类并调用方法
     * @param $name      要调用Base对象里的方法名
     * @param $arguments 要调用方法的参数
     * @return mixed     返回一个les\view\base对象 用于链式操作和toString方法
     */
    public static function runParse($name,$arguments){
        //new一个Base对象,并且调用里面$name对应的方法
        //对应方法的参数为$arguments
        //返回一个les\view\base对象,用于链式操作和toString方法
        return call_user_func_array([new Base(),$name],$arguments);
    }
}