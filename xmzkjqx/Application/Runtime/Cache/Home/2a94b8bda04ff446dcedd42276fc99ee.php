<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>8.18 博世/西门子家电-为爱而行</title>
<link rel="stylesheet" href="/Public/Home/css/style.css">

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script> 
  wx.config({
    appId: '<?php echo ($signPackage["appId"]); ?>',
    timestamp: '<?php echo ($signPackage["timestamp"]); ?>',
    nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
    signature: '<?php echo ($signPackage["signature"]); ?>',
    jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','showOptionMenu'
      // 所有要调用的 API 都要加到这个列表中
    ]
  });
  wx.ready(function () {
  	wx.showOptionMenu();
    wx.onMenuShareTimeline({
    title: '8.18 博世/西门子家电-为爱而行', // 分享标题
    link: 'sn818.ktwlkj.com', // 分享链接
    imgUrl: 'http://sn818.ktwlkj.com/Public/Home/images/40705121999591555.jpg', // 分享图标
    success: function () { 
        // 用户确认分享后执行的回调函数
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
    }
  });

wx.onMenuShareAppMessage({
    title: '8.18 博世/西门子家电-为爱而行', // 分享标题
    desc: '8.18 博世/西门子家电-为爱而行', // 分享描述
    link: "sn818.ktwlkj.com", // 分享链接
    imgUrl: 'http://sn818.ktwlkj.com/Public/Home/images/40705121999591555.jpg', // 分享图标
    type: '', // 分享类型,music、video或link，不填默认为link
    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
    success: function () { 
        // 用户确认分享后执行的回调函数
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
    }
});
    });

</script>


</head>

<body>
 <div class="index index1-bg">
 <div class="content">
  <div class="logo"><img src="/Public/Home/icon/logo.png"></div>
  <div class="index1-2"><img  src="/Public/Home/icon/index1-2.png"></div>
  <div class="index1-3"><img class="index1-3" src="/Public/Home/icon/index1-3.png"></div>
 </div>
  <div class="sy-anniu">
     <a href="<?php echo U('Home/Index/hd_list');?>">砍价活动</a>
     <a href="<?php echo U('Home/Index/qixing');?>">骑行公益活动</a>
  </div> 
   
  
 </div>
</body>
</html>