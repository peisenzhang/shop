<?php

namespace Admin\Controller;
use Think\Controller;
class RoleController extends Controller{
    //添加
    public function add(){
        if(IS_POST){
           // var_dump($_POST);DIE;
            $model = D('role');
            $pri_id = I('post.pri_id');
            if($model->create(I('post.'), 1)){
                if($id = $model->add()){ 
                    $this->success('添加成功！', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        
        //取出所有的权限复选框
        $prModel = D('privilege');
        $priCheck = $prModel->buildCheckbox();
        $this->assign(array(
            'priCheck' => $priCheck,
        ));
        $this->display();
    }
 
    
    //显示
    public function lst(){
        $rModel = D('role');
        $data = $rModel->alias('a')
                ->field("a.*,GROUP_CONCAT(c.pri_name) pri_name")
                ->join("LEFT JOIN __ROLE_PRI__ b ON b.role_id=a.id
                        LEFT JOIN __PRIVILEGE__ c ON c.id=b.pri_id")
                ->group("a.id")
                ->select();
        $this->assign(array(
            'data' => $data,
        ));
        $this->display();
    }
    
    //修改
    public function edit(){
        $id = I('get.id');
        $model = D('role');
        if(IS_POST){ 
            if($model->create(I('post.'), 2)){
                if(FALSE !== $model->save()){       
                    $this->success('修改成功！', U('lst'));
                    exit;
                }
            }
            $this->error('修改失败！失败原因：'.$model ->getError());
        }
        
        //取出修改前的数据
        $rData = $model->alias("a")
                ->field("A.*,GROUP_CONCAT(b.pri_id) pri_id")
                ->join('LEFT JOIN __ROLE_PRI__ b ON b.role_id=a.id')
                ->find($id);
         //取出所有的权限复选框
        $prModel = D('privilege');
        $priCheck = $prModel->buildCheckbox(explode(',', $rData['pri_id']));
        
        $this->assign(array(
            'rData' => $rData,
            'priCheck' => $priCheck,
        ));
        $this->display();
    }
    
    //删除
    public function del(){
        $id = I('get.id');
        $model = D('role');        
        if(false !== $model->delete($id)){
            $this->success('删除成功！', U('lst'));
        }else{
            $this->error('删除失败！失败原因：'.$model->getError());
        }
    }
}
