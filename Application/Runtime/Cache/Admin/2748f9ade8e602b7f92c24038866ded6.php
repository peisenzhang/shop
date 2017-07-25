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
        
</div>

<!-- 商品列表 -->
<form method="post" action="/index.php/Admin/Goods/goods_number/id/6.html" >
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>                
                <?php foreach($gaData as $k => $v):?>
                <th><?php echo $k;?></th>
                <?php endforeach;?>
                <th>库存量</th>
                <th>操作</th>
            </tr>
            <?php if($gnData):?>
            <?php foreach($gnData as $k0 => $v0):?>
            <tr class="tron">
                <?php foreach($gaData as $k => $v):?>
                <td align="center">
                    <select name="goods_attr_id[]"><option value="0">请选择</option>
                        <?php foreach($v as $k1 => $v1):?>
                        <?php
 $_attr = explode(',', $v0['goods_attr_id']); if(in_array($v1['id'], $_attr)) $selected = 'selected="selected"'; else $selected =''; ?>
                        <option value="<?php echo $v1['id'];?>" <?php echo $selected;?>><?php echo $v1['attr_value'];?></option>
                        <?php endforeach;?>
                    </select>
                </td>
                <?php endforeach;?>
                <td><input type='text' name="goods_number[]" value="<?php echo $v0['goods_number'];?>"/></td>
                <td><input type="button" value='<?php echo $k0==0 ? "+" : "-";?>' onclick='addNewTr(this)'/></td>
            </tr>
                <?php endforeach;?>
                <?php else:?>
                <tr class="tron">
                <?php foreach($gaData as $k => $v):?>
                <td align="center">
                    <select name="goods_attr_id[]"><option value="0">请选择</option>
                        <?php foreach($v as $k1 => $v1):?>
                        <?php
 $_attr = explode(',', $v0['goods_attr_id']); if(in_array($v1['id'], $_attr)) $selected = 'selected="selected"'; else $selected =''; ?>
                        <option value="<?php echo $v1['id'];?>" <?php echo $selected;?>><?php echo $v1['attr_value'];?></option>
                        <?php endforeach;?>
                    </select>
                </td>
                <?php endforeach;?>
                <td><input type='text' name="goods_number[]" value="<?php echo $v0['goods_number'];?>"/></td>
                <td><input type="button" value='<?php echo $k0==0 ? "+" : "-";?>' onclick='addNewTr(this)'/></td>
            </tr>
           <?php endif;?>
            <tr id='submit'><td align='center' colspan='5'><input  type='submit' value='提交'/></td></tr>
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
    
    //
    function addNewTr(btn){
       var tr = $(btn).parent().parent();
       if($(btn).val() == '+'){           
           var newTr = tr.clone();
           newTr.find(":button").val('-');
           $("#submit").before(newTr);
       }else{
           tr.remove();
       }
       
    }
</script>
    </div>
</div>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>