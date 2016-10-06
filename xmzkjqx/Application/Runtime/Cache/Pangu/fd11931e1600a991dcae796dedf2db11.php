<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
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
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>版权所有</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 行业动态 <span class="c-gray en">&gt;</span></nav>
<div class="pd-20">
共查询到<?php echo ($count); ?>条记录,其中完成10人的有<?php echo ($count_all); ?>人
<!-- <div class="cl pd-5 bg-1 bk-gray mt-20"> 
  <span class="l"><a href="javascript:;" id="" class="btn btn-danger radius del_all"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
  <a class="btn btn-primary radius" onclick="admin_add('添加','<?php echo U('Pangu/Index/edit');?>?table=news&type=<?php echo ($type); ?>','800','800')" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加</a>
   </span> 
</div> -->

	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
					<th width="80">用户ID</th>
					<th width="80">用户昵称</th>
					<th width="80">创建时间</th>
          <th width="80">发展人数</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="text-c">
					<td class="hidden-xs" aid="<?php echo ($vo["id"]); ?>"><input type="checkbox" value="<?php echo ($vo["id"]); ?>" aid="<?php echo ($vo["id"]); ?>" name=""></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["uid"]); ?></td>
					<td><?php echo ($vo["nickname"]); ?></td>
					<td><?php echo ($vo["c_time"]); ?></td>
          <td><?php echo ($vo["num"]); ?></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<?php echo ($page); ?>
	</div>
</div>
<script type="text/javascript" src="/Public/Pangu/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/layer/1.9.3/layer.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/datatables/1.10.0/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="/Public/Pangu/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/Pangu/js/H-ui.admin.js"></script>
<script type="text/javascript">

function admin_add(title,url,w,h){
	layer_show(title,url,w,h);
}
function admin_edit(title,url,w,h){
	layer_show(title,url,w,h);
}
</script> 


<script type="text/javascript">
$(function(){
  $(".del_all").click(function(){
      var ids="";
      
      $(".hidden-xs").each(function(){
          var p=$(this);
          if (p.children().is(":checked")) {
            ids+=p.attr('aid')+',';
          }
      })
          if (ids=="") {alert("您什么都没有选择呢");return false};
          var r=confirm("确认删除吗");
          if (r==true){
          }else{
            return false;
          }           
          $.get("<?php echo U('Index/do_del_all');?>?table=list&ids="+ids,function(data,s){
           
            if (data>0) {alert("删除成功");location.reload()}else{
              alert("失败");alert(data);
            }
          })
  })


  $(".w_del").click(function(){
    var r=confirm("确认删除吗");
    if (r==true){
    }else{
      return false;
    } 
    var p=$(this);
    var id=$(this).attr("aid");
    $.post("<?php echo U('Index/do_del');?>",{id:id,table:"list"},function(data){
      if (data==1) {
      alert("删除成功")     
      p.parent().parent().remove();return false;
      };
    });
  })

  

})

</script>





</body>
</html>