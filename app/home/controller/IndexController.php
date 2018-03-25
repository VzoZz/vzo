<?php

namespace app\home\controller;

use les\core\Controller;
use les\model\Model;
use les\view\View;
use system\model\Student;

class IndexController extends Controller
{
    /**
     * 测试加载模板和传递变量
     */
    public function index()
    {
//        echo "home-IndexController-index";
//        parent::index();
        //View类里面没有make()方法
        //静态调用时触发__callStatic
        //实例化调用时触发__call;
        $vzo = 1;
        $v = [1, 2, 3];
//        View::make("welcome");
//        View::with(compact('v','vzo'));

        //执行View类里的make方法和with方法
        //实际上调用了les\view\Base里面的make方法和with方法
        //welcome为要跳转的页面, with里的参数为要传递的变量
//        echo View::make("welcome")->with(compact('vzo', 'v'));
//        echo View::make("welcome");
//        return View::make('welcome')->with(compact ('vzo','v'));
        $data = [
            "name"=>"呵",
            "age"=>10
        ];
//        Student::where("id=1")->update($data);
//        Student::insert($data);
//        $res = Student::find(1);
        $res = Student::first();
        p($res);
    }

    /**
     * 测试跳转和消息显示
     * @return $this
     */
    public function add()
    {
//        echo "home-IndexController-add";
        //添加
//        $this->setRedirect();
//+
//        $this->message("添加成功");
//        $this->setRedirect    ();
//        echo $this->message("添加成功")->setRedirect();
        //调用core里的Controller类里的setRedirect()方法
        //调转到?s=home/index/index 显示消息内容为转到主页
//        echo $this->setRedirect("?s=home/index/index")->message("转到主页");
        return $this->setRedirect()->message("成功添加");
    }

    /**
     * sql语句测试方法
     */
    public function sql(){
        //要执行的SQL语句
        $sql = "select * from student";
        //执行Model里面的query方法,参数为要执行的$sql语句
        //实际上调用了Base里面的query方法
        //$res为结果集
        $res =  Model::query($sql);
        //打印结果
        p($res);
        //要执行的Sql语句
        $sql = "update student set age=20 where id=2;";
        //执行model类里的exec方法,参数为要执行的$sql语句
        //实际上调用了Base里面的exec方法
        //$res为影响的条目数
        $res = Model::exec($sql);
        //打印结果
        p($res);
    }

    public function test(){
//        p(c());
//        p(c("database"));
//        p(c("database.user"));

//        $res = Student::query("select * from student");
//        $res = Student::get();
//        $res = Student::find(1);
//        $res = Student::where("age>10")->limit(2)->column("id,name")->orderBy("age","desc")->get();
//        $res = Student::where("age>10")->limit(2)->column("id,name")->orderBy("age","desc")->update();
//        Student::where("id=1")->update("name='hehe'");
//        Student::alter("add","time","int","1","0");
//        $res = Student::alter("","time","int","1","0");
//          Student::alter("rename","stu");
//        $res = Student::get();
//        Student::rename("stu");
//        Studnet::where("id=10").del();
//        Student::where("id=5")->orderBy("age","desc")->limit(2)->del();
//        Student::delPri();
        $res = Student::addPri("i");
//        $res = Student::getNames();
        p($res);
    }
}