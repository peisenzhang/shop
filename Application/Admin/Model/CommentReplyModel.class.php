<?php
namespace Admin\Model;
use Think\Model;
class CommentReplyModel extends Model{
    protected $insertFields = 'content,comment_id';
    protected $_validate = array(
      array('comment_id', 'require', '参数错误', 1),
      array('content', '1,200', '内容必须在1-200个字符之间', 1, 'length'),
    );
    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
        $member_id = session('m_id');
        if(!$member_id){
            $this->error = "必须先登录";
            return false;
        }
        $data['member_id'] = $member_id;
        $data['addtime'] = date("Y-m-d H:i:s");
    }
}