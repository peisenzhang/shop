<?php

namespace Admin\Model;
use Think\Model;
class BrandModel extends model{
    protected $insertFileds = array();
    protected $updataFileds =array();
    protected $_validate = array();
    
    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
        if($_FILES['logo']['error'] == 0){
            $ret = uploadOne('logo', 'Brand');
            if($ret['ok'] == 1){
                $data['logo'] = $ret['images'][0];
            }else{
                $this->error = $ret['error'];
            }
        }
    }
    protected function _before_update(&$data, $options) {
        parent::_before_update($data, $options);
        $id = $options['where']['id'];
        if($_FILES['logo']['error'] == 0){
            $ret = uploadOne('logo', 'Brand');
            if($ret['ok'] == 1){
                $data['logo'] = $ret['images'] [0];
            }else{
                $this->error = $ret['error'];
            }
        }
        //
        $logo = $this->field('logo')->find($id);
        deleteImg($logo);
    }
    protected function _before_delete($options) {
        parent::_before_delete($options);
        $id = $options['where']['id'];
        $logo = $this->field('logo')->find($id);
        deleteImg($logo);
        
    }
    
    //
    public function serach($perpage){
        //搜索
        $where = array();
        $bn = I('get.bn');
        if($bn)
            $where['brand_name'] = array('like', "%$bn%");
        
        //排序
        $odby = I('get.odby');
        $order_by = 'id';
        $order_way = 'desc';
        if($odby == 'id_asc')
            $order_way = 'asc';
        
        //翻页
        $count = $this->where($where)->count();  //取出总的记录数
        $Page = new \Think\Page($count, $perpage);
        $show = $Page->show();
        $list = $this->where($where)->order("$order_by $order_way")->limit($Page->firstRow.','.$Page->listRows)->select();
        return array(
            'show' => $show,
            'list' => $list
        );
    }
   
}

