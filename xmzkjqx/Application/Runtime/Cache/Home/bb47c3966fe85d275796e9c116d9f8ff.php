<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>西门子-产品详情</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="index index3-bg">
 <div class="content">
   <div class="product-ny-tu"><img src="images/bingxiang-zt.jpg"></div>
   <div class="title"><span class="title-border">当前已帮砍<em>12/23</em>人</span></div>
   <div class="jindu">
     <div class="jindu-main">
      <div class="xbg">
      <span style="width: 50%"></span>
      </div>
     </div>
   </div>
   <div class="jine">
      <div class="jine-left"><span class="font1">原价</span><span class="font2">13990元</span></div>
      <div class="jine-right"><span class="font1">累计总金额</span><span class="font2">108元</span></div>
   </div>
   <div class="daka">你小手一挥，轻轻松松砍掉了<em>6.66</em>元，快点邀请朋友帮你砍吧！</div>
   <div class="zhezhao"></div>
   
   <div class="anniu">
     <a class="kanjia-btn" >找朋友帮忙</a>
     <a href="youhuijuan.html">我的优惠券</a>
   </div>
    <h1 class="product-title"><span>详情介绍</span></h1>
  <div class="text">
    <img src="images/bingxiang-zt.jpg">
    <img src="images/bingxiang-zt.jpg">
    <img src="images/bingxiang-zt.jpg">
    <img src="images/qixing-zt.jpg">
  </div>
  </div>
   <div class="sy-anniu">
     <a href="index.html">活动首页</a>
     <a href="paihangbang.html">砍价亲友团</a>
   </div> 
</div>
<script src="js/jquery.min.js"></script>

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
		        
				
					$(".zhezhao").css({"display":"block","background":"url(icon/zhezhao.png)","background-size":"100% 100%"}).click(function(){
						 $(".zhezhao").css("display","none")
						
						})
					 
				
				
			})
			
			
			

</script>
</body>
</html>