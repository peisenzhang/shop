<?php

namespace Admin\Model;
use Think\Model;
class RoleModel extends Model{  
    protected function _before_update(&$data, $options) {
        parent::_before_update($data, $options);;
        $id = $options['where']['id'];
        $prModel = D('role_pri');
        $pri_ids = I("post.pri_id");
        $prModel->where(array('role_id' => array('eq', $id)))->delete();
        foreach($pri_ids as $k => $v){
            $prModel->add(array(
                'pri_id' => $v,
                'role_id' => $id,
            ));
        }
    }


    protected function _after_insert($data, $options) {
            parent::_after_insert($data, $options);
            $prModel = M('role_pri');
            $pri_ids = I("post.pri_id");
            foreach($pri_ids as $k => $v){
                $prModel->add(array(
                    'pri_id' => $v,
                    'role_id' => $data['id'],
                ));
            }
        }
}

