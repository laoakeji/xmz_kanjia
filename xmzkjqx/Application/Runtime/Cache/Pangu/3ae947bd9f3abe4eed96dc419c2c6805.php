<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>开天网络</title>

    <!-- Bootstrap -->
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

                    <p id="export" class="btn btn-info  btn-lg btn-block"><a data-type="csv" href="javascript:;">下载Excel表格</a></p>
                  

    <script src="/Public/search/js/Blob.js"></script>

      <script src="/Public/search/js/FileSaver.js"></script>

      <script src="/Public/search/js/tableExport.js"></script>



      <script>



      var $exportLink = document.getElementById('export');

      $exportLink.addEventListener('click', function(e){

        e.preventDefault();

        if(e.target.nodeName === "A"){

          tableExport('table2', '统计数据', e.target.getAttribute('data-type'));

        }

        

      }, false);



      </script>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <div class="alert alert-success">
         <button type="button" class="close" data-dismiss="alert">×</button>
        <h4></h4> <strong>发起记录</strong>共：<?php echo ($num); ?>次
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <table class="table" id="table2">
        <thead>
          <tr>
            <th>编号</th>
            <th>用户昵称</th>
            <th>产品</th>
            <th>砍掉价格</th>
            <th>参与时间</th>
          </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="success">
            <td><?php echo ($vo["id"]); ?></td>            
            <td><?php echo ($vo["nickname"]); ?></a></td>
            <td><?php echo ($vo["goods_name"]); ?></td>
            <td><?php echo ($vo["k_price"]); ?></td>
            <td><?php echo ($vo["c_time"]); ?></td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

    <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


  </body>
</html>