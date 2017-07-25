<?php

namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller{
    public function __construct() {
        parent::__construct();
        //判断登录
        if(!session('id')){
            $this->error('必须先登录！', U('Login/login'));
        }
        //检查是否有权访问正在访问的页面
        if(CONTROLLER_NAME == 'Index')
            return true;
        else{
            $prModel = D('privilege');
            $has = $prModel->checkPri();
            if(!$has)
                $this->error('无权访问!');
        }
        
        
    }
}
