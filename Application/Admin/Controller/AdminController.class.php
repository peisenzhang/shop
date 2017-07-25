<?php

namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller{
    //添加
    public function add(){
        if(IS_POST){
            $model = D('admin');
            var_dump($_POST);
            if($model->create(I('post.'), 1)){
                if($model->add()){
                    $this->success('添加成功！', U('lst'));
                    exit;
                }
            }
            $this->error("添加失败！失败原因：".$model->getError());
        }
        
        //取出所有的角色
        $rModel = D('role');
        $rData = $rModel->select();
        $this->assign(array(
           'rData' => $rData, 
        ));
        $this->display();
    }
    
    //显示
    public function lst(){
        $model = D('admin');
        $aData = $model->alias("a")
                ->field("a.*,GROUP_CONCAT(c.role_name) role_name")
                ->join("LEFT JOIN __ROLE_ADMIN__ b ON b.admin_id=a.id
                        LEFT JOIN __ROLE__ c ON c.id=b.role_id")
                ->group("a.id")
                ->select();
        $this->assign(array(
            'aData' => $aData,
        ));
        $this->display();
    }
    
    //修改
    public function edit(){
        $id = I('get.id');
        $model = D('admin');
        if(IS_POST){
            if($model->create(I('post.'), 2)){
                if(false !== $model->save()){
                    $this->success('xiugaichegng!', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        
        //取出原数据
        $data = $model->alias("a")
                ->field("a.*,GROUP_CONCAT(b.role_id) role_id")
                ->join("LEFT JOIN __ROLE_ADMIN__ b ON b.admin_id=a.id")
                ->group("a.id")
                ->find($id);
        //取出角色
        $rModel = D('role');
        $rData = $rModel->select();
        //取出管管理员拥有的角色
        $raModel = D('role_admin');
        $role_id = $raModel->field('role_id')->where(array('admin_id' => array('eq', $id)))->select();
        $role_id = explode(',', $role_id);        
        $this->assign(array(
            'data' => $data,
            'rData' => $rData,
            'role_id' => $role_id, 
        ));
        $this->display();
    }
    
    //删除
    public function del(){
        $id = I('get.id');
        $model = D('admin');
        if(false !== $model->delete($id))
            $this->success("删除成功！", U('lst'));
        else
            $this->error("删除失败！失败原因：".$model->getError ());
    }
}

