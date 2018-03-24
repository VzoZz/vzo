<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1><?php echo $this->msg;?></h1>
<a href="<?php echo $this->url;?>"><span>2</span>秒后返回上一页</a>
<script>
    var second = document.getElementsByTagName("span")[0];
    var sec = second.innerHTML;
    setInterval(function () {
        sec--;
        second.innerHTML=sec;
        if(sec==0){
            location.href="<?php echo $this->url;?>"
        }
    },1000)
</script>
</body>
</html>