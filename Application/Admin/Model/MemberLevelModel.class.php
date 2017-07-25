<?php

namespace Admin\Model;
use Think\Model;
class MemberLevelModel extends Model{
   
    protected  function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
    }
    protected function _after_insert($data, $options) {
        parent::_after_insert($data, $options);
       
    }
    
//    public function search(){
//        $this->;
//    }
}
    
