<?php

namespace Home\Controller;
use Think\Controller;
class OrderController extends Controller{
    //
    public function add(){
      // 如果会员没有登录就跳到登录页面，登录成功之后再跳回到这个页面
		$memberId = session('m_id');
		if(!$memberId)
		{
			// 先要登录成功之后要跳回的地址存到SESSION
			session('returnUrl', U('Order/add'));
			redirect(U('Member/login'));
		}
		if(IS_POST)
		{
			$orderModel = D('Admin/Order');
			if($orderModel->create(I('post.'), 1))
			{
				if($orderId = $orderModel->add())
				{
					$this->success('下单成功!', U('order_success?order_id='.$orderId));
					exit;
				}
			}
			$this->error($orderModel->getError());
		}
		
		// 先取出购物车中所有的商品
		$cartModel = D('Admin/Cart');
		$data = $cartModel->cartList();
		
		// 设置页面信息
    	$this->assign(array(
    		'cData' => $data,
    		'_page_title' => '定单确认页',
    		'_page_keywords' => '定单确认页',
    		'_page_description' => '定单确认页',
    	));
    	$this->display();
    }
   
    //提交订单后跳转页面
    public function order_success(){
        $order_id = I('get.order_id');
        $btn = makeAlipayBtn($order_id);
        $this->assign(array(
            'btn' => $btn,
        ));
        $this->display();
    }
    
    //付款成功后接受页面
    public function receive(){
        require "./Pulbic/alipay/notify_url.php";
    }
}

