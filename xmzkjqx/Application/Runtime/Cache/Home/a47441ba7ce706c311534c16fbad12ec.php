<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>产品详情</title>
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
    link: "http://sn818.ktwlkj.com/index.php/Home/Index/help_kan.html?uid=<?php echo ($uid); ?>&krid=<?php echo ($krid); ?>", // 分享链接
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
    link: "http://sn818.ktwlkj.com/index.php/Home/Index/help_kan.html?uid=<?php echo ($uid); ?>&krid=<?php echo ($krid); ?>", // 分享链接
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
   <div class="title"><span class="title-border">当前已帮砍<em><?php echo ($record['k_peo']); ?> / <?php echo ($list['max_peo']); ?></em>人</span></div>
   <div class="jindu">
     <div class="jindu-main">
      <div class="xbg">
      <span style="width: <?php echo ($process); ?>%"></span>
      </div>
     </div>
   </div>
   <div class="jine">
      <div class="jine-left"><span class="font1">原价</span><span class="font2"><?php echo ($list['cost_price']); ?>元</span></div>
      <div class="jine-right"><span class="font1">累计总金额</span><span class="font2"><?php echo ($record['k_price']); ?>元</span></div>
   </div>
  
   <div class="zhezhao"></div>
   <div class="anniu">

    <?php if($k_num >= $list['max_peo']): ?><p class="cut-p1"><m style="color:red"><?php echo ($k_user["nickname"]); ?></m>的帮砍人数已满</p>
    <?php else: ?>
      <?php switch($is_k): case "0": ?><p class="cut-p1">你的小伙伴<?php echo ($k_user["nickname"]); ?>邀请你帮TA砍价，快点帮TA一把吧！</p>
            <a class="btn-a2 w_help" href="javascript:;" >帮TA砍价</a><?php break;?>
          <?php case "1": ?><p class="cut-p1">你已经帮<?php echo ($k_user["nickname"]); ?>砍了<m style="color:red"><?php echo ($list9["k_price"]); ?></m>,赶紧邀请好友帮TA砍吧</p>
            <a class="btn-a2 btn-friend" href="javascript:;" >找人帮TA</a><?php break;?>
          <?php case "2": ?><p class="cut-p1"><?php echo ($k_user["nickname"]); ?>的帮砍人数已满</p><?php break;?>
          <?php case "-1": ?><p class="cut-p1"><?php echo ($k_user["nickname"]); ?>的帮砍人数已满</p><?php break; endswitch; endif; ?>
    <!-- <a class="btn-a2 w_help" href="pegnyou-kanhou.html">帮他砍价</a> -->
   </div>
  </div>
   <div class="sy-anniu">
     <a href="index.html">活动首页</a>
     <a href="<?php echo U('Home/Index/paihangbang');?>?krid=<?php echo ($krid); ?>">砍价亲友团</a>
   </div> 
</div>
<script src="/Public/Home/js/jquery.min.js"></script>

<script  src="/Public/Home/layer/layer.js"></script>
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

<script>
    $(function () {
        $(".w_help").click(function(){
            $(".w_help").off();
            location.href="<?php echo U('Home/Index/help_kanhou');?>?krid=<?php echo ($krid); ?>";return false;
        })
        var total_price=parseFloat("<?php echo ($list["cost_price"]); ?>");
        var low_price=parseFloat("<?php echo ($list["low_price"]); ?>");
        var k_price=parseFloat("<?php echo ($k_money); ?>");

        var wd=k_price/(total_price-low_price)*100;
            
        $("#w_p").css("width",wd+'%');
        $(".js-btn-rule").on("click",function () {
            $(".js-rule").show();
        });
        $(".js-close").on("click",function () {
            $(".js-rule").hide();
            return false;
        });
        $(".btn-friend").on("click",function () {
            $(".zhezhao").css({"display":"block","background":"url(/Public/Home/icon/zhezhao.png)","background-size":"100% 100%"}).click(function(){
            $(".zhezhao").css("display","none")
          })
        });
        $(".js-friends").on("click",function () {
            $(this).hide();
        });
    })
</script>

</body>
</html>