<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class IndexController extends NavController {    
    public function index(){
	$gModel = D('Admin/Goods');
        //取出促销的商品
        $proData = $gModel->getPromoteGoods();
        //取出三个推荐的商品
        $goods_is_new = $gModel->getRecGoods('is_new');  //新品
        $goods_is_hot = $gModel ->getRecGoods('is_hot'); //热卖
        $goods_is_best = $gModel->getRecGoods('is_best'); //精品
        //取出首页楼层数据        
        $cModel = D('Admin/category');
        $floorData = $cModel->floorData();        
        $this->assign(array(
           'proData' => $proData,
            'goods_is_new' => $goods_is_new,
            'goods_is_hot' => $goods_is_hot,
            'goods_is_best' => $goods_is_best,
            'floorData' => $floorData,
        ));
        $this->assign(array(
           '_show_nav' => 1 
        ));
       $this->display();
    }
    public function goods(){ 
        //var_dump(session('m_level_id'));die;
        //接收商品的id
         $id = I('get.id');
        //根据id取出这件商品的详细信息
        $gModel = D('Admin/goods');       
        $goods = $gModel->find($id);
        //根据商品主分类id找出所有上级分类制作面包屑导航
        $cModel = D('Admin/Category');
        $catPath = $cModel->parentPath($goods['cat_id']);
        $count = count($catPath);
        for($i = $count-1; $i >= 0; $i--){
            $_catPath[] = $catPath[$i]['cat_name'];
        }
        
        //取出商品相册信息
        $gpModel = D('goods_pic');
        $goods_pic = $gpModel->where(array('goods_id' => array('eq', $id)))->select();
        //取出商品属性信息
        $gaModel = D('goods_attr');
        $goods_attr = $gaModel->alias("a")
                              ->field("a.*,b.attr_name,b.attr_type")
                              ->join("LEFT JOIN __ATTRIBUTE__ b ON b.id=a.attr_id")
                              ->where(array('a.goods_id' => array('eq', $id)))->select();
        foreach($goods_attr as $k => $v){
            if($v['attr_type'] == '唯一'){
                $attr_unique[] = $v;
            }else{
                $attr_mul[$v['attr_name']][] = $v;
            }
        }
        //取出这件商品所有的会员价格
        $mpModel = D('membrer_price');
        $mpData = $mpModel->alias("a")->field("a.price,b.level_name")
                ->join("LEFT JOIN __MEMBER_LEVEL__ b ON b.id=a.level_id")
                ->where(array('a.goods_id' => array('eq', $id)))->select();
       // var_dump($mpData);die;
        //var_dump($_catPath);die;
        $config = C('IMG_CONFIG');
        $viewPath = $config['viewPath'];
        $this->assign(array(
           '_show_nav' => 0,
            'catPath' => $_catPath,          //商品的上级分类
            'goods' => $goods,               //商品的详细信息
            'goods_pic' =>  $goods_pic ,     //商品相册信息
             'attr_unique' => $attr_unique,  //商品的唯一属性
            'attr_mul' => $attr_mul,         //商品的可选属性
            'mpData' => $mpData,            //商品的会员价格
            'viewPath' => $viewPath         //图片的根路径
        ));
        $this->display();
    }
    public function nav(){
        $id = I('get.id');
        $gModel = D('Admin/goods');
        $goods = $gModel->find($id);
        $cModel = D('Admin/Category');
        $catPath = $cModel->parentPath($goods['cat_id']);
        var_dump($catPath);
    }
    
    //处理浏览历史
    public function displayHistory(){
        $id = I('get.id');
        //echo $id;die;
        //判断cookie中display_history是否存在，存在反序列化并存入$data，不存在把空数组赋值给$data 
        $data = isset($_COOKIE['display_history']) ? unserialize($_COOKIE['display_history']) : array();
        array_unshift($data, $id);  //把最新浏览的商品id放到数组的第一个位置
        array_unique($data);   //数组去重
        if(count($data) > 6)
            array_slice ($data, 0, 6);    //只取数组前6个
        setcookie('display_history', serialize($data), time()+30*86400); //数组反序列化后存回cookie
        //再根据商品id取出商品的详细信息
        $gModel = D('Goods');
        //$data = implode(',', $data);
        $gData = $gModel->where(array(
            'is_on_sale' => '是',
            'id' => array('in', $data),
        ))->select();
        echo json_encode($gData);
        
    }
    
    public function ajaxGetMemberPrice(){
        $goodsId = I('get.goods_id');
        $model = D('Admin/goods');
        echo $model->getMemberPrice($goodsId);
    }
}