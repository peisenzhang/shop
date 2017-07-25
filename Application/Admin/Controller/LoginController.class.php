<?php

namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    public function checkCode(){
        $verify = new \Think\Verify(array(
            'fontSize' => 30,
            'length' => 2,
            'useNoise' => true,
        ));
        $verify->entry();
    }
    
    //为登录的表单定义一个验证规则
    public $_login_validate = array(
        array('user_name', 'require', '用户名不能为空', 1),
        array('password', 'require', '密码不能为空', 1),
        array('checkCode', 'require', '验证码不能为空'),
        array('checkCode', 'check_verify', '验证码错误!', 1, 'callback'),
    );
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);        
    }
    public function login(){
        if(IS_POST){
            $adModel = D('admin');
            if($adModel->validate($adModel->$_login_validate)->create()){
                if($adModel->login()){
                    $this->success('登录成功！', U('Index/index'));
                    exit;
                }                
            }
            $this->error($adModel->getError());
        }
        $this->display();
    }
    public function logout(){
        $model = D('admin');
        $model->logout();
        redirect('login');
    }
}

