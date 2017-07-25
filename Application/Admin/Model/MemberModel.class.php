<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model{
    protected $insertFields = array('user_name', 'password', 'cpassword', 'chkcode', 'must_click');
    protected $updateFields = array('id', 'user_name', 'password', 'cpassword');
    protected $_validate = array(
        array('must_click', 'require', '必须同意注册协议', 1, 'regex', 3),
        array('user_name', 'require', '用户名不能为空', 1, 'regex', 3),
        array('user_name', '1,30', '用户名的长度最长不能超过30个字符', 1, 'length', 3),
        array('password', 'require', '密码不能为空', 1, 'regex', 3),
        array('password', '6,20', '密码的长度必须在6-20个字符之间', 1, 'length', 3),
        array('cpassword', 'password', '两次密码输入不一致', 1, 'confirm', 3),
        array('user_name', '', '用户名已经存在', 1, 'unique', 3),
        array('chkcode', 'require', '验证码不能为空！', 1, 'regex', 3),
        array('chkcode', 'check_verify', '验证码不正确！', 1, 'callback', 3),
    );
    public $_login_validate = array(
      array('user_name', 'require', '用户名不能为空！'),
        array('password', 'require', '密码不能为空'),
        array('chkcode', 'require', '验证码不能为空'),
        array('chkcoede', 'check_verify', '验证码不正确！', 1, 'callback'),
    );
    function check_verify($code, $id=''){
        $verify = new \Think\Verify();
        $verify->check($code, $id);
    }
    public function login(){
        //从模型中获取用户名和密码
        $user_name = $this->user_name;
        $password = $this->password;
        //通过用户查询用户数据
        $user = $this->where(array('user_name' => array('eq', $user_name)))->find();
        if($user){  //判断用户数据是否存在
            //用户数据存在，判读密码是否正确
            if($user['password'] == md5($password)){
                //密码正确把用户id和用户名存入session
                session('m_id', $user['id']);
                session('m_user_name', $user['user_name']);
                //计算当前会员级别存session
                $mlModel = D('member_level');
                $level_id = $mlModel->field('id')->where(array(
                    'jifen_bottom' => array('elt', $user['jifen']),
                    'jifen_top' => array('egt', $user['jifen'])
                ))->find();
                session('m_level_id', $level_id['id']);
                //登陆后把cookie中购物车的数据存数据库并清除cookie
                $cModel = D('Admin/cart');
                $cModel->moveDataToDb();
                return True;
            }else{  //密码错误
                $this->error = "密码错误！";
                return false;
            }
        }else{
            $this->error = '用户名不存在！';
            return false;
        }
    }
    
    public function logout(){
        session(null);
    }
    
    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
        $data['password'] = md5($data['password']);
    }
}
    

