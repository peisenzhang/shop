<?php

namespace Admin\Controller;
use Think\Controller;
class GoodsController extends BaseController{
    public function add(){
        if(IS_POST){ //判断是否提交表单    
            $model = D('goods');    //实例化模型   
            //var_dump($_POST);die;
            if($data = $model->create(I('post.'),1)){ //创建数据
                //var_dump($data);die;
                if($id = $model->add()){   //插入数据        
                    $this->success('添加商品成功', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());     
        }
        
        //取出品牌数据
        $bModel = D('brand');
        $bData = $bModel->select();
        $bdata = buildSelect('brand', 'brand_id', 'id', 'brand_name');
        
        //取出会员级别数据
        $mModel = D('member_level');
        $mData = $mModel->select();
        
        //取出分类数据
        $cModel = D('category');
        $catSelect = $cModel->buildCatSelect('cat_id');
        
        //扩展分类
        $extCatSelect = $cModel->buildCatSelect('ext_cat_id[]');
        
        $this->assign(array(
            '_page_title' => '添加商品',
            '_page_btn_name' => '商品列表',
            '_page_btn_link' => U('lst'),
            'bData' => $bData,
            'mData' => $mData,
            'catSelect' => $catSelect,
            'extCatSelect' => $extCatSelect,
        ));
        $this->display();
    }
    public function lst(){
        $model = D('goods');    
        $data = $model->search();
        //取出分类下拉框
        $cModel = D('category');
        $cSelect = $cModel->buildCatSelect('cat_id', I('get.cat_id'));
        $this->assign($data);
        $this->assign(array(
           '_page_title' => '添加商品',
            '_page_btn_link' => U('add'),
            '_page_btn_name' => '商品列表',
            'cSelect' => $cSelect,
        ));
        $this->display();
    }
    public function edit(){
        $id = I('get.id');
        $model = D('goods');        
        if(IS_POST){  
            if($model->create(I('post.'),2)){
                if(FALSE  !== $model->save()){
                    $this->success('修改成功！', U('lst'));
                    exit;
                }
            }
            $this->error("jiushicuole".$model->getError());
        }
        
        //取出品牌数据 下拉框
//        $bModel = D('brand');
//        $bData = $bModel->select();
        $bData = buildSelect('brand', 'brand_id', 'id', 'brand_name');
        
        //取出会员级别数据
        $mModel = D('member_level');
        $mData = $mModel->select();
        
        //取出该商品的会员价格
        $mpModel = D('membrer_price');
        $mpData = $mpModel->where(array('goods_id' => array('eq', $id)))->select();
        foreach($mpData as $k => $v){  //二维转一维
            $_mpData[$v['level_id']] = $v['price'];
        }
        
        //取出该商品的商品相册数据
        $gpModel = D('goods_pic');
        $gpData = $gpModel->where(array('goods_id' => array('eq', $id)))->select();    
        
        //取出该商品的原数据
        $gData = $model->find($id);
        
        //取出该商品的分类下拉框
        $cModel = D('category');
        $catSelect = $cModel->buildCatSelect('cat_id', $gData['cat_id']);
        
        //取出扩展分类数据并制作成下拉框赋值给模板
        $gcModel = D('goods_cat');
        $gcData = $gcModel->where(array('goods_id' => array('eq', $id)))->select();
        foreach($gcData as $k => $v){
            $extCatSelect[]= $cModel->buildCatSelect('ext_cat_id[]', $v['cat_id']);
        }
        
        //取出该商品的当前类型下的属性
        $aModel = D('attribute');
        $aData = $aModel->alias(a)
                ->field("a.id attr_id, a.attr_name, a.attr_type, a.attr_option_value, b.attr_value, b.id")
                ->join("LEFT JOIN __GOODS_ATTR__ b ON (b.attr_id=a.id AND b.goods_id=".$id.")")
                ->where(array('type_id' => array('eq', $gData['type_id'])))->select();
        //var_dump($aData);die;
        
        
        $this->assign(array(
            'gData' => $gData,  //原来商品数据
            'bData' => $bData,  //商品品牌下拉框数据
            'mData' => $mData,  //会员级别数据
            'mpData' => $_mpData, //会员价格数据
            'gpData' => $gpData,  //商品相册数据
            'catSelect' => $catSelect,   //主分类下拉框
            'extCatSelect' => $extCatSelect, //扩展分类下拉框组
            'aData' => $aData,
            '_page_title' => $gData['goods_name'].'-商品修改',
            '_page_btn_link' => U('lst'),
            '_page_btn_name' => '商品列表',
        ));
        $this->display();
    }
    public function del(){
        $id = I('get.id');
        $model = D('goods');
        if(FALSE != $model->delete($id))
            $this->success ('删除成功', U('lst'));
        else 
            $this->error('删除失败,原因：'.$model ->getError ());
    }
    
    
    public function ajaxDelPic(){
       $pid = I('get.picid');
       $model = D('goods_pic');
       $pic = $model->field('pic', 'sm_pic', 'mid_pic', 'big_pic')->find($pid);
       deleteImg($pic);
       $model->delete($pid);
    }
    
    //ajax获取属性
    public function ajaxGetAttribute(){
        $type_id = I('get.type_id');
        $model = D('attribute');
        $data = $model->where(array('type_id' => array('eq', $type_id)))->select();
        echo json_encode($data);
    }
    
    //ajax删除商品属性和相关属性的库存量
    public function ajaxDelAttr(){
        $goods_id = addslashes(I('get.goods_id'));
        $gaid = addslashes(I('get.gaid'));
        $gaModel = D('goods_attr');
        $gaModel->delete($gaid);
        //刪除庫存量
        $gnModel = D('goods_number');
        $gnModel->where(array(
            'goods_id' => array('EXP', "=$goods_id  AND FIND_IN_SET($gaid, goods_attr_id)")
        ))->delete();
        
    }
    
    //庫存量
    public function goods_number(){
        $id = I('get.id');
        $gnModel = D('goods_number');
        if(IS_POST){
            //先删除原库存
            $gnModel->where(array('goods_id' => array('eq', $id)))->delete();
            $gaid = I('post.goods_attr_id');
            $gn = I("post.goods_number");           
            $rate = count($gaid)/count($gn);            
            $_i = 0;
            foreach($gn as $k => $v){
                $gaidAttr = array();
                 for($i=0; $i<$rate; $i++){
                  $gaidAttr[] = $gaid[$_i];
                  $_i++;
               }
               //先升序排列
               sort($gaidAttr, SORT_NUMERIC);
               //把取出来的商品属性id转换成字符串
               $gaidAttr = (string)implode(',', $gaidAttr);
               $gnModel->add(array(
                  'goods_id' => $id,
                  'goods_attr_id' => $gaidAttr,
                  'goods_number' => $v,
               ));
            }               
        }
        
        //取出商品属性中可选属性的值
        $gaModel = D('goods_attr');
        $gaData = $gaModel->alias('a')
                ->field("a.*,b.attr_name,b.attr_type")
                ->join("LEFT JOIN __ATTRIBUTE__ b ON b.id=a.attr_id")
                ->where(array('a.goods_id' => array('eq', $id), 'b.attr_type' => array('eq', "可选")))
                ->select();        
        foreach($gaData as $k => $v){  //二维转三维
            $_gaData[$v['attr_name']][] = $v;
        }
        
        //取出库存量
        $gnData = $gnModel->where(array('goods_id' => array('eq', $id)))->select();
        
        $this->assign(array(
           'gaData' => $_gaData, 
           'gnData' => $gnData,
            '_page_title' => '库存量',
            '_page_btn_name' => '商品列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }
}