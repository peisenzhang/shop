<?php

namespace Admim\Model;
use Think\Model;
class TypeModel extends Model{ 
    protected function _before_delete($options) {
        parent::_before_delete($options);
        $attrModel = D('attribute');
        $attrModel->where(array('type_id' => array('eq', $options['where']['id'])))->delete();
    }
}
