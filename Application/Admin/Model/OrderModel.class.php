<?php

namespace Admin\Model;
use Think\Model;
class OrderModel extends Model{
    //验证字段
    protected $_validate = array(
        array('shr_name', 'require', '收货人姓名不能为空', 1, 'regex'),
        array('shr_tel', 'require', '收货人电话不能为空', 1, 'regex'),
        array('shr_province', 'require', '收货人省份不能为空', 1, 'regex'),
        array('shr_city', 'require', '收货人省份城市不能为空', 1, 'regex'),
        array('shr_area', 'require', '收货人地区不能为空', 1, 'regex'),
        array('shr_address', 'require', '收货人详细地址不能为空', 1, 'regex'),        
    );
    
    protected function _before_insert(&$data, &$option)
	{
		$memberId = session('m_id');
		/********************* 下单前的检查 **************************/
		// 是否登录
		if(!$memberId)
		{
			$this->error = '必须先登录！';
			return FALSE;
		}
		// 购物车中是否有商品
		$cartModel = D('Admin/Cart');
		$option['goods'] = $goods = $cartModel->cartList();  // 获取购物车中的商品,并保存到 $option变量中，这个$option会被传到 _after_insert中
		if(!$goods)
		{
			$this->error = '购物车中没有商品，无法下单！';
			return FALSE;
		}
		
		// 读库存之前加锁,注意：把锁赋给这个模型，这样这个锁可以一直保存到下单结束，否则如果是局部变量这个锁在_before_insert函数执行完之后注释放了
		$this->fp = fopen('./order.lock');
		flock($this->fp, LOCK_EX);
		
		// 循环购物车中的商品检查库存量并且计算商品总价
		$gnModel = D('Admin/goods_number');
		$total_price = 0;  // 总价
		foreach ($goods as $k => $v)
		{
			// 检查库存量
			$gnNumber = $gnModel->field('goods_number')->where(array(
				'goods_id' => array('eq', $v['goods_id']),
				'goods_attr_id' => array('eq', $v['goods_attr_id']),
			))->find();
			if($gnNumber['goods_number'] < $v['goods_number'])
			{
				$this->error = '下单失败，原因：商品：<strong>'.$v['goods_name'].'</strong> 库存量不足！';
				return FALSE;
			}
			// 统计总价
			$total_price += $v['price'] * $v['goods_number'];  
		}
		// 把其他信息补到定单中
		$data['total_price'] = $total_price;
		$data['member_id'] = $memberId;
		$data['addtime'] = time();
		
		// 为了确定三张表的操作都能成功：定单基本信息表，定单商品表，库存量表
		$this->startTrans();
	}
	// 定单基本信息生成之后, $data['id']就是新生成的定单的id
	protected function _after_insert($data, $option)
	{
		// 从$option中取出购物车中的商品并循环插入到定单商品表中并且减少库存
		$ogModel = D('Admin/goods_order');
		$gnModel = D('Admin/goods_number');
		foreach ($option['goods'] as $k => $v)
		{
			$ret = $ogModel->add(array(
				'order_id' => $data['id'],
				'goods_id' => $v['goods_id'],
				'goods_attr_id' => $v['goods_attr_id'],
				'goods_number' => $v['goods_number'],
				'price' => $v['price'],
			));
			if(!$ret)
			{
				$this->rollback();
				return FALSE;
			}
			// 减库存
			$ret = $gnModel->where(array(
				'goods_id' => array('eq', $v['goods_id']),
				'goods_attr_id' => array('eq', $v['goods_attr_id']),
			))->setDec('goods_number', $v['goods_number']);
			if(FALSE === $ret)
			{
				$this->rollback();
				return FALSE;
			}
		}
		
		// 所有操作都成功提交事务
		$this->commit();
		
		// 释放锁
		flock($this->fp, LOCK_UN);
		fclose($this->fp);
		
		// 清空购物车
		$cartModel = D('Admin/Cart');
		$cartModel->clear();
	}
    
    public function setPaid($order_id){
        //设置订单付款状态和付款时间
        $oModel = D('Admin/order');
        $oModel->where(array('id' => array('eq', $order_id)))->save(array(
           'pay_status' => 1,
            'pay_time' => time(),
        ));
        //更新会员积分
        $tp = $oModel->field('total_price, member_id')->find($order_id);
        $mModel = D('member');
        $mModel->where(array('id' => array('eq', $tp['member_id'])))->setInc('jifen', $tp['total_price']);
    }
//    protected function _before_insert(&$data, &$options) {
//        parent::_before_insert($data, $options);
//        //判断是否登录
//        $member_id = session('m_id');
//        if(!$member_id){
//            $this->error = "必须先登录！";
//            return false;
//        }
//        //取购物车中的信息
//        $cModel = D('cart');
//        $options['goods'] = $goods = $cModel->cartList();
//        if(!$goods){
//            $this->error = "购物车中没有商品！";
//            return false;
//        }
//        
//        //读库存之前加锁 注意：把锁赋给这个模型，这样这个锁可以一直保存到下单结束，否则把锁赋值给局部变量，这个锁在_befort_insert_执行完就释放了
//        $this->fp = fopen('./order.lock');
//        flock($this->fp, LOCK_EX);
//        
//        //遍历购物车中的商品，检查库存量和计算总价
//        $gnModel = D('goods_number');
//        $total_price = 0;
//        foreach($goods as $k => $v){
//            //检查库存量
//            $gn = $gnModel->field("goods_number")->where(array(
//                'goods_id' => array('eq', $v['goods_id']),
//                'goods_attr_id' => array('eq', $v['goods_attr_id']),
//            ))->find();
//            if($gn['goods_number'] < $v['goods_number']){
//                $this->error = "下单失败，原因：商品".$v['goods_name']."库存量不足！";
//                return false;
//            }
//            //计算总价
//            $total_price += $v['price'] * $v['goods_number'];
//        }
//        //把其他信息补充到订单中
//        $data['total_price'] = $total_price;
//        $data['addtime'] = time();
//        $data['member_id'] = $member_id;
//        
//        //开启事务
//        $this->startTrans();
//    }
//    
//    protected function _after_insert($data, $options) {
//        parent::_after_insert($data, $options);
//        $goModel = D('goods_order');
//        $gnModel = D('goods_number');
//        foreach($options['goods'] as $k => $v){
//            $ret = $goModel->add(array(
//                'order_id' => $data['id'],
//                'goods_id' => $v['goods_id'] ,
//                'goods_attr_id' => $v['goods_attr_id'],
//                'goods_number' => $v['goods_number']
//            ));
//            if(!$go){
//                $this->rollback();
//                return false;
//            }
//            $ret = $gnModel->where(array(
//                'goods_id' => $v['goods_id'],
//                'goods_attr_id' => $v['goods_attr_id']
//            ))->setDec('goods_number', $v['goods_number']);
//            if(false !== $ret){
//                $this->rollback();
//                return false;
//            }
//            
//            //提交事务
//            $this->commit();
//            //释放锁
//            flock($fp, LOCK_UN);
//            fclock($this->fp);
//        }
//    }
}
