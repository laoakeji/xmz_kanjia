<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
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
      <label class="form-label col-2"><span class="c-red">*</span>队一：</label>
      <div class="formControls col-8">
       <input type="text" class="input-text" placeholder="" value="<?php echo ($list["ca"]); ?>" name="ca" id="ca">
      </div>
    </div>

    <div class="row cl">
      <label class="form-label col-2"><span class="c-red">*</span>队二：</label>
      <div class="formControls col-8">
       <input type="text" class="input-text" placeholder="" value="<?php echo ($list["cb"]); ?>" name="cb" id="cb">
      </div>   
    </div>

    <div class="row cl">
      <label class="form-label col-2"><span class="c-red">*</span>比赛时间：</label>
      <div class="formControls col-8">
       <input type="text" class="input-text" placeholder="" value="<?php echo (date('Y-m-d H:i',$list["c_time"])); ?>" name="c_time" id="c_time">
      </div>
    </div>

    <div class="row cl">
      <label class="form-label col-2">队一国旗：</label>
      <div class="formControls col-2"> 
      <p class="" id="file_upload1"><span>上传</span></p><label>预览：</label><img id="img1" style="hight:64px;width:64px" src="<?php echo (is_img($list["a_img"])); ?>"><input type="hidden"  id="a_img" name="a_img" value="<?php echo (is_img($list["a_img"])); ?>">
    </div>

    <div class="row cl">
      <label class="form-label col-2">队二国旗：</label>
      <div class="formControls col-2"> 
      <p class="" id="file_upload2"><span>上传</span></p><label>预览：</label><img id="img2" style="hight:64px;width:64px" src="<?php echo (is_img($list["b_img"])); ?>"><input type="hidden"  id="b_img" name="b_img" value="<?php echo (is_img($list["b_img"])); ?>">
    </div>

    <div class="row cl">
      <label class="form-label col-2"><span class="c-red">*</span>胜利队伍：</label>
      <div class="formControls col-8">
       <input type="text" class="input-text" placeholder="" value="<?php echo ($list["result"]); ?>" name="result" id="result">
       胜利队伍用1和2表示。
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
          var id="<?php echo ($list["id"]); ?>";
          $.post("<?php echo U('Index/do_edit');?>?table=match&id="+id,data,function(data){
            if (data>=1) {
              alert("修改成功");
              parent.location.reload();
          }else{
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

$('#file_upload1').uploadify({

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
              $('#img1').attr('src',data);
              $('#a_img').val(data);   
            }else{
              alert('上传失败，请重新上传');
            }
        }
        // Put your options here
    });


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
              $('#b_img').val(data);   
            }else{
              alert('上传失败，请重新上传');
            }
        }
        
    });
})

</script>


</body>
</html>