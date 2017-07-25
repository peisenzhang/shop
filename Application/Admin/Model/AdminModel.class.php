<?php

namespace Admin\Model;
use Think\Model;
class AdminModel extends Model{
    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
        $data['password'] = md5($data['password']);
    }
    protected function _after_insert($data, $options) {
        parent::_after_insert($data, $options);
        $role_id = I('post.role_id');
        $raModel = D('role_admin');
        foreach($role_id as $k => $v){
            $raModel->add(array(
                'role_id' => $v,
                'admin_id' => $data['id'],
            ));
        }
    }
    
    protected function _before_update(&$data, $options) {
        parent::_before_update($data, $options);
        if($data['password'])
            $data['password'] = md5($data['password']);
        else
            unset($data['password']); //从表单中删除这个字段就不会修改这个字段了
        $role_id = I('post.role_id');
        $raModel = D('role_admin');
        $raModel->where(array('admin_id' => array('eq',$options['where']['id'])))->delete();
        foreach($role_id as $k => $v){
            $raModel->add(array(
                'role_id' => $v,
                'admin_id' => $options ['where']['id'],
            ));
        };
        
    }
    
    protected function _before_delete($options) {
        parent::_before_delete($options);
        $id = $options['where']['id'];
        if($id == 1){
            $this->error = "超级管理员无法删除！";
            return false;
        }
    }
    
    public function login(){
        //从该模型中获取用户名和密码
        $user_name = $this->user_name;
        $password = $this->password;
        //通过获娶到的用户名查询用户信息
        $user_info = $this->where(array('user_name' => array('eq', $user_name)))->find();
        if($user_info){//如果用户信息存在，验证密码，不存在直接返回用户名不存在的错误信息
            if($user_info['password'] == md5($password)){
                session('id', $user_info['id']);
                session('user_name', $user_name);
                return true;
            }else{
                $this->error = "密码错误！";
            }
        }else{
            $this->error = "用户名不存在";
        }
    }
    
    public function logout(){
        session(null);
    }
}

