<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>西门子-骑行公益</title>
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
    title: '我为818关爱孤儿活动出一份力', // 分享标题
    link: "http://sn818.ktwlkj.com/index.php/Home/Index/help_qixing.html?rid=<?php echo ($rid); ?>", // 分享链接
    imgUrl: 'http://sn818.ktwlkj.com/Public/Home/images/40705121999591555.jpg', // 分享图标
    success: function () { 
        // 用户确认分享后执行的回调函数
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
    }
  });

wx.onMenuShareAppMessage({
    title: '我为818关爱孤儿活动出一份力', // 分享标题
    desc: '我为818关爱孤儿活动出一份力', // 分享描述
    link: "http://sn818.ktwlkj.com/index.php/Home/Index/help_qixing.html?rid=<?php echo ($rid); ?>", // 分享链接
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
   <div class="product-ny-tu"><img src="/Public/Home/images/qixing-zt.jpg"></div>
   <div class="title"><span class="title-border">当前已打卡人数<em><?php echo ($num); ?>/10</em>人</span></div>
   <div class="jindu">
     <div class="jindu-main">
      <div class="xbg">
      <span style="width: <?php echo ($process); ?>%"></span>
      </div>
     </div>
   </div>
   <div class="jine">
      <div class="jine-left"><span class="font1">金额</span><span class="font2">2元</span></div>
      <div class="jine-right"><span class="font1">累计总金额</span><span class="font2"><?php echo ($allmoney); ?>元</span></div>
   </div>
   <div class="daka">您已帮他打卡成功，目前已有<em><?php echo ($count); ?></em>人参于打卡</div>
   <div class="zhezhao"></div>
   <div class="anniu">
     <a href="index.html">我也要打卡</a>
     
   </div>
   </div>
   <div class="sy-anniu">
     <a href="index.html">活动首页</a>
     <a href="juankuan.html">捐款名单</a>
   </div> 
</div>


<div class="layer_notice">
     <h1>活动说明</h1>
    <div class="shuoming">
      <h2>活动说明</h2>
      <p>活动说明活动说明活动说明活动说明活动说明活动说明活动说明</p>
      <h2>活动说明</h2>
      <p>活动说明活动说明活动说明活动说明活动说明活动说明活动说明</p>
      <h2>活动说明</h2>
      <p>活动说明活动说明活动说明活动说明活动说明活动说明活动说明</p>
      <h2>活动说明</h2>
      <p>活动说明活动说明活动说明活动说明活动说明活动说明活动说明</p>
      <h2>活动说明</h2>
      <p>活动说明活动说明活动说明活动说明活动说明活动说明活动说明</p>
      <h2>活动说明</h2>
      <p>活动说明活动说明活动说明活动说明活动说明活动说明活动说明</p>
      <h2>活动说明</h2>
      <p>活动说明活动说明活动说明活动说明活动说明活动说明活动说明</p>
    
    </div>
  </div>
<script src="/Public/Home/js/jquery.min.js"></script>

<script  src="layer/layer.js"></script>
<script>
		$(function() {
			$(".jindu-main span").each(function() {
				$(this)
					.data("origWidth", $(this).width())
					.width(0)
					.animate({
						width: $(this).data("origWidth")
					}, 1200);
			});
		});
</script>

<script type="text/javascript">
     
  	$(".kanjia-btn").click(function(){
					$(".zhezhao").css({"display":"block","background":"url(/Public/Home/icon/qixnghou.png)","background-size":"100% 100%"}).click(function(){
						 $(".zhezhao").css("display","none")
						})
			})
</script>
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