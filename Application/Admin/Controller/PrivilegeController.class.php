<?php

namespace Admin\Controller;
use Think\Controller;
class PrivilegeController extends Controller{
    //添加表单
    public function add(){
         $model = D('privilege');
        if(IS_POST){ //判断是否提交，提交添加，未提交显示模板  
            if($model->create(I('post.'), 1)){
                if($model->add()){
                    $this->success('添加成功！', U('lst'));
                    exit;
                }
            }
            $this->error('添加失败！失败原因：'.$model->getError());
        }

        //获取上级权限        
        $priSelect = $model->buildSelect('parent_id');
        $this->assign(array(
            'priSelect' => $priSelect,
        ));
        $this->display();    
    }
    
    //显示
    public function lst(){
        $model = D('privilege');
        $data = $model->getTree();
        $this->assign(array(
            'data' => $data,
              '_page_title' => '权限列表',
            '_page_btn_link' => U('add'),
            '_page_btn_name' => '添加权限'
        ));
        $this->display();
    }
    
    //修改
    public function edit(){
        $id = I('get.id');
        $model = D('privilege');
          if(IS_POST){ //判断是否提交，提交添加，未提交显示模板            
            if($model->create(I('post.'), 2)){
                if(false !== $model->save()){
                    $this->success('修改成功！', U('lst'));
                    exit;
                }
            }
            $this->error('添加失败！失败原因：'.$model->getError());
        }
        //查询原权限数据
        $priData = $model->find($id);
        $priSelect = $model->buildSelect('parent_id', $priData['parent_id']);
        $this->assign(array(
           'priData' => $priData, 
            'priSelect' => $priSelect,
        ));
        $this->display(); 
    }
    
    //删除
    public function del(){
        $id = I('get.id');
        $model = D('privilege');
        if(false !== $model->delete($id)){
            $this->success('删除成功！');
        }else{
            $this->error("删除失败，失败原因：".$model->getError());
        }
    }
    
    public function text(){
        $model = D('privilege');
        $data = $model->getBtns();
        var_dump($data);
    }
}

