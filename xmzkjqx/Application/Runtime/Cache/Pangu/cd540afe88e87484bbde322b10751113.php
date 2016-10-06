<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link href="/Public/Pangu/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Pangu/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/Pangu/lib/icheck/icheck.css" rel="stylesheet" type="text/css" />
<link href="/Public/Pangu/lib/Hui-iconfont/1.0.1/iconfont.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="/Public/Pangu/lib/web2/webuploader.css" />
<link rel="stylesheet" type="text/css" href="/Public/Pangu/lib/web2/style.css" />

<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/Public/UEditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/UEditor/ueditor.all.js"></script>

<title>编辑</title>
</head>
<body>
<div class="pd-20">
  <form id="form2" method="post" class="form form-horizontal" id="form-article-add">

   <input type="hidden" name="type" value="<?php echo ($type); ?>"></input>

    <div class="row cl">
      <label class="form-label col-2"><span class="c-red">*</span>类型编码：</label>
      <div class="formControls col-6">
       <input type="text" class="input-text" placeholder="" value="<?php echo ($list["goods_no"]); ?>" name="goods_no" id="goods_no">
      </div>   
    </div>
    <div class="row cl">
      <label class="form-label col-2"><span class="c-red">*</span>名称：</label>
      <div class="formControls col-6">
       <input type="text" class="input-text" placeholder="" value="<?php echo ($list["goods_name"]); ?>" name="goods_name" id="goods_name">
      </div>   
    </div>
    <div class="row cl">
      <label class="form-label col-2"><span class="c-red">*</span>价格：</label>
      <div class="formControls col-6">
       <input type="text" class="input-text" placeholder="" value="<?php echo ($list["price"]); ?>" name="price" id="price">
      </div>   
    </div>


 <div class="row cl">
      <label class="form-label col-2">顶部图片：</label>
      <div class="formControls col-2"> 
      <p class="" id="file_upload2"><span>上传</span></p><label>预览：</label><img id="img2" style="hight:64px;width:64px" src="<?php echo (is_img($list["head_img"])); ?>"><input type="hidden"  id="head_img" name="head_img" value="<?php echo (is_img($list["head_img"])); ?>">
 </div>

 <div class="row cl">
      <label class="form-label col-2"><span class="c-red">*</span>详情：</label>
      <div class="formControls col-8">
        <!-- 第二步：初始化 UDEITOR编辑器，更多的参数可以看说明文档-->
        <script type="text/javascript" >
          UE.getEditor('detail_img')/*,{initialFrameHeight:400,initialFrameWidth:600}*/
        </script>
        <!-- 第三步：用于显示UDEITOR编辑器，可以是<textarea>，也可以是<script>... -->
        <textarea name="detail_img" id="detail_img" style="width:80%px;height:500px"><?php echo ($list["detail_img"]); ?></textarea>
      </div>
    </div>


</div>
 
    <div class="row cl">
      <div class="col-10 col-offset-2">
        <button id="sub_form2" class="btn btn-primary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 保存</button>

        <button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="/Public/Pangu/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/layer/1.9.3/layer.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="/Public/Pangu/lib/Validform/5.3.2/Validform.min.js"></script> 


<script type="text/javascript" src="/Public/Pangu/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/Pangu/js/H-ui.admin.js"></script> 

  <script type="text/javascript">
      $(function(){
        $('#sub_form2').click(function(){
          var data=$("#form2").serialize();
          var id="<?php echo ($id); ?>";
          $.post("<?php echo U('Index/do_edit');?>?table=goods&id="+id,data,function(data){
            if (data>=1) {
              //layer.msg("修改成功");
              alert("修改成功");
              parent.location.reload();
          }else{
            //alert('失败'+data);
            alert('失败'+data);
          };
          })
        })
      })

    </script>

<script type="text/javascript">
$(function(){
  $('.skin-minimal input').iCheck({
    checkboxClass: 'icheckbox-blue',
    radioClass: 'iradio-blue',
    increaseArea: '20%'
  });
});
</script>



<script type="text/javascript" src="/Public/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
$(function(){


$('#file_upload2').uploadify({

        //'debug' : true,
         'buttonText':'上传',
        // 'width':'130',
        // 'height':'40',
        // 'buttonImage':'<<?php echo ($templets); ?>>/images/sc_03_06.jpg',
        'queueID'  : 'some_file_queue',//取消上传进度显示
        'swf'      : "/Public/uploadify/uploadify.swf",
        //'uploader' : "/index.php/home/reg/upload_img",
        'uploader' : "<?php echo U('Home/Other/upload');?>",
        'onFallback':function(){
            alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
        },
        "onUploadSuccess":function(file, data, response){

            if (data!=-1) {
              $('#img2').attr('src',data);
              $('#head_img').val(data);           
            }else{
              alert('上传失败，请重新上传');
            }
        }
        // Put your options here
    });


$(".w_dp").click(function(s){
                    $(this).parent('div').remove();
                  var ss="";
                  $(".w_dp").each(function(){
                    ss=ss+','+$(this).attr("asrc");
                  })
                  $("#img_url3").val(ss);
})  
$('#file_upload1').uploadify({
         'buttonText':'上传',
        'queueID'  : 'some_file_queue',//取消上传进度显示
        'swf'      : "/Public/uploadify/uploadify.swf",
        'uploader' : "<?php echo U('Home/Other/upload');?>",
        'onFallback':function(){
            alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
        },
        
        "onUploadSuccess":function(file, data, response){
            if (data!=-1) {
              var s=$('#img_url3').val();
              //var s=$('#img_url3').val();
              s=s+',' +data;
              str='<div style="float: left;"><img style="hight:64px;width:64px;float:left" src="'+data+'"><span class="w_dp" asrc="'+data+'">删除</span>';

              $("#spp3").append(str);
              $('#img_url3').val(s);  

              $(".w_dp").click(function(s){
                  $(this).parent('div').remove();
                  var ss="";
                  $(".w_dp").each(function(){
                    ss=ss+','+$(this).attr("asrc");
                  })
                  $("#img_url3").val(ss);
              })         
            }else{
              alert('上传失败，请重新上传');
            }
        }
        // Put your options here
    });


})

</script>

</body>
</html>