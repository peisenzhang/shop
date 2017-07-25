<?php

namespace Admin\Model;
use Think\Model;
class AttributeModel extends Model{
    
    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);      
        $data['attr_option_value'] = str_replace('，', ',', $data['attr_option_value']);
    }


    public function search(){
        //搜索
        $where = array();
        //属性名称
        $attr_name = I('get.attr_name');
        if($attr_name)
            $where['attr_name'] = array('like', "%$attr_name%");
        //属性类型
        $attr_type = I('get.attr_type');
        if($attr_type)
            $where['attr_type'] = array('eq', $attr_type);
        //所属类型
        $type_id = I('get.type_id');
        if($type_id){
            $where['type_id'] = array('eq', $type_id);
        }
        //翻页
        $count = $this->count();
        $Page = new \Think\Page($count, 5);
        $show = $Page->show();
        $list = $this->field("a.*,b.type_name")
                     ->alias(a)
                     ->join('LEFT JOIN __TYPE__ b ON b.id=a.type_id')
                     ->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        return array(
            'list' => $list,
            'show' => $show,
        );
    }
}
