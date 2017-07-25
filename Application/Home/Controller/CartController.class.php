<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller{
    public function add(){      
        if(IS_POST){ 
          $model = D('Admin/Cart');
          if($data = $model->create()){ 
//              echo session('m_id')."<br/>";
//              var_dump($data);die;
//              if(session('m_id')){
//                  sort($data['goods_attr_id'], SORT_NUMERIC);
//                  $data['goods_attr_id'] = implode(',', $data['goods_attr_id']);
//                  $data['member_id'] = session('m_id');
//                  $has = $model->where(array(
//                      'goods_id' => array('eq', $data['goods_id']),
//                      'goods_attr_id' => array('eq', $data['goods_attr_id']),
//                      'member_id' => array('eq', $data['member_id']),
//                  ))->find();
//                  if($has){
//                      //$model->add($data);
//                  }else{
//                      //var_dump($data);die;
//                      $model->add($data);die;
//                  }
//              }
              //var_dump($data);die;
              if($model->add()){
                  $this->success('成功加入购物车！', U('lst'));
                  exit;
              }
          }
          $this->error('添加失败，失败原因》|：'.$model->getError());
       }
    }
    
    //获取购物车中的数据
    public function lst(){
        $model = D('Admin/Cart');
        $cartData = $model->cartList();
        //var_dump($cartData);die;
        $this->assign(array(
            'cartData' => $cartData,
        ));
        $this->display();
    }
    
    //调用购物车列表页的方法，输出json，以供商品详情页调用
     public function ajaxCartList(){
         $model = D('Admin/cart');
        echo json_encode($model->cartList());
     }
}
