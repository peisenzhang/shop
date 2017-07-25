<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model{
    protected $_validate = array(
        array('goods_name', 'require', '商品名称不能为空', 1),
        array('market_price', 'currency', '市场价格必须是货币类型', 1),
        array('shop_price', 'currency', '商品价格必须是货币类型', 1)
    );  
    
    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
        if($_FILES['logo']['error'] == 0){
            $ret = uploadOne('logo', 'Goods', array(
                array(70,70),
                array(130, 130),
                array(350, 350),
                array(700, 700)
            ));
            if($ret['ok'] == 1){
                $logo = array('logo', 'sm_logo', 'mid_logo', 'big_logo', 'mbig_logo');
                foreach($ret['images'] as $k => $v){
                    $data[$logo[$k]] = $v; 
                }
            }else{
                $this->error = $ret['error'];
            }
                    
//            $config = array(
//                'maxSize' => 1024*1024,
//                'exts' => array('jpg', 'jpeg', 'png', 'gif'),
//                'rootPath' => './Public/Uploads/',
//                'savePath' => 'Goods/'
//            );
//            $upload = new \Think\Upload($config);
//            $info = $upload->upload();
//            if(!$info){
//                $this->error = $upload->getError();
//                return false;
//            }else{
//                //拼原图路径和缩略图路径
//                $logo = $info['logo']['savepath'].$info['logo']['savename'];
//                $sm_logo = $info['logo']['savepath'].'sm_'.$info['logo']['savename'];
//                $mid_logo = $info['logo']['savepath'].'mid_'.$info['logo']['savename'];
//                $big_logo = $info['logo']['savepath'].'big_'.$info['logo']['savename'];
//                $mbig_logo = $info['logo']['savepath'].'mbig_'.$info['logo']['savename'];
//                
//                //实例化图片处理类
//                $img = new \Think\Image();
//                //打开要处理的图片
//                $img->open('./Public/Uploads/'.$logo);
//                //生成缩略图并保存
//                $img->thumb(700,700)->save('./Public/Uploads/'.$mbig_logo);
//                $img->thumb(350,350)->save('./Public/Uploads/'.$big_logo);
//                $img->thumb(130,130)->save('./Public/Uploads/'.$mid_logo);
//                $img->thumb(70,70)->save('./Public/Uploads/'.$sm_logo);
//                
//                //存入表单
//                $data['logo'] = $logo;
//                $data['sm_logo'] = $sm_logo;
//                $data['mid_logo'] = $mid_logo;
//                $data['big_logo'] = $big_logo;
//                $data['mbig_logo'] = $mbig_logo;  
//            }            
        }
        
        $data['addtime'] = date("Y-m-d H:i:s", time());
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);
    }
    
    protected function _after_insert($data, $options) {
        parent::_after_insert($data, $options);
        $id = $data['id'];
        
        //把会员价格插入到 会员价格 中间表中
        $price = I('post.price');
        $model = M('membrer_price');
        foreach($price as $k => $v){
            $_v = (float)$v;
            if($_v > 0){
              $model->add(array(
                'price' => $_v,
                'level_id' => $k,
                'goods_id' => $id,
            ));
            }          
        }
        
        //把商品相册插入到  商品相册 表中
        $pics = array();
        if($_FILES['pic']){
            //处理数组
            foreach($_FILES['pic'] as $k => $v){
                foreach($v as $k1 => $v1){
                    $pics[$k1][$k] = $v1;
                }
            }
            $_FILES = $pics;
            $gpModel = D('goods_pic');
            foreach($_FILES as $k => $v){
                if($v['error'] == 0){
                    $ret = uploadOne($k, 'Goods', array(
                        array(650, 650),
                        array(350, 350),
                        array(50, 50),
                    ));
                    if($ret['ok'] == 1){
                        $pic = array('pic', 'big_pic', 'mid_pic', 'sm_pic');
                        foreach($ret['images'] as $k1 => $v1){
                            $goods_pic[$pic[$k1]] = $v1;
                        }
                        $goods_pic['goods_id'] = $id;
                        $gpModel->add($goods_pic);
                    }
                }
            }
        }
        
        //把商品扩展分类 插入到 商品扩展分类 表中
        $ext_cat = I('post.ext_cat_id');
        $_ext_cat = array_unique($ext_cat); //去重
        $gcModel = D('goods_cat');
        foreach($_ext_cat as $k => $v){
            $gcModel->add(array(
                'cat_id' => $v,
                'goods_id' => $id,
            ));
        }
        
        //把 商品属性 插入到 商品-属性 中间表中
        $attr_value = I('post.attr_value');
        $gaModel = D('goods_attr');
        foreach($attr_value as $k => $v){
            $v = array_unique($v);
            foreach($v as $k1 => $v1){
                $gaModel->add(array(
                    'goods_id' => $id,
                    'attr_id' => $k,
                    'attr_value' => $v1
                ));
            }
        }
        
    }
        
    protected function _before_update(&$data, $options) {
        parent::_before_update($data, $options);
        $id = $options['where']['id'];
        //处理会员价格
        $mpModel = D('membrer_price');
        $price = I('post.price');
        //先删除原数据再添加
        $mpModel->where(array('goods_id' => array('eq', $id)))->delete();
         foreach($price as $k => $v){
            $_v = (float)$v;
            if($_v > 0){
              $mpModel->add(array(
                'price' => $_v,
                'level_id' => $k,
                'goods_id' => $id,
            ));
            }          
        }
                
        //处理商品图片
         if($_FILES['logo'] && $_FILES['logo']['error'] == 0){//             
             $ret = uploadOne('logo', 'Goods', array(
                array(70, 70),
                 array(130, 130),
                 array(350, 350),
                 array(700, 700),
             ));
             if($ret['ok'] == 1){
                 $logo = array('logo', 'sm_logo', 'mid_logo', 'big_logo', 'mbig_logo');
                 foreach($ret['images'] as $k => $v){
                     $data[$logo[$k]] = $v;
                 }
             }else{
                 $this->error = $ret['error'];
             }
                //取出原数据
                $old_logo = $this->field('logo', 'sm_logo', 'mid_logo', 'big_logo', 'mbig_logo')->find($id);
                //从硬盘上删除
                deleteImg($old_logo);  
            }
            
            //处理商品相册图片
            $pics = array();
            if($_FILES['pic']){
                //转化数组
                foreach($_FILES['pic'] as $k => $v){
                    foreach($v as $k1 => $v1){
                        $pics[$k1][$k] = $v1;
                    }
                }
                //把转化后的数组赋值给$_FILES,因为uploadOne只能从 $_FILES里面找图片
                $_FILES = $pics;
                $gpModel = D('goods_pic');
                //循环上传每个图片
                foreach($_FILES  as $k => $v){
                    if($v['error'] == 0){
                        $ret = uploadOne($k, 'Goods', array(
                            array(650, 650),
                            array(350, 350),
                            array(50, 50),
                        ));
                        if($ret['ok'] == 1){  //上传成功，把图片地址存入数据库
                            $gp = array();
                            $pic = array('pic', 'big_pic', 'mid_pic', 'sm_pic');
                            foreach($ret['images'] as $k1 => $v1){
                                $gp[$pic[$k1 ]] = $v1;
                            }
                            $gp['goods_id'] = $id;
                            $gpModel->add($gp);
                        }else{
                            $this->error = 'cuowu'.$gpModel->getError();
                        }
                    }
                }
            }
            
            //处理扩展分类数据
            $gc = I('ext_cat_id');
            $gcModel = D('goods_cat');
            $gcModel->where(array('goods_id' => array('eq', $id)))->delete();
            foreach($gc as $k => $v){
                $gcModel->add(array(
                    'cat_id' => $v,
                    'goods_id' => $id,
                ));
            }
            
            //处理商品属性
            $goods_attr_id = I('post.goods_attr_id');
            $attr_value = I('post.attr_value');
            $_i = 0;
            $gaModel = D('goods_attr');
            foreach($attr_value as $k => $v){
                foreach($v as $k1 => $v1){
                    if($goods_attr_id[$_i] == ''){
                        $gaModel->add(array(
                            'goods_id' => $id,
                            'attr_id' => $k,
                            'attr_value' => $v1,
                        ));
                    }else{
                        $gaModel->where(array('id' => array('eq', $goods_attr_id[$_i])))->setField('attr_value', $v1);
                    }
                    $_i++;
                }
            }
        //过滤    
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);
    }
        
    protected function _before_delete($options) {
        parent::_before_delete($options);
        $id = $options['where']['id'];
        
        //物理删除商品图片
        $logo = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($id);
        deleteImg($logo);
        
        //删除会员价格
        $mpModel = D('membrer_price');
        $mpModel->where(array('goods_id' => array('eq', $id)))->delete();
        
        //删除商品相册
        $gpModel = D('goods_pic');
        $goods_pic = $gpModel->where(array('goods_id' => array('eq', $id)))->select();
        deleteImg($goods_pic);  //物理删除
        $gpModel->where(array('goods_id' => array('eq', $id)))->delete();  //数据库删除
        
        //删除扩展分类数据
        $gcModel = D('goods_cat');
        $gcModel->where(array('goods_id' => array('eq', $id)))->delete();
        
        //删除商品-属性表中的数据
        $gaModel = D('goods_attr');
        $gaModel->where(array('goods_id' => array('eq', $id)))->delete();
        
        //删除库存量
        $gnModel = D('goods_number');
        $gnModel->where(array('goods_id' => array('eq', $id)))->delete();
        
    }
       
    public function search(){
        //搜索
        $where = array();  //空的where条件
        //商品名称
        $gn = I('get.gn'); //商品名称
        if($gn)
            $where['goods_name'] = array('like', "%$gn%");
        //商品价格
        $fp = I('get.fp');
        $tp = I('get.tp');
        if($fp && $tp)
            $where['shop_price'] = array('between', array($fp, $tp));
        if($fp)
            $where['shop_price'] = array('egt', $fp);
        if($tp)
            $where['shop_price'] = array('egt', $tp);
        //是否上架
        $ios = I('get.ios');
        if($ios)
            $where['is_on_sale'] = array('eq', $ios);
        //添加时间
        $fa = I('get.fa');
        $ta = I('get.ta');
        if($fa && $ta)
            $where['addtime'] = array('between', array($fa, $ta));
        if($fa)
            $where['addtime'] = array('egt', $fa);
        if($ta)
            $where['addtime'] = array('elt', $ta);
        //品牌
        $brand_id = I('get.brand_id');
        if($brand_id)
            $where['brand_id'] = array('eq', $brand_id);
        //商品分类
               
        $catId = I('get.cat_id');
        if($catId)
        {
                // 先查询出这个分类ID下所有的商品ID
                $gids = $this->getGoodsIdByCatId($catId);
                // 应用到取数据的WHERE上
                $where['id'] = array('in', $gids);
        }
        
        //排序
        $order_by = 'id';
        $order_way = 'desc';
        $odby = I('get.odby');
        switch($odby){
            case 'id_asc':
                $order_way = 'asc'; 
                break;
            case 'price_asc':
                $order_by = 'shop_price';
                break;
            case 'price_desc':
                $order_by = 'a.shop_price';
                $order_way = 'desc';
                break;                   
        };
        
        //翻页
        $count = $this->where($where)->count(); //取出总的记录数
        $Page = new \Think\Page($count, 5);
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $show = $Page->show();
        $list = $this->field('a.*,b.brand_name,c.cat_name,GROUP_CONCAT(e.cat_name) ext_cat_name')
                ->alias('a')
                ->join("LEFT JOIN __BRAND__ b ON b.id=a.brand_id
                        LEFT JOIN __CATEGORY__ c ON c.id=a.cat_id
                        LEFT JOIN __GOODS_CAT__ d ON d.goods_id=a.id
                        LEFT JOIN __CATEGORY__ e ON e.id=d.cat_id")
                ->GROUP("a.id")
                ->where($where)
                ->order("$order_by $order_way")
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        return array(
            'show' => $show,
            'list' => $list
        );
        
    }
    
    public function getGoodsIdByCatId($catId){
        //在主分类中取出商品id
        $catModel = D('Admin/category');
        $child = $catModel ->getChild($catId);//找该分类的子分类
        $child[] = $catId;
        $gid = $this->field("id")->where(array('id' => array('in', $child)))->select();
        //在扩展分类中取出该分类的商品id
        $gcModel = D('goods_cat');
        $gid1 = $gcModel->field(" DISTINCT goods_id  id")->where(array('cat_id' => array('eq', $catId)))->select();        
        if($gid && $gid1)
            $gid = array_merge($gid, $gid1);
        elseif($gid1)
            $gid = $gid1;
        //二维数组转一维
        $id = array();
        foreach($gid as $k => $v){
            $id[] = $v['id'];
        }
        
        //返回id
        return $id;
    }
    
    /**
     * 取出当前正在促销的商品
     * @param type $limit
     */
    public function getPromoteGoods($limit = 5){
        return $this->field("id, goods_name, mid_logo, promote_price")
             ->where(array(
                 'is_on_sale' => array('eq', '是'),
                 'promote_price' => array('gt', 0),
                 'promote_start_date' => array('elt', date('Y-m-d H:i')),
                 'promote_end_date' => array('egt', date('Y-m-d H:i'))
                ))
             ->limit($limit)
             ->select();
    }
    
    /**
     * 取出三种推荐数据的商品
     * @param type $recType
     * @param type $limit
     * @return type
     */
    public function getRecGoods($recType, $limit=5){
        return $this->field("id, goods_name, mid_logo, shop_price")
        ->where(array(
            'is_on_sale' =>  array('eq', '是'),
            "$recType" => array('eq', '是')
        ))->select();
    }
    
    //取出商品的会员价格
    public function getMemberPrice($goodsId){  
        $level_id = session('m_level_id');
        //取出商品促销价格
        $promote_price = $this->field('promote_price')->where(array(
            'promote_price' => array('gt', 0),
            'promote_start_date' => array('elt', date('Y-m-d H:i')),
            'promote_end_date' => array('egt', date('Y-m-d H:'))
        ))->find($goodsId);
        $_promote_price = $promote_price['promote_price'];
        //取出商品本店价格
        $price = $this->field('shop_price')->find($goodsId);
        $_price = $price['shop_price'];
        if(session('m_level_id')){  //判断是否登录
            $mpModel = D('membrer_price');
            //取出会员价格
            $mp_price = $mpModel->field('price')->where(array(
                'good_id' => array('eq', $goodsId),
                'level_id' => array('eq', $level_id)
                ))->find();
            $_mp_price = $mp_price['price'];
            if($mp_price['price']){//判断会员价格是否存在
                return $_promote_price ? min($_mp_price, $_promote_price) : $_mp_price;
                }else{                  
                  return $promote_price['promote_price'] ? min($_shop_price, $_promote_price) : $_price;
                }                   
        }else{//没有登录
            return $_promote_price ? min($_promote_price, $_price) : $_price;
        }
    }
    
    public function cat_search($catId, $pageSize){
        //根据分类id搜索出来这个分类下的商品id
        $goodsId = $this->getGoodsIdByCatId($catId);
       $where['a.id'] = array('in', $goodsId);
//        //根据品牌搜索
        $brand = I('a.get.brand_id');
        if($brand_id)
            $where['a.brand_id'] = array('eq', $brand_id);
        //根据价格搜索
        $price = I('get.price');
        if($price)
            $where['a.shop_price'] = array('between', explode ('-', $price));
         //根据属性搜索
        $gaModel = D('goods_attr');
        $attrGoodsId = null;
        foreach($_GET  as $k => $v){
            if(strpos($k, 'attr_') === 0){
                $attrId = str_replace('attr_', '', $k);
                $attrName = strrchr($v, '-');
                $attrValue = str_replace($attr_name, '', $v);
                $goodsId = $gaModel->field("GROUP_CONCAT(goods_id) gids")->where(array(
                    'attr_id' => array('eq', $attrId),
                    'attr_value' => array('eq', $attrValue)
                ))->find();
                if($goodsId['gids']){  //商品id存在
                    if($attrGoodsId === null){  //第一次直接存
                        $attrGoodsId = explode(',', $goodsId['gids']);
                    }else{
                        $attrGoodsId = array_intersect($attrGoodsId, $goodsId);//取交集
                        if(empty($attrGoodsId)){
                            $where['a.id'] = array();
                            break;
                        }
                    }
                }else{
                    //前几次的交集结果清空
                    $attrGoodsId = array();
                    $where['a.id'] = array('eq', 0);
                    break;
                }  
            }
        }
          if($attrGoodsId)
              $where['a.id'] = array('in', $attrGoodsId);
          
          //排序
          $odby = I('get.odby');
          $order_way = 'desc';
          $order_by = 'X1';
         if($odby){
             if($odby == 'adddtime')
                 $order_by = 'a.addtime';
             if(strpos($odby, 'price_') === 0){
                 $order_by = 'a.shop_price';
                 if($odby = 'price_asc')
                         $order_way = 'asc';
             }
         }
          
          //翻页
//          $count = $this->alias("a")->where($where)->count(); 
         $count = $this->field("count(a.id) goods_count, GROUP_CONCAT(a.id) goods_id")->alias("a")->where($where)->find();
         $goods_id = explode(',', $count['goods_id']);
          $Page = new \Think\Page($count['goods_count'], $pageSize);
          $show = $Page->show();
          $list = $this->alias('a')
                  ->field("a.id, a.goods_name, a.shop_price, a.mid_logo, sum(b.goods_number) x1")
                  ->join("LEFT JOIN __GOODS_ORDER__ b ON (b.goods_id=a.id AND b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status='是'))")
                  ->where($where)
                  ->group("a.id")
                  ->order("$order_by $order_way")
                  ->limit($Page->firstRow.','.$Page->listRows)
                  ->select();
          return array(
              'show' => $show,
              'list' => $list,
              'goods_id' => $goods_id,
          );
    }
    
     public function key_search($key, $pageSize=60){
       //根据关键字搜索商品
         $goodsId = $this->alias("a")
                 ->field("GROUP_CONCAT(a.id) goods_id")
                 ->join("LEFT JOIN __GOODS_ATTR__ b ON b.goods_id=a.id")
                 ->where(array(
                     'a.is_on_sale' => array('eq', '是'),
                     'a.goods_name' => array('exp', "LIKE '%$key%' OR a.goods_desc LIKE '%$key%' OR b.attr_value LIKE '%$key%'")
                 ))->find();
       $where['a.id'] = array('in', $goodsId);
//        //根据品牌搜索
        $brand = I('a.get.brand_id');
        if($brand_id)
            $where['a.brand_id'] = array('eq', $brand_id);
        //根据价格搜索
        $price = I('get.price');
        if($price)
            $where['a.shop_price'] = array('between', explode ('-', $price));
         //根据属性搜索
        $gaModel = D('goods_attr');
        $attrGoodsId = null;
        foreach($_GET  as $k => $v){
            if(strpos($k, 'attr_') === 0){
                $attrId = str_replace('attr_', '', $k);
                $attrName = strrchr($v, '-');
                $attrValue = str_replace($attr_name, '', $v);
                $goodsId = $gaModel->field("GROUP_CONCAT(goods_id) gids")->where(array(
                    'attr_id' => array('eq', $attrId),
                    'attr_value' => array('eq', $attrValue)
                ))->find();
                if($goodsId['gids']){  //商品id存在
                    if($attrGoodsId === null){  //第一次直接存
                        $attrGoodsId = explode(',', $goodsId['gids']);
                    }else{
                        $attrGoodsId = array_intersect($attrGoodsId, $goodsId);//取交集
                        if(empty($attrGoodsId)){
                            $where['a.id'] = array();
                            break;
                        }
                    }
                }else{
                    //前几次的交集结果清空
                    $attrGoodsId = array();
                    $where['a.id'] = array('eq', 0);
                    break;
                }  
            }
        }
          if($attrGoodsId)
              $where['a.id'] = array('in', $attrGoodsId);
          
          //排序
          $odby = I('get.odby');
          $order_way = 'desc';
          $order_by = 'X1';
         if($odby){
             if($odby == 'adddtime')
                 $order_by = 'a.addtime';
             if(strpos($odby, 'price_') === 0){
                 $order_by = 'a.shop_price';
                 if($odby = 'price_asc')
                         $order_way = 'asc';
             }
         }
          
          //翻页
//          $count = $this->alias("a")->where($where)->count(); 
         $count = $this->field("count(a.id) goods_count, GROUP_CONCAT(a.id) goods_id")->alias("a")->where($where)->find();
         $goods_id = explode(',', $count['goods_id']);
          $Page = new \Think\Page($count['goods_count'], $pageSize);
          $show = $Page->show();
          $list = $this->alias('a')
                  ->field("a.id, a.goods_name, a.shop_price, a.mid_logo, sum(b.goods_number) x1")
                  ->join("LEFT JOIN __GOODS_ORDER__ b ON (b.goods_id=a.id AND b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status='是'))")
                  ->where($where)
                  ->group("a.id")
                  ->order("$order_by $order_way")
                  ->limit($Page->firstRow.','.$Page->listRows)
                  ->select();
          return array(
              'show' => $show,
              'list' => $list,
              'goods_id' => $goods_id,
          );
    }
    
    public function goods_search($goodsId, $pageSize=60){      
       $where['a.id'] = array('in', $goodsId);
//        //根据品牌搜索
        $brand = I('get.brand_id');
        if($brand_id)
            $where['a.brand_id'] = array('eq', $brand_id);
        //根据价格搜索
        $price = I('get.price');
        if($price)
            $where['a.shop_price'] = array('between', explode ('-', $price));
         //根据属性搜索
        $gaModel = D('goods_attr');
        $attrGoodsId = null;
        foreach($_GET  as $k => $v){
            if(strpos($k, 'attr_') === 0){
                $attrId = str_replace('attr_', '', $k);
                $attrName = strrchr($v, '-');
                $attrValue = str_replace($attr_name, '', $v);
                $goodsId = $gaModel->field("GROUP_CONCAT(goods_id) gids")->where(array(
                    'attr_id' => array('eq', $attrId),
                    'attr_value' => array('eq', $attrValue)
                ))->find();
                if($goodsId['gids']){  //商品id存在
                    if($attrGoodsId === null){  //第一次直接存
                        $attrGoodsId = explode(',', $goodsId['gids']);
                    }else{
                        $attrGoodsId = array_intersect($attrGoodsId, $goodsId);//取交集
                        if(empty($attrGoodsId)){
                            $where['a.id'] = array();
                            break;
                        }
                    }
                }else{
                    //前几次的交集结果清空
                    $attrGoodsId = array();
                    $where['a.id'] = array('eq', 0);
                    break;
                }  
            }
        }
          if($attrGoodsId)
              $where['a.id'] = array('in', $attrGoodsId);
          
          //排序
          $odby = I('get.odby');
          $order_way = 'desc';
          $order_by = 'X1';
         if($odby){
             if($odby == 'adddtime')
                 $order_by = 'a.addtime';
             if(strpos($odby, 'price_') === 0){
                 $order_by = 'a.shop_price';
                 if($odby = 'price_asc')
                         $order_way = 'asc';
             }
         }
          
          //翻页
//          $count = $this->alias("a")->where($where)->count(); 
         $count = $this->field("count(a.id) goods_count, GROUP_CONCAT(a.id) goods_id")->alias("a")->where($where)->find();
         $goods_id = explode(',', $count['goods_id']);
          $Page = new \Think\Page($count['goods_count'], $pageSize);
          $show = $Page->show();
          $list = $this->alias('a')
                  ->field("a.id, a.goods_name, a.shop_price, a.mid_logo, sum(b.goods_number) x1")
                  ->join("LEFT JOIN __GOODS_ORDER__ b ON (b.goods_id=a.id AND b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status='是'))")
                  ->where($where)
                  ->group("a.id")
                  ->order("$order_by $order_way")
                  ->limit($Page->firstRow.','.$Page->listRows)
                  ->select();
          return array(
              'show' => $show,
              'list' => $list,
              'goods_id' => $goods_id,
          );
    }
}

