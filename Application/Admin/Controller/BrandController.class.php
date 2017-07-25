<?php

namespace Admin\Controller;
use Think\Controller;
class BrandController extends Controller{
    //添加品牌
    public function add(){
        if(IS_POST){  //判读是否提交表单
            $model = D('brand'); //实例化数据模型
            if($model->create(I('post.'),1)){ //创建数据
                if($model->add()){     //添加数据
                    $this->success('添加成功！', U('lst')); //成功提示
                    exit; //添加成功终止脚本运行
                }
            }
            $this->error($model->getError());  //如果没有成功添加数据显示错误信息
        }
        //赋值
        $this->assign(array(
            '_page_title' => '品牌添加',
            '_page_btn_link' => U('lst'),
            '_page_btn_name' => '品牌列表',
        ));
        //显示模板
        $this->display();
    }
    
    //显示品牌
    public function lst(){
        $model = D('brand');
        $data = $model->serach(2);
        //var_dump($data);die;
        $this->assign($data);
        $this->assign(array(
           '_page_title' => '品牌列表',
            '_page_btn_link' => U('add'),
            '_page_btn_name' => '品牌添加',
        )); 
        $this->display();
    }
    
    //修改表单
    public function edit(){
        //接收要修改的id
        $id = I('get.id');
        //实例化数据模型
        $model = D('brand');
        //判读是否提交表单
        if(IS_POST){
            //创建数据
            if($data = $model->create(I('post.'),2)){                
                //修改数据
                if(FALSE != $model->save()){                    
                    $this->success('修改成功!', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        //查询原来数据
        $data = $model->find($id);
        //赋值
        $this->assign(array(
            'bData' => $data,
            '_page_title' => '品牌修改',
            '_page_btn_link' => U('add'),
            '_page_btn_name' => '品牌添加',
        ));
        //显示表单
        $this->display();
    }
    
    //删除
    public function del(){
        $id = I('get.id');
        //echo $id;die;
        $model = D('brand');        
        if(FALSE !== $model->delete($id)){
            $this->success('删除成功', U('lst/?time()'));
        }else{
            $this->error('删除失败！失败原因：'.$model->getError());
        }
    }
    
}

