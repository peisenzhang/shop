<?php

namespace Home\Controller;
use Think\Controller;
class SearchController extends NavController{
    //
    public function cat_search(){        
        //商品信息
        var_dump($_GET);DIE;
        $catId = I('get.cat_id');
        $gModel = D('Admin/goods');
        $goodsId = $gModel->getGoodsIdByCatId($catId);
        $goodsData = $gModel->goods_search($goodsId, 3);
        //筛选条件
        
        $cModel = D('Admin/category');
        $ret = $cModel->getSearchConditionByGoodsId($goodsData['goods_id']);
        $this->assign(array(
            'searchFilter' => $ret,
            'goodsData' => $goodsData,
        ));
        $this->display();
    }  
    public function key_search(){        
        //商品信息
        $key = I('get.key');
        $gModel = D('Admin/goods');
        $goodsId = $gModel->alias("a")->field("GROUP_CONCAT(DISTINCT a.id) goodsId")
                 ->field("GROUP_CONCAT(a.id) goods_id")              
                 ->join("LEFT JOIN __GOODS_ATTR__ b ON b.goods_id=a.id")
                 ->where(array(
                     'a.is_on_sale' => array('eq', '是'),
                     'a.goods_name' => array('exp', "LIKE '%$key%' OR a.goods_desc LIKE '%$key%' OR b.attr_value LIKE '%$key%'")
                 ))->find();
        $goodsId = explode(',', $goodsId['goods_id']);
        //var_dump($goodsId);die;
        $goodsData = $gModel->goods_search($goodsId);
        //筛选条件
        $cModel = D('Admin/category');
        $ret = $cModel->getSearchConditionByGoodsId($goodsData['goods_id']);
        $this->assign(array(
            'searchFilter' => $ret,
            'goodsData' => $goodsData,
        ));
        $this->display();
    }  
    public function test(){
//        $cModel = D('Admin/goods');
//        $dd = $cModel->cat_search(1,5);
//        echo count($dd['list']);die;
//        var_dump($cModel->cat_search(1, 5));die;
        $cModel = D('category');
        $cModel->getSearchConditionByGoodsId();
    }
    
     public function goods_search(){   
         $gModel = D('Admin/goods');
         $goodsId = "";
        //如果是按分类搜索就通过分类取goods_id
        if(I('get.cat_id')){
            $catId = I('get.cat_id');            
            $goodsId = $gModel->getGoodsIdByCatId($catId);            
        }
        //如果是通过关键字搜索，就通过关键字取goods_id
        if(I('get.key')){
            $key = I('get.key');            
            $goodsId = $gModel->alias("a")->field("GROUP_CONCAT(DISTINCT a.id) goodsId")
                     ->field("GROUP_CONCAT(a.id) goods_id")              
                     ->join("LEFT JOIN __GOODS_ATTR__ b ON b.goods_id=a.id")
                     ->where(array(
                         'a.is_on_sale' => array('eq', '是'),
                         'a.goods_name' => array('exp', "LIKE '%$key%' OR a.goods_desc LIKE '%$key%' OR b.attr_value LIKE '%$key%'")
                     ))->find();
            $goodsId = explode(',', $goodsId['goods_id']);
        }
        //取商品数据
        $goodsData = $gModel->goods_search($goodsId, 3);
        //筛选条件
        $cModel = D('Admin/category');
        $ret = $cModel->getSearchConditionByGoodsId($goodsData['goods_id']);
        $this->assign(array(
            'searchFilter' => $ret,
            'goodsData' => $goodsData,
        ));
        $this->display();
    }  
}

