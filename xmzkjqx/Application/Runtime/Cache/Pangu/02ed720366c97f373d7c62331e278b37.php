<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link href="/Public/Pangu/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Pangu/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/Pangu/skin/default/skin.css" rel="stylesheet" type="text/css" id="skin" />
<link href="/Public/Pangu/lib/Hui-iconfont/1.0.1/iconfont.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/Public/Pangu/lib/jquery/1.9.1/jquery.min.js"></script> 
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>我的桌面</title>
</head>
<body>
<div class="pd-20" style="padding-top:20px;">
  <p class="f-20 text-success">欢迎使用PG.admin <span class="f-14">v2.3</span>后台！</p>
  <p> </p>


  <table class="table table-border table-bordered table-bg mt-20">
    <thead>
      <tr>
        <th colspan="2" scope="col">服务器信息</th>
      </tr>
    </thead>
    <tbody>

      <tr>
        <td>系统类型</td>
        <td><?php echo ($sys["php_uname1"]); ?></td>
      </tr>
      <tr>
        <td>系统版本号</td>
        <td><?php echo ($sys["php_uname2"]); ?></td>
      </tr>
      <tr>
        <td>服务器端口 </td>
        <td><?php echo ($sys["port"]); ?></td>
      </tr>
      <tr>
        <td>php版本 </td>
        <td><?php echo ($sys["PHP_VERSION"]); ?></td>
      </tr>


      <tr>
        <td>PHP运行方式 </td>
        <td><?php echo ($sys["php_sapi_name"]); ?></td>
      </tr>
      <tr>
        <td>PHP安装路径 </td>
        <td><?php echo ($sys["DEFAULT_INCLUDE_PATH"]); ?></td>
      </tr>
      <tr>
        <td>域名或IP </td>
        <td><?php echo ($sys["host"]); ?></td>
      </tr>
      <tr>
        <td>服务器系统目录 </td>
        <td><?php echo ($sys["root"]); ?></td>
      </tr>

      <tr>
        <td>服务器当前时间 </td>
        <td><?php echo ($sys["day"]); ?>&nbsp;&nbsp;<time id="clock">上午00:00:00</time></td>
      </tr>
  

    </tbody>
  </table>
</div>








<footer class="footer">
  <p><br>Copyright &copy;2015 LX.admin v3.0 All Rights Reserved.<br>
    本后台系统由<a href="" target="_blank" title="盘古科技">盘古科技</a>提供前端技术支持</p>
</footer>
<script type="text/javascript" src="/Public/Pangu/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/Validform/5.3.2/Validform.min.js"></script>  
<script type="text/javascript" src="/Public/Pangu/lib/layer/1.9.3/layer.js"></script>
<script type="text/javascript" src="/Public/Pangu/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/Pangu/js/H-ui.admin.js"></script> 






    <script type="text/javascript">
        var sbbbb=window.setInterval(function(){
        var d=new Date();
        var t=d.toLocaleTimeString();
        $("#clock").html(t)  ;
        },1000);
    </script>








</body>
</html>