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
        
 <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
            <span class="tab-back" id="general-tab">商品描述</span>
            <span class="tab-back" id="general-tab">会员价格</span>
            <span class="tab-back" id="general-tab">商品属性</span>
            <span class="tab-back" id="general-tab">商品相册</span>
        </p>
    </div>
        <form enctype="multipart/form-data" action="/index.php/Admin/Goods/edit/id/6.html" method="post">
            <table width="90%" id="general-table" align="center">
                <input name="id" type="hidden" value="<?php echo $gData['id'];?>"/>
                  <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value="<?php echo $gData['goods_name'];?>"  size="30" />
                    <span class="require-field">*</span></td>
                </tr>
                
                
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo $gData['shop_price'];?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                 <tr>
                    <td class="label">促销价格：</td>
                    <td>
                        <input type="text"  name="promote_price" value="<?php echo $gData['promote_price'];?>" size="20"/><br/>
                        从 <input type="text" id='promote_start_date' name="promote_start_date" value="<?php echo $gData['promote_start_date'];?>" size="20"/>
                        到<input type="text" id="promote_end_date" name="promote_end_date" value="<?php echo $gData['promote_end_date'];?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否新品：</td>
                    <td>
                        <input type="radio" name="is_new" value="是" <?php if($gData['is_new'] == '是') echo 'checked="checked"';?>/> 是
                        <input type="radio" name="is_new" value="否" <?php if($gData['is_new'] == '否') echo 'checked=""';?>/> 否
                    </td>
                </tr> 
                <tr>
                    <td class="label">是否热卖：</td>
                    <td>
                        <input type="radio" name="is_hot" value="是" <?php if($gData['is_hot'] == '是') echo 'checked="checked"';?>/> 是
                        <input type="radio" name="is_hot" value="否" <?php if($gData['is_hot'] == '否') echo 'checked=""';?>/> 否
                    </td>
                </tr> 
                <tr>
                    <td class="label">是否精品：</td>
                    <td>
                        <input type="radio" name="is_best" value="是" <?php if($gData['is_best'] == '是') echo 'checked="checked"';?>/> 是
                        <input type="radio" name="is_best" value="否" <?php if($gData['is_best'] == '否') echo 'checked=""';?>/> 否
                    </td>
                </tr> 
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="是" <?php if($gData['is_on_sale'] == '是') echo 'checked="checked"';?>/> 是
                               <input type="radio" name="is_on_sale" value="否" <?php if($gData['is_on_sale'] == '否') echo 'checked=""';?>/> 否
                    </td>
                </tr> 
                <tr>
                    <td class="label">是否推荐到楼层：</td>
                    <td>
                        <input type="radio" name="is_floor" value="是" <?php if($gData['is_floor'] == '是') echo 'checked="checked"';?>/> 是
                        <input type="radio" name="is_floor" value="否" <?php if($gData['is_floor'] == '否') echo 'checked=""';?>/> 否
                    </td>
                </tr> 
                <tr>
                    <td class="label">排序：</td>
                    <td>
                        <input type="text" name="sort_num" value="<?php echo $gData['sort_num'];?>" />                         
                    </td>
                </tr> 
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo $gData['market_price'];?>" size="20" />
                    </td>
                </tr> 
                <tr>
                    <td class="label">商品品牌：</td>
                    <td>
                        <?php buildSelect('brand', 'brand_id', 'id', 'brand_name', $gData['brand_id']);?>
                    </td>
                </tr> 
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <?php echo $catSelect;?>
                    </td>
                </tr> 
                 <tr>
                    <td class="label">扩展分类：<input onclick="$('#cat_list').append($('#cat_list').find('li').eq(0).clone())" type="button" value="添加一张"/></td>
                    <td>
                        <ul id="cat_list">
                            <?php foreach($extCatSelect as $k => $v):?>
                            <li><?php echo $v;?></li>
                            <?php endforeach;?>
                        </ul>                       
                    </td>
                </tr> 
                <tr>
                    <td class="label">LOGO：</td>
                    <td>
                        <input type="file" name="logo"  size="60" />
                    </td>
                </tr> 
            </table>
            <!-- 商品描述 -->
            <table style="display:none;" width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">商品简单描述：</td>
                    <td>
                        <textarea id="goods_desc" name="goods_desc" cols="40" rows="3"><?php echo $gData['goods_desc'];?></textarea>
                    </td>
                </tr>
            </table>
            <!-- 会员价格 -->
            <table style="display: none;" width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">会员价格：</td>
                    <td>
                        <?php foreach($mData as $k => $v):?>
                        <p><?php echo $v['level_name'];?><input type="text" name="price[<?php echo $v['id'];?>]" value="<?php echo $mpData[$v['id']];?>" size="20" /></p>
                        <?php endforeach;?>
                    </td>
                </tr> 
            </table>   
            <!-- 商品属性 -->
            <table style="display: none;" width="90%" id="general-table" align="center">
                <tr><td>
            		商品类型：<?php buildSelect('Type', 'type_id', 'id', 'type_name', $gData['type_id']); ?>
            	</td></tr>
            	<tr>
                        <td>
                            <ul id="ai_list">
                                <?php  foreach($aData as $k => $v): if(in_array($v['attr_id'], $attrId)) $opt = '-'; else { $opt = '+'; $attrId[] = $v['attr_id']; } ?> 
                                <li>
                                    <input type="hidden" name="goods_attr_id[]" value="<?php echo $v['id'];?>"/>
                                    <?php if($v['attr_type'] == "可选"):?>
                                    <a href="#" onclick="addNewAttr(this)">[<?php echo $opt;?>]</a>
                                    <?php endif;?>
                                    <?php echo $v[attr_name];?>
                                    <?php if($v['attr_option_value'] == ''):?>
                                    <input type="text" name="attr_value[<?php echo $v['attr_id'];?>][]" value="<?php echo $v['attr_value'];?>"/>
                                    <?php else: $attr = explode(',' , $v['attr_option_value']); ?>
                                    <select name="attr_value[<?php echo $v['attr_id'];?>][]" >
                                        <option value="0" >请选择</option>
                                        <?php foreach($attr as $k1 => $v1): if($v1 == $v['attr_value']) $selected = 'selected="selected"'; else $selected = ''; ?>
                                        <option <?php echo $selected;?> value="<?php echo $v1;?>"><?php echo $v1;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <?php endif;?>
                                </li>
                                <?php endforeach;?>
                            </ul>
                         </td>
            	</tr>
            </table>
            <!-- 商品相册 -->
            <table style="display: none;" width="90%" id="general-table" align="center">
                <tr>
                    <td>
                        <input id="btn_add_pic" type="button" value="添加一张" name="" />
                        <hr/>
                        <ul id="ul_pic_list"></ul>
                        <ul id="old_pic_list">
                            <?php foreach($gpData as $k => $v):?>
                            <li style='display: inline-block'><input pic_id="<?php echo $v['id'];?>" type='button' value='删除' class="btn_del_pic"><?php showImg($v['mid_pic']);?></li>
                            <?php endforeach;?>
                        </ul>
                    </td>
                </tr>
            </table>                                              
                               
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
   

