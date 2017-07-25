<?php
namespace Admin\Controller;
use Think\Controller;
class TypeController extends Controller{
    public function add(){
        if(IS_POST){
            $model = D('type');
            if($model->create(I('post.'), 1)){
                if($model->add()){
                    $this->success('添加成功！', U('lst'));
                    exit;
                }
            }
            $this->error('添加失败，失败原因：'.$model->getError());
        }
        
        $this->assign(array(
            '_page_title' => '添加类型',
            '_page_btn_link' => U('lst'),
            '_page_btn_name' => '类型列表',
        ));
        $this->display();
    }
    
    public function lst(){
        $model = D('type');
        $data = $model->select();
        $this->assign(array(
            'data' => $data,
           '_page_title' => '类型列表',
            '_page_btn_name' => '添加类型',
            '_page_btn_link' => U('add'),
        ));
        $this->display();
    }
    
    public function edit(){
        $id = I('get.id');
        $model = D('type');
        if(IS_POST){
            if($model->create(I('post.'), 2)){
                if(false !== $model->save()){
                    $this->success('修改成功！', U('lst'));
                    exit;
                }
            }
            $this->error('修改失败，失败原因：'.$model->getError());
        }
        
        $data = $model->find($id);
        $this->assign(array(
            'data' => $data,
            '_page_title' => '修改类型',
            '_page_btn_title' => '类型列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }
    
    public function del(){
        $id = I('get.id');
        $model = D('type');
        if(false !== $model->delete($id)){             
            $this->success('删除成功！', U('lst'));
        }
        else
            $this->error('删除失败，失败原因'.$model->getError ());
    }
}
