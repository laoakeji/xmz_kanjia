<?php if (!defined('THINK_PATH')) exit();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1，user-scalable=0;">
<title>西门子-优惠券</title>
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
 
  <div class="youhui-zt"><img src="/Public/Home/icon/youhui.jpg"></div>

    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["is_prize"] == 1): ?><div ids="<?php echo ($vo["id"]); ?>" class="youhuijuan sure-btn">
      <?php else: ?>
        <div ids="<?php echo ($vo["id"]); ?>" style="background-image:url(/Public/Home/icon/hou.jpg)" class="youhuijuan"><?php endif; ?>
   
        <div  class="youhuijuan-main">
          <div class="youhuijuan-left">
            <span><?php echo ($vo["goods_no"]); ?></span>
            <span><?php echo ($vo["goods_name"]); ?></span>
            <span>原价:<?php echo ($vo["price"]); ?></span>
            <span class="yuan">已优惠：<em><?php echo ($vo["k_price"]); ?></em></span>
          </div>
          <span class="youhuijuan-right">点击<br>领取</span>
        </div>
      </div>
      <div style="height:20px;"></div><?php endforeach; endif; else: echo "" ;endif; ?>

</div>
<script src="/Public/Home/js/jquery.min.js"></script>
<script  src="/Public/Home/layer/layer.js"></script>
<script type="text/javascript">
     
  	$(".sure-btn").click(function(){
        var ids = $(this).attr("ids");
        var th = $(this);
        if(ids == 0){return;}
				layer.confirm('你确定要领取优惠券吗，请在工作人员引导下进行操作！', {
				   btn: ['确定','取消'] //按钮
				}, function(){
					$.post("<?php echo U('Home/Index/get_prize');?>",{kid:ids},function(result){
            if(result.code>0){
              layer.msg('领取成功',{
                icon: 1,
                time: 1000 //2秒关闭（如果不配置，默认是3秒）
              });

              th.attr("ids",0);
              th.children("em").css("color","#999");
              th.css("background-image","url(/Public/Home/icon/hou.jpg)");
            }else{
              alert(result.msg);
            }
          })
				},function(){
					//取消
				})
			})
			
			
			

</script>

</body>
</html>