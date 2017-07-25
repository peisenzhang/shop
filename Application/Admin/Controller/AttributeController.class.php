<?php
namespace Admin\Controller;
use Think\Controller;
class AttributeController extends Controller{
    public function add(){
        if(IS_POST){
            $model = D('attribute');
            if($model->create(I('post.'), 1)){
               if($model ->add()){
                   $this->success('添加成功！', U('lst?type_id='.I('get.type_id')));
                   exit;
               }
            }
            $this->error('添加失败！失败原因：'.$model ->getError());
        }
        
        $this->assign(array(
           '_page_title' => '添加属性',
            '_page_btn_name' => '属性列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }
    
    public function lst(){
        $model = D('attribute');
        $data = $model->search();
        $this->assign($data);
        $this->assign(array(
            '_page_title' => '属性列表',
            '_page_btn_name' => '添加属性',
            '_page_btn_link' => U('add?type_id='.I('get.type_id')),
        ));
        $this->display();
    }
    
    public function edit(){
        $id = I('get.id');
        $model = D('attribute');
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
           '_page_title' => '修改属性',
            '_page_btn_name' => '属性列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }
    
    public function del(){
        $id = I('get.id');
        $model = D('attribute');
        if(false !== $model->delete($id)){
            $this->success('删除成功', U('lst'));
        }else{
            $this->error('删除失败！失败原因：'.$model->getError());
        }
        
    }
}
