<?php

namespace Admin\Controller;
use Think\Controller;
class CategoryController extends BaseController{
    //添加表单
    public function add(){
        $model = D('category');
        if(IS_POST){
            if($model->create(I('post.'), 1)){
                if($model->add()){
                    $this->success('添加成功！', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError);
        }
        
        $catSelect = $model->buildCatSelect('parent_id');
        $this->assign(array(
            'catSelect' => $catSelect,
            '_page_title' => '添加分类',
            '_page_btn_link' => U('lst'),
            '_page_btn_name' => '分类列表',
        ));
        $this->display();
    }
    
    //显示分类
    public function lst(){
        $model = D('category');
        $data = $model->getTree();
        //var_dump($data);
        $this->assign(array(
            'data' => $data,
            '_page_tile' => '分类列表',
            '_page_btn_link' => U('add'),
            '_page_btn_name' => '添加分类'
        ));
        $this->display();
    }
    
    //修改表单
    public function edit(){
        $id = I('get.id');
        $model = D('category');
        if(IS_POST){
            if($model->create(I('post.'), 2)){
                if(false !== $model->save()){
                    $this->success('修改成功!', U('lst'));
                    exit;
                }
            }
            $this->error = $model->getError();
        }
        
        //取该分类数据
        $data = $model->find($id);
        $child = $model->getChild($id); //获取该分类的子分类
        $child[] = $id;
        $catSelect = $model-> buildCatSelect('parent_id', $data['parent_id'], $child);
        $this->assign(array(
            'data' => $data,
            'catSelect' => $catSelect,
            'child' => $child,       
        ));
        $this->display();
    }
    
    //删除分类
    public function del(){
        $id = I('get.id');
        $model = D('category');
        if(false !== $model->delete($id))
            $this->success('删除成功！', U('lst'));
        else
            $this->error('删除失败！失败原因：'.$model->getError());
    }
}

