<?php

namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController{
    public function index(){
        $this->display();
    }
    public function top(){
        $this->display();
    }
    public function menu(){
        $model = D('privilege');
        $menu = $model ->getBtns();
        $this->assign(array(
            'menu' => $menu,
        ));
        $this->display();
    }
    public function main(){
        $this->display();
    }
}

