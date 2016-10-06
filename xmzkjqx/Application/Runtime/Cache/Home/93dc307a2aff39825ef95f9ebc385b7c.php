<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>西门子-产品详情</title>
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
<div class="index index3-bg">
 <div class="content">
   <div class="product-ny-tu"><img src="<?php echo ($info["head_img"]); ?>"></div>
   <div class="title"><span class="title-border">当前已帮砍<em>0 / <?php echo ($list['max_peo']); ?></em>人</span></div>
   <div class="jindu">
    <div class="jindu-main">
          <div class="xbg">
			<span style="width: 0%"></span>
          </div>
		</div>
   </div>
   <div class="jine">
      <div class="jine-left"><span class="font1">原价</span><span class="font2"><?php echo ($list['cost_price']); ?>元</span></div>
      <div class="jine-right"><span class="font1">累计总金额</span><span class="font2">0元</span></div>
   </div>
  
   <div class="zhezhao"></div>
   <div class="anniu">
     <a href="<?php echo U('Home/Index/hd_ny_result');?>?id=<?php echo ($id); ?>">立即砍价</a>
     <a href="youhuijuan.html">我的优惠券</a>
   </div>
    <h1 class="product-title"><span>详情介绍</span></h1>
  <div class="text">
    <?php echo ($info["detail_img"]); ?>
  </div>
  </div>
   <div class="sy-anniu">
     <a href="index.html">活动首页</a>
     <a href="paihangbang.html">砍价亲友团</a>
   </div> 
</div>
<script src="/Public/Home/js/jquery.min.js"></script>

<script  src="/Public/Home/layer/layer.js"></script>

<script>
		$(function() {
			$(".jindu-main  span").each(function() {
				$(this)
					.data("origWidth", $(this).width())
					.width(0)
					.animate({
						width: $(this).data("origWidth")
					}, 1200);
			});
		});
	</script>

</body>
</html>