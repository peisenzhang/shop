<?php

namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller{
    //
    public function add(){
       if(IS_POST){
           $model = D('Admin/comment');
           if($model->create(I('post.'), 1)){
               if($data = $model->add()){                   
                   $this->success(array(
                       'face' => session('face'),
                       'user_name' => session('m_user_name'),
                       'addtime' => date('Y-m-d H:i:s'),
                       'content' => I('post.content'),
                       'star' => I('post.star'),                      
                   ), '', true);
                   exit;
               }
           }
           $this->error($model->getError(), '', true);
       }
    }
    
    public function ajaxGetComment(){
        $model = D('Admin/comment');
        $goods_id = I('get.id');
        $ret = $model->search($goods_id,3);
        echo json_encode($ret);
    }
    
    public function reply(){
        if(IS_POST)
        {
            $model = D('Admin/CommentReply');
            if($model->create(I('post.'), 1))
            {
                    if($model->add())
                            $this->success(array(
                                    'face' => session('face'),
                                    'username' => session('m_username'),
                                    'addtime' => date('Y-m-d H:i:s'),
                                    'content' => I('post.content'),
                            ), '', TRUE);
            }
            $this->error($model->getError());
        }
    }
    
    public function click_count(){
        $id = I('get.id');
        $model = D('comment');
        $model->where(array('id' => $id))->setInc('click_count');
        $click_count = $model->field('click_count')->where(array('id' => $id))->find();
        echo json_encode($click_count);
        
    }
}

