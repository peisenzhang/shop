<?php

namespace Admin\Model;
use Think\Model;
class CartModel extends Model{
    protected $insertFields = array('goods_id', 'goods_attr_id', 'goods_number');
    protected $updateFields = array('id', 'goods_id', 'goods_attr_id', 'goods_number');
    protected $_validate = array(
        array('goods_id', 'require', '必须选择商品'),
       //array('goods_number', 'chkGoodsNumber', '库存量不足！', 1, 'callback')
    );
    public function chkGoodsNumber($goods_number){
        $goods_id = I('get.goods_id');
        $goods_attr_id = I('get.goods_attr_id');
        sort($goods_attr_id, SORT_NUMERIC);
        $gaid = implode(',', $goods_attr_id);
        $gnModel = D('Admin/goods_number');
        //取出库存量
        $gn = $gnModel->field('goods_number')->where(array(
            'goods_id' => array('eq',$goods_id),
            'goods_attr_id' => array('eq', $gaid),
        ))->find();
        //返回库存量是否够
        return ($gn['goods_number'] >= $goods_number);
     }
     
     //重写父类的add方法，登录存数据库，未登录存cookie
     public function add(){
         $goods_id = $this->goods_id;
         $goods_attr_id = $this->goods_attr_id;
         sort($goods_attr_id, SORT_NUMERIC);
         $goods_attr_id = implode(',', $goods_attr_id);
         $goods_number = $this->goods_number;
         $member_id = session('m_id'); 
         //判断是否登录
         if($member_id){//登录存数据库
             $has = $this->where(array(
                 'goods_id' => array('eq', $goods_id),
                 'goods_attr_id' => array('eq', $goods_attr_id),
                 'member_id' => array('eq', $member_id),
             ))->find();
             if($has){
                 $this->where(array('id' => array('eq', $has['id'])))->setInc('goods_number', $goods_number);
                 return true;
             }else{
                 parent::add(array(                   
                     'goods_id' => $goods_id,
                     'goods_attr_id' => $goods_attr_id,
                     'goods_number' => $goods_number,  
                     'member_id' => $member_id,
                 ));
                 return true;
             }
         }else{//未登录存cookie
             $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
             $key = $goods_id.'-'.$goods_attr_id;
             $cart[$key] ? $cart[$key] += $goods_number : $cart[$key] = $goods_number;
             //把一维数组存到cookie中
             setcookie('cart', serialize($cart), time()+30*86400);
             return true;
         }         
     }
     
     //登录成功后把cookie中的数据存入数据库并清空cookie
     public function moveDataToDb(){
         $member_id = session('m_id');
         if($member_id){
             $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
             foreach($cart as $k => $v){
                 $_k = explode('-', $k);
                 $has = $this->where(array(
                     'goods_id' => array('eq', $_k[0]),
                     'goods_attr_id' => array('eq', $_k[1]),
                     'member_id' => array('eq', $member_id),
                 ))->find();
                 if($has){
                     $this->where(array('id' => array('eq', $has['id'])))->setInc('goods_number', $v);
                 }else{
                     $this->add(array(
                        'goods_id' => $_k[0],
                         'goods_attr_id' => $_k[1],
                         'goods_number' => $v,
                         'member_id' => $member_id,
                     ));
                 }
             }
             //清除cookie
             setcookie('cart', '', time()-1);
         }
     }
     
     //购物车列表页
     public function cartList(){
         $member_id = session('m_id');
         //判断是否登录
         if($member_id){ //登录，从数据库中取出该会员的购物数据
             $data = $this->where(array('member_id' => array('eq', $member_id)))->select();             
         }else{ //未登录，从cookie中取出该购物数据
             $cart = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : array();
             if($cart){
                 foreach($cart as $k => $v){
                     $_k = explode('-', $k);
                     $data[] = array(
                       'goods_id' => $_k[0]  ,
                         'goods_attr_id' => $_k[1],
                         'goods_number' => $v,
                         'member_id' => $member_id,
                     );
                 }
             }else{
                 return "购物车为空！";
             } 
         }
            
             $gModel = D('Admin/goods');
             $gaModel = D('Admin/goods_attr');
             if($data){
                foreach($data as $k => $v){
                    $info = $gModel->field('mid_logo, goods_name')->find($v['goods_id']);
                    $data[$k]['goods_name'] = $info['goods_name'];
                    $data[$k]['mid_logo'] = $info['mid_logo'];
                    $data[$k]['price'] = $gModel->getMemberPrice();
                    $data[$k]['dg_tol_price'] = $data[$k]['price']*$v['goods_number'];                    
                    $data[$k]['gaData'] = $gaModel->alias("a")
                            ->field("a.attr_value, b.attr_name")
                            ->join("LEFT JOIN __ATTRIBUTE__ b ON b.id=a.attr_id")
                            ->where(array(
                                'a.id' => array('in', $v['goods_attr_id'])
                    ))->select();
                }
             }           
         return $data;
     }
     
     //清空购物车
     public function clear(){
         $this->where(array(
             'member_id' => array('eq', session('m_id'))
             ))->delete();
     }
          
}
