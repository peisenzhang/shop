<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加新商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $_page_btn_link;?>"><?php echo $_page_btn_name;?></a>
    </span>
    <span class="action-span1"><a href="__GROUP__"> 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $_page_title;?> </span>
    <div style="clear:both"></div>
</h1>

<div class="tab-div">
<!--    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
            <span class="tab-back" id="general-tab">商品描述</span>
            <span class="tab-back" id="general-tab">会员价格</span>
            <span class="tab-back" id="general-tab">商品属性</span>
            <span class="tab-back" id="general-tab">商品相册</span>
        </p>
    </div>-->
    <div id="tabbody-div">
        
<!--<form action="/index.php/Admin/MemberLevel/lst" name="searchForm" method="get">
    <p>
        品牌名称:<input type="text" value="<?php echo I('get.bn');?>" name="bn" />
    </p>
    <p>
        排序方式：
        <?php $odby = I('get.odby', id_desc);?>
        <input onclick="this.parentNode.parentNode.submit()" type="radio" value="id_desc" name="odby" <?php if($odby == 'id_desc') echo 'checked="checked"';?>/>以添加时间降序
        <input onclick="this.parentNode.parentNode.submit()" type="radio" value="id_asc" name="odby" <?php if($odby == 'id_asc') echo 'checked="checked"';?>/>以添加时间升序
    </p>
        <input type="submit" value=" 搜索 " class="button" />
</form>-->
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>级别名称</th>
                <th>积分下限</th>
                <th>积分上限</th>
                <th>操作</th>
            </tr>
            <?php foreach($data as $k => $v):?>
            <tr class="tron">
                <td align="center"><?php echo $v['id'];?></td>
                <td align="center" class="first-cell"><span><?php echo $v['level_name'];?></span></td>
                <td align="center"><?php echo $v['jifen_bottom'];?></td>
                <td align="center"><span><?php echo $v['jifen_top'];?></span></td>                
                <td align="center">
                <a href="/index.php/Goods/?goods_id=<<?php echo ($val["goods_id"]); ?>>" target="_blank" title="查看"><img src="./Images/icon_view.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('edit?id='.$v['id']);?>" title="编辑">编辑</a>
                <a href="<?php echo U('del?id='.$v['id']);?>" onclick="" title="回收站">删除</a></td>
            </tr>
            <?php endforeach;?>
        </table>

    <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="80%">&nbsp;</td>
                <td align="center" nowrap="true">
                    <?php echo $show;?>
                </td>
            </tr>
        </table>
    <!-- 分页结束 -->
    </div>
</form>

<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
<!-- 引入时间插件 -->
<link href="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.css" />
<script type="text/javascript" src="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="/Public/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script>
  $.timepicker.setDefaults($.timepicker.regional['zh-CN']);
  $("#fa").datepicker();
  $("#ta").datepicker();
</script>

<script>
    $(".tron").mouseover(function(){
          $(this).find("td").css('backgroundColor', "#ccc"); 
          //alert('dddd');
      });
    $(".tron").mouseout(function(){
        $(this).find("td").css('backgroundColor', '#fff')
    });
</script>
    </div>
</div>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>