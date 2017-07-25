<?php
namespace Admin\Model;
use Think\Model;
class CommentModel extends Model{
    protected $insertFields = 'star,content,goods_id';
    protected $_validate = array(
      //array('goods_id', 'require', '参数错误', 1),
      array('star', '1,2,3,4,5', '分值只能是1-5之间的数字', 1, 'in'),
      array('content', '1,200', '不能超过200个字符', 1, 'length')
    );
    
    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
        if(!session('m_id')){
            $this->error = "必须先登录";
            return false;
        }
        $data['member_id'] = session('m_id');
        $data['addtime'] = date("Y-m-d H:i:s");
    }
    
    protected function _after_insert($data, $options) {
        parent::_after_insert($data, $options);
        $yx_id = I('post.yx_id');
        $yx_name = I('post.yx_name');
        $yx_name = str_replace('，', ',', $yx_name);
        $yx_name = explode(',', $yx_name);
        $yxModel = D('yinxiang');      
        if($yx_id){
            foreach($yx_id as $k => $v){
                $yxModel->where(array('id' => array('eq', $v)))->setInc('yx_count');
            }
        }
        foreach($yx_name as $k => $v){  //处理新添加的印象
            $v = trim($v);
            if(empty($v))
                continue;
            $has = $yxModel->where(array(
                'yx_name' => array('eq', $v),
                'goods_id' => array('eq', $data['goods_id'])
            ))->find();
            if($has){
                $yxModel->where(array('id' => array('eq', $has['id'])))->setInc('yx_count');
            }else{
                $yxModel->add(array(
                    'yx_name' => $v,
                    'yx_count' => 1,
                    'goods_id' => $data['goods_id'],
                ));
            }
        }
    }
    
    //
    public function search($goodsId, $pageSize){
        $where['a.goods_id'] = array('eq', $goodsId);
        
        //取出总的记录数
        $count = $this->alias('a')->where($where)->count();
        $pageCount = ceil($count / $pageSize); //计算总的页数
        $currentPage = max(1, (int)I('get.p'));  //取出当前页
        $offset = ($currentPage-1)*$pageSize;    //计算limit第一参数：偏移量
         
        //取数据
        if($currentPage == 1){
            $star = $this->alias("a")->field("a.star")->where($where)->select();
            $hao = $zhong = $cha = 0;
            foreach($star as $k => $v){
                if($v['star'] == 3)
                    $zhong++;
                elseif($v['star'] > 3)
                    $hao++;
                else
                    $cha++;
            }
            $total = $hao + $zhong + $cha;
            $hao = round(($hao / $total)*100, 2);
            $zhong = round(($zhong / $total)*100, 2);
            $cha = round(($cha / $total)*100, 2);
            
            //取印象
            $yxModel = D('Admin/yinxiang');
            $yxData = $yxModel->where(array('goods_id' => array('eq', $goodsId)))->select();
        }
        $data = $this->alias("a")
                ->field("a.id, a.content, a.star, a.click_count, a.addtime, b.user_name, b.face, count(c.id) reply_count")
                ->join('LEFT JOIN __MEMBER__ b ON b.id=a.member_id
                        LEFT JOIN __COMMENT_REPLY__ c ON c.comment_id=a.id')
                ->where($where)
                ->group("a.id")
                ->order("a.id desc")
                ->limit("$offset, $pageSize")
                ->select();  
        $crModel = D('comment_reply');
        foreach($data as $k => $v){
            $data[$k]['reply']  = $crModel->alias("a")
                    ->field("a.content,a.addtime,b.user_name,b.face")
                    ->join("LEFT JOIN __MEMBER__ b ON b.id=a.member_id")
                    ->where(array(
                        'a.comment_id' => array('eq', $v['id']),
                    ))
                    ->order("a.id desc")
                    ->select(); 							          
        }
        return array(
            'page' => $pageCount,   //页码
            'list' => $data,        //数据
            'hao' => $hao,
            'zhong' => $zhong,
            'cha' => $cha,
            'yxData' => $yxData,
            'member_id' => session('m_id'),
        );
    }
}