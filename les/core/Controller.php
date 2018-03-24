<?php

namespace les\core;

class Controller
{
    private $url;
    private $msg;

    /**
     * 加载模板文件
     */
    public function message($msg)
    {

//        p($this->url);
//        include "./view/message.php";
        $this->msg = $msg;
        return $this;
    }

    public function setRedirect($url = '')
    {
        //操作成功后返回上一层
        //因为要在msssage方法中传递$url到页面,所以要设置为属性
        $this->url = $url;
//        $this->url = "javascript:history.back()";
        return $this;
    }

    /**
     * echo 对象时出发. 为了链式操作不分顺序
     * @return string 返回空字符串为了toString 不报错
     */
    public function __toString()
    {
        //如果$this->url为默认值空,则为url附加默认值
        if (!$this->url) {
            $this->url = "javascript:history.back()";
        }
        //引入模板文件
        include "./view/message.php";
        return '';
    }
}