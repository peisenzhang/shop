<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>京西商城</title>
	<link rel="stylesheet" href="/Public/Home/style/base.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/global.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/header.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/index.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/bottomnav.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/footer.css" type="text/css">

	<script type="text/javascript" src="/Public/Home/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/Public/Home/js/header.js"></script>
	<script type="text/javascript" src="/Public/Home/js/index.js"></script>
</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w1210 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
                                    <li id="chklogin"></li>
<!--                                    <li><?php if(session('m_id')):?>
                                        <?php echo session('m_user_name');?>您好，欢迎来到京西！[<a href="<?php echo U('Member/logout');?>">退出</a>]
                                        <?php else:?>
                                        [<a href="<?php echo U('Member/login');?>">登录</a>] [<a href="<?php echo U('Member/regist');?>">免费注册</a>] </li>
                                        <?php endif;?>-->
					<li class="line">|</li>
					<li>我的订单</li>
					<li class="line">|</li>
					<li>客户服务</li>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
<!--	<div style="clear:both;"></div>-->

	
<link rel="stylesheet" href="/Public/Home/style/cart.css" type="text/css">	
<script type="text/javascript" src="/Public/Home/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/Public/Home/js/cart1.js"></script>
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->       
	<div class="mycart w990 mt10 bc">
		<h2><span>我的购物车</span></h2>               
		<table>
			<thead>
				<tr>
					<th class="col1">商品名称</th>
					<th class="col2">商品信息</th>
					<th class="col3">单价</th>
					<th class="col4">数量</th>	
					<th class="col5">小计</th>
					<th class="col6">操作</th>
				</tr>
			</thead>
			<tbody>
                            <?php foreach($cartData as $k => $v): $tot=0; ?>
				<tr>
					<td class="col1"><a href=""><?php echo showImg($v['mid_logo']);?></a>  <strong><a href=""><?php echo $v['goods_name'];?></a></strong></td>
					<td class="col2">
                                            <?php foreach($v['gaData'] as $k1 => $v1):?>
                                            <p><?php echo $v1['attr_name'].":".$v1['attr_name'];?></p> 
                                            <?php endforeach;?>
                                        </td>
					<td class="col3">￥<span><?php echo $v['price'];?></span></td>
					<td class="col4"> 
						<a href="javascript:;" class="reduce_num"></a>
                                                <input type="text" name="amount" value="<?php echo $v['goods_number'];?>" class="amount"/>
						<a href="javascript:;" class="add_num"></a>
					</td>
					<td class="col5">￥<span><?php echo $v['dg_tol_price']; ?></span></td>
                                        <td class="col6"><a href="<?php echo U('del')?>">删除</a></td>
				</tr>
                                <?php endforeach;?>				
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6">购物金额总计： <strong>￥ <span id="total"><?php $cartData['tol_price'];?></span></strong></td>
				</tr>
			</tfoot>
		</table>
		<div class="cart_btn w990 bc mt10">
			<a href="" class="continue">继续购物</a>
                        <a href="<?php echo U('Order/add');?>" class="checkout">结 算</a>
		</div>
	</div>        
	<!-- 主体部分 end -->

	<div style="clear:both;"></div>
	

<!--	<div style="clear:both;"></div>-->
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt10">
		<p class="links">
			<a href="">关于我们</a> |
			<a href="">联系我们</a> |
			<a href="">人才招聘</a> |
			<a href="">商家入驻</a> |
			<a href="">千寻网</a> |
			<a href="">奢侈品网</a> |
			<a href="">广告服务</a> |
			<a href="">移动终端</a> |
			<a href="">友情链接</a> |
			<a href="">销售联盟</a> |
			<a href="">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="images/xin.png" alt="" /></a>
			<a href=""><img src="images/kexin.jpg" alt="" /></a>
			<a href=""><img src="images/police.jpg" alt="" /></a>
			<a href=""><img src="images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->

</body>
</html>

<scripT>
    // ajax 获取登录状态 
    $.ajax({
        type : 'GET',
        dataType : 'json',
        url : "<?php echo U('Member/ajaxChkLogin');?>",
        success : function(data){
            if(data.login == 1)
                var li = data.user_name+"您好，欢迎来到京西！[<a href='<?php echo U('Member/logout');?>'>退出</a>]";
            else
                var li = '<a href="<?php echo U('Member/login');?>">登录</a>] [<a href="<?php echo U('Member/regist');?>">免费注册</a>] </li>';
            $("#chklogin").html(li);
        }
    })
</scripT>