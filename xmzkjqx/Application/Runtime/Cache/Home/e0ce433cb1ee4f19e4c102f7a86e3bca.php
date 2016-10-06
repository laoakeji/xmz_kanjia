<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>产品列表</title>
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
 <div class="index index2-bg">
  <div class="content">
  <ul class="list">
    <!-- <li><a href="<?php echo U('Home/Index/hd_ny_result');?>?id=1"><img src="/Public/Home/images/bingxiang.jpg"></a></li>
    <li><a href="<?php echo U('Home/Index/hd_ny_result');?>?id=2"><img src="/Public/Home/images/xiyiji.jpg"></a></li>
    <li><a href="<?php echo U('Home/Index/hd_ny_result');?>?id=3"><img src="/Public/Home/images/xiwanji.jpg"></a></li>
    <li><a href="<?php echo U('Home/Index/hd_ny_result');?>?id=4"><img src="/Public/Home/images/diankaoxiang.jpg"></a></li> -->
    <li><a href="<?php echo U('Home/Index/hd_ny');?>?id=1"><img src="/Public/Home/images/bingxiang.jpg"></a></li>
    <li><a href="<?php echo U('Home/Index/hd_ny');?>?id=2"><img src="/Public/Home/images/xiyiji.jpg"></a></li>
    <li><a href="<?php echo U('Home/Index/hd_ny');?>?id=3"><img src="/Public/Home/images/xiwanji.jpg"></a></li>
    <li><a href="<?php echo U('Home/Index/hd_ny');?>?id=4"><img src="/Public/Home/images/diankaoxiang.jpg"></a></li>
  </ul>
  
  </div>

  <div class="sy-anniu">
     <a href="index.html">活动首页</a>
     <a class="sure_btn">活动详情</a>
  </div> 
   
  
 </div>
 <style>
  .shuoming p{margin-top:3px;}
 </style>
 <div class="layer_notice">
      <h1>活动说明</h1>
      <div class="shuoming">
        <p>活动时间：8月18日</p>
        <p>活动主题：博世/西门子家电 8.18齐聚苏宁 为爱前行</p>
        <p>1.点击产品列表相应的产品，即可参与砍价，砍价金额随机。砍完价后，可找朋友帮忙继续砍。每个产品最多可有88个人砍价。</p>
        <p>2.砍多少减多少。</p>
        <p>3.同一用户只能发起一次砍价，所有参与砍价活动的产品库存都是有限的，先到先的</p>
        <p>4.活动中，凡以不正当手段（包括但不限于作弊、恶意套现、虚假交易、扰乱系统、实施网络攻击等）参与活动的用户，我们有权终止其参加活动，并取消资格。活动详情请关注和咨询微信公众号“西门子家电晋蒙”，联系客服。</p>
        <p>5.本活动主办方对本活动拥有最终解释权。</p>
      </div>
  </div>
 <script src="/Public/Home/js/jquery.min.js"></script>
 <script  src="/Public/Home/layer/layer.js"></script>
<script type="text/javascript">
     
  	$(".sure_btn").click(function(){
				layer.open({
                type: 1,
               shade: false,
              title: false, //不显示标题
              content: $('.layer_notice'), //捕获的元素
              
               });
			})
</script>
</body>
</html>