<link href="/Public/umeditor1_2_2-utf8-php/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/lang/zh-cn/zh-cn.js"></script>
<script>
    UM.getEditor('goods_desc', {
        initialFrameWidth: '100%',
        initialFrameHeight: 350
    });
</script>
<!-- 切换代码 -->
<script>
    $("#tabbar-div p span").click(function(){
        //点击的第几个按钮
        var i = $(this).index();
        //隐藏所有的table，显示第一个table
        $("table").hide();
        $("table").eq(i).show();
        //取消其他按钮的选中状态，把点击的按钮给选中
        $(".tab-front").removeClass("tab-front").addClass("tab-back");
        $(this).removeClass("tab-back").addClass("tab-front");
    })
</script>
<!-- 添加一张的按钮 -->
<script>
    $("#btn_add_pic").click(function(){
        var file = '<li><input type="file" name="pic[]" /></li>';
	$("#ul_pic_list").append(file);
    });
</script>
<!-- ajax删除相册图片 -->
<script>
    $(".btn_del_pic").click(function(){
        if(confirm("确定要删除吗？"))
            var li = $(this).parent();
            var pid = $(this).attr('pic_id');
            $.ajax({
                type : 'GET',
                url : "<?php echo U('ajaxDelPic', '', false);?>/picid/"+pid,
                success : function(data){
                    li.remove();
                }
            });
        
    })
    </script>
    <!-- ajax获取属性信息-->
    <script>
        $("select[name=type_id]").change(function(){
	// 获取当前选中的类型的id
	var typeId = $(this).val();
	// 如果选择了一个类型就执行AJAX取属性
	if(typeId > 0)
	{
		// 根据类型ID执行AJAX取出这个类型下的属性，并获取返回的JSON数据              
		$.ajax({
			type : "GET",
			url : "<?php echo U('ajaxGetAttribute', '', FALSE); ?>/type_id/"+typeId,
			dataType : "json",
			success : function(data)
			{
				/** 把服务器返回的属性循环拼成一个LI字符串，并显示在页面中 **/
				var li = "";                                
				// 循环每个属性                                
				$(data).each(function(k,v){
					li += '<li>';      
					// 如果这个属性类型是可选的就有一个+
					if(v.attr_type == '可选')
						li += '<a onclick="addNewAttr(this);" href="#">[+]</a>';
					// 属性名称
					li += v.attr_name + ' : ';	
					// 如果属性有可选值就做下拉框，否则做文本框
					if(v.attr_option_value == "")
						li += '<input type="text" name="attr_value['+v.id+'][]" />';
					else
					{
						li += '<select name="attr_value['+v.id+'][]"><option value="">请选择...</option>';
						// 把可选值根据,转化成数组
						var _attr = v.attr_option_value.split(',');
						// 循环每个值制作option
						for(var i=0; i<_attr.length; i++)
						{
							li += '<option value="'+_attr[i]+'">'+_attr[i] + '</option>';
						}
						li += '</select>';
					}
						
					li += '</li>';                       
                                    });
				// 把拼好的LI放到 页面中                   
				$("#ai_list").html(li);
			}
		});
	}
	else
		$("#ai_list").html("");  // 如果选的是请 选择就直接清空
});
function addNewAttr(a){ 
    var li = $(a).parent();
    if($(a).text() == '[+]'){
        var newLi = li.clone();
        $(newLi).find('a').text('[-]')
        $(newLi).find("option:selected").removeAttr("selected");
        $(newLi).find("input[name='goods_attr_id[]']").val("");
        li.after(newLi); 
    }else{
        li.remove();
        var gaid = li.find("input[name='goods_attr_id[]']").val();
        if(gaid == ''){
            li.remove();
        }else{
            if(confirm("如果删除了这个属性，相关的库存量也会删除，确定要删除吗？")){
                $.ajax({
                    type : "GET",
                    url : "<?php echo U('ajaxDelAttr?goods_id='.$gData['id'],'',false);?>/gaid/"+gaid,
                    success : function(data){
                        li.remove();
                    }
                })
            }
        }
        
    }
}
//function addNewAttr(a){
//    var li = $(a).parent();
//    if($(a).text() == '[+]'){
//        var newLi = li.clone();
//        $(newLi).find('a').text('[-]')
//        li.after(newLi);
//    }else{
//        var gaid = li.find("input[name='goods_attr_id[]']").val();
//        alert(gaid);
//        if(gaid == ''){
//            li.remove();
//        }else{
//            if(confirm("確定要刪除屬性嗎？該屬性的庫存量也要刪除")){
//                $.ajax({
//                type : "GET",
//                url : "<?php echo U('ajaxDelAttr?goods_id='.$gData['id'], '', false);?>/gaid/"+gaid,
//                success : function(data){
//                    li.remove();
//                }
//               })
//            }
//        }
//    }
//}
</script>
<!-- 引入时间插件 -->
<link href="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.css" />
<script type="text/javascript" src="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="/Public/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script>
  $.timepicker.setDefaults($.timepicker.regional['zh-CN']);
  $("#promote_start_date").datepicker();
  $("#promote_end_date").datepicker();
</script>
    </div>
</div>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>