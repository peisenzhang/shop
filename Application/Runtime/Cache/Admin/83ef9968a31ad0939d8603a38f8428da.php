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
        
        <form enctype="multipart/form-data" action="/index.php/Admin/Attribute/add/type_id/1.html" method="post">
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">属性名称：</td>
                    <td><input type="text" name="attr_name" value=""size="30" />
                    <span class="require-field">*</span></td>
                </tr>
                
                
                <tr>
                    <td class="label">属性类型</td>
                    <td>
                        <input type="radio" name="attr_type" value="唯一" size="20"/>唯一
                        <input type="radio" name="attr_type" value="可选" size="20"/>可选
                        <span class="require-field">*</span>
                    </td>
                </tr>               
                <tr>
                    <td class="label">属性可选值：</td>
                    <td>
                        <textarea  name="attr_option_value"  size="60" ></textarea>
                    </td>
                </tr> 
               <tr>
                    <td class="label">所属类型：</td>
                    <td>
                        <?php buildSelect('type', 'type_id', 'id', 'type_name', I('get.type_id'));?>
                    </td>
                </tr> 
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
   


    </div>
</div>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>