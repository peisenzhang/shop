<?php

namespace Admin\Controller;
use Think\Controller;
class MemberLevelController extends Controller{
    //添加表单
    public function add(){
        if(IS_POST){
            $model = D('member_level');    
            if($data = $model->create(I('post.'),1)){ //创建数据                
                if($model->add()){
                    $this->success('添加成功！', U('lst?time=time()'));
                    exit;
                }
            }
            $this->error('添加失败：'.$model->getError());
        }
        $this->assign(array(
            '_page_title' => '添加级别',  
            '_page_btn_link' => U('lst'),
            '_page_btn_name' => '级别列表',
        ));
        $this->display();
    }
    
    //显示表单
    public function lst(){
        $model = D('member_level');
        $data = $model->select();
        $this ->assign(array(
            'data' => $data,
             '_page_title' => '级别列表',  
            '_page_btn_link' => U('add'),
            '_page_btn_name' => '添加级别',
        ));
        
        $this->display();
    }
    
    //修改表单
    public function edit(){
        $id = I('get.id');
        $model = D('member_level');
        $data = $model->find($id);
        if(IS_POST){
            if($model->create(I('post.'), 2)){
                if(FALSE != $model->save()){
                    $this->success('修改成功！', U('lst'));
                    exit;
                }
            }
        }
        $this->assign(array(
            'data' => $data,
             '_page_title' => '修改级别',  
            '_page_btn_link' => U('lst'),
            '_page_btn_name' => '级别列表',
        ));
        $this->display();
    }
    
    //删除
    public function del(){
        $id = I('get.id');
        $model = D('member_level');
        if(FALSE != $model->delete($id)){
            $this->success('删除成功！', U('lst'));
        }  else {
            $this->error('删除失败！失败原因：'.$model->getErrot());
        }
    }
}
