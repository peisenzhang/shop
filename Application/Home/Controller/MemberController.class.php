<?php

namespace Home\Controller;
use Think\Controller;
class MemberController extends Controller{
    //生成验证码
    public function chkcode(){
        $verify = new \Think\Verify(array(
            'fontSize' => 30,      //字体大小
            'length' => 2,         //验证码长度
            'useNoise' => True,   //关闭验证码杂点
        ));
        return $verify->entry();
    }
    //登录
    public function login(){
        if(IS_POST){
            $model = D('Admin/Member');
            if($data = $model->validate($model->_login_validate)->create()){
                if($model->login()){
                    $returnUrl = U('/');
                    $ru = session('returnUrl');
                    if($ru){
                        session('returnUrl', null);
                        $returnUrl = $ru;
                    }
                    $this->success('登录成功', $returnUrl);
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $this->display();
    }
   
    //注册
    public function regist(){
        if(IS_POST){
            $model = D('Admin/Member');
            if($model->create()){
                if($model->add()){
                    $this->success('注册成功！', U('Index/index'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        
        $this->display();
    }
    
    //退出
    public function logout(){
        $model = D('Admin/Member');
        $model->logout();
    }
    
    public function ajaxChkLogin(){
        if(session('m_id')){
            echo json_encode( array(
              'login' => 1,
              'user_name' => session('m_user_name'),
            ));
        }else{
            echo json_encode(array(
                'login' => 0
            ));
        }
    }
}

