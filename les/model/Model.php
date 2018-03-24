<?php
namespace les\model;
/**
 * 执行SQL语句的类
 * Class Model
 * @package les\model
 */
class Model{

    /**
     * 非静态调用View里没有的方法时,触发
     * @param $name      被调用的方法名
     * @param $arguments 被调用的方法里的参数
     * @return mixed     返回一个Base对象
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        //执行自身的runParse()方法,
        //并把要执行的方法名和参数传递
        //返回一个Base对象
        return self::runParse($name,$arguments);
    }

    /**
     * 静态调用View里没有的方法时,触发
     * @param $name      被调用的方法名
     * @param $arguments 被调用的方法里的参数
     * @return mixed     返回一个Base对象
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        //执行自身的runParse()方法,
        //并把要执行的方法名和参数传递
        //返回一个Base对象
        return self::runParse($name,$arguments);
    }

    /**
     * 用于加载同空间下Base类
     * @param $name       被调用的方法名
     * @param $arguments  被调用的方法里的参数
     * @return mixed      返回一个Base对象
     * @throws \Exception
     */
    public static function runParse($name,$arguments){
        //获取调用类的类名,包括命名空间
        //为了传递到后面的base对象中传递模型名(表名)
        $modelClass = get_called_class();
//        p($modelClass);//system\model\Student
        //new一个Base对象,执行被调用的方法$name,被调用的方法的参数为$arguments
        //返回一个Base对象
        return call_user_func_array([new Base($modelClass),$name],$arguments);
    }
}