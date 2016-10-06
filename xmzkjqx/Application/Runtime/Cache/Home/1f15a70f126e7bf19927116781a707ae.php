<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>818骑行公益活动</title>
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
    title: '我为818关爱孤儿活动出一份力', // 分享标题
    desc: '我为818关爱孤儿活动出一份力', // 分享描述
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
   <div class="product-ny-tu"><img src="/Public/Home/images/qixing-zt.jpg"></div>
   <div class="title"><span class="title-border">当前已打卡人数<em>0/10</em>人</span></div>
   <div class="jindu">
     <div class="jindu-main">
      <div class="xbg">
      <span style="width: 0%"></span>
      </div>
     </div>
   </div>
   <div class="jine">
      <div class="jine-left"><span class="font1">金额</span><span class="font2">2元</span></div>
      <div class="jine-right"><span class="font1">累计总金额</span><span class="font2"><?php echo ($allmoney); ?>元</span></div>
   </div>
  
   <div class="anniu">
     <a href="qixinghou.html">点击打卡</a>
     <!-- <a class="sure_btn">活动详情</a> -->
   </div>
   <style>
      .rule_ride{margin:10px 15px;border:1px dashed #87ba14;padding:15px;border-radius: 6px;}
      .rule_ride h3{text-align: center;font-size: 18px;font-weight: bold;color: #87ba14;line-height: 26px;}
      .rule_ride p{line-height: 24px;font-size:14px;color:#444;margin-top:5px;}
   </style>
  <div class="rule_ride">
    <h3>活动详情</h3>
    <p>1、此次活动日期为8.7-8.18日。<br>2、活动规则：一人可以个人名义邀请十个好友助力打卡，邀请满十人即可为本次818骑聚苏宁，为爱前行公益活动增加2元的新鲜水果资助金额，每人有且只能参加一次，本次最高累计金额为4800元。<br>3、本次公益活动由博西家用电器（中国）有限公司晋蒙办事处出资。<br>4、本次活动捐赠对象为太原（社会）儿童福利院的孩子们，捐赠时间暂定8.21日，请持续关注公众号“西门子家电晋蒙”和“博世家电晋蒙”<br>5、本活动最终解释权归博西家用电器（中国）有限公司晋蒙办事处所有。</p>
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
      <p>活动时间：8月18日</p>
      <p>活动主题：博世/西门子家电 8.18齐聚苏宁 为爱前行</p>
      <p>1.点击我要打卡即可打卡成功，每人需邀请9个好友为自己帮忙，即可捐赠2元。</p>
      <p>2.同一用户只能发起一次打卡</p>
      <p>3.活动中，凡以不正当手段（包括但不限于作弊、恶意套现、虚假交易、扰乱系统、实施网络攻击等）参与活动的用户，我们有权终止其参加活动，并取消资格。活动详情请关注和咨询微信公众号“西门子家电晋蒙”，联系客服。</p>
      <p>4.本活动主办方对本活动拥有最终解释权。</p>
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