<?php

namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model{
    
    protected function _before_delete($options) {
        parent::_before_delete($options);
        $id = $options['where']['id'];
        $model = D('category');
        $children = $model->getChild($id);
        if($children){
            $children = implode(',', $children);
//            $model = new \Think\Model();
//            $model->table('__CATEGORY__')->delete($children);
            $options['where']['id'] = array(
                '0' => 'IN',
                '1' => implode(',', $children)
            );
            
        }
        //implode(',', $children);
    }
    public function getChild($catId){
        $data = $this->select();
        return $this->_getChild($data, $catId);
    }
    //找一个分类的子分类
    private function _getChild($data, $catId, $isClear = false){
        static $ret = array();
        if($isClear == true)
            $ret = array();
        foreach($data as $k => $v){
            if(in_array($v['id'], $ret))
                    continue;
            if($v['parent_id'] == $catId){
                $ret[] = $v['id'];
                $this->_getChild($data, $v['id']);
            }
        }
        return $ret;
}

    public function getTree(){
        $data = $this->select();
        return $this->_getTree($data);
    }
    //打印树形结构
    private function _getTree($data, $parentId = 0, $level = 0, $html = '--'){
        static $ret = array();
        foreach($data as $k => $v){
            if(in_array($v['id'], $ret['id']))
                    continue;
            if($v['parent_id'] == $parentId){
                $v['level'] = $level;
                $v['html'] = str_repeat($html, 8*$level);
                $ret[] = $v;
                $ret['id'][] = $v['id'];
                $this->_getTree($data, $v['id'], $level+1);
            }                    
        }
        return $ret;
    }

    //构造分类下拉框
    public function buildCatSelect($selectName, $selectValue = '', $child=array()){
        $cData = $this->getTree();
        $select ="";
        $select = "<select name='".$selectName."'><option value='0'>顶级分类</option>";
        foreach($cData as $k => $v){
            if($child && in_array($v['id'], $child))
                    continue;
            if($selectValue && $v['id'] == $selectValue)
                $selected = 'selected="selected"';
            else
                $selected = '';
            $select .= "<option ".$selected."value='".$v['id']."'>".$v['html'].$v['cat_name']."</option>";
        }
        $select .= "</select>";
        return $select;
    }
    
    //取出前三级的分类数据,用于首页导航
    public function getNavData(){
        //取出所有分类
        $data = $this->select();
        $ret = array();
        $id = array();    //用于保存不需要再遍历的数据id
        //循环所有分类找出顶级分类
        foreach($data as $k => $v){
            if(in_array($v['id'], $id))
                       continue;//如果已经保存的不需要再遍历了
            if($v['parent_id'] == 0){
                //循环所有分类找出这个顶级分类的子分类
                foreach($data as $k1 => $v1){
                    if(in_array($v1['id'], $id))
                            continue;//如果已经保存的不需要再遍历了
                    if($v1['parent_id'] == $v['id']){
                        //循环所有分类找出这个二级分类找出子分类
                        foreach($data as $k2 => $v2){
                            if(in_array($v['id'], $id))
                                 continue;//如果已经保存的不需要再遍历了
                            if($v2['parent_id'] == $v1['id']){
                                $v1['children'][] = $v2;
                                $id[] = $v2['id'];
                            }
                        }
                        $v['children'][] = $v1;
                        $id[] = $v1['id'];
                }
                }
                $ret[] = $v;
                $id[] = $v['id'];
            }
        }
        return $ret;
    }
    
    //取出推荐楼层的数据
    public function floorData(){
        //取出推荐的顶级分类
        $ret = $this->where(array(
            'is_floor' => array('eq', '是'),
            'parent_id' => array('eq', 0)
        ))->select();
        $gModel = D('Admin/Goods');
        //循环每个楼层取出楼层中的数据
        foreach($ret as $k => $v){
            //先取出这个楼层中的所有商品id
            $goodsId = $gModel->getGoodsIdByCatId($v['id']);
            //再取出这些商品所用到的品牌数据
            $ret[$k]['brand'] = $gModel->alias("a")
                    ->join("LEFT JOIN __BRAND__ b ON b.id=a.brand_id")
                    ->where(array('a.id' => array('in', $goodsId), 'a.brand_id' => array('neq', 0)))
                    ->limit(9)
                    ->select();
            //取出未推荐的二级分类并保存到这个顶级分类的subCat字段中
            $ret[$k]['subCat'] = $this->where(array(
                'parent_id' => array('eq', $v['id']),
                'is_floor' => array('eq', '否'),
                ))->select();
            //取出推荐的二级分类并保存到这个顶级分类的recSubCat字段中
            $ret[$k]['recSubCat'] = $this->where(array(
                'parent_id' => array('eq', $v['id']),
                'is_floor' => array('eq', '是')
            ))->select();
            //循环每个推荐的二级分类取出分类下被推荐到楼层的8件商品
            foreach($ret[$k]['recSubCat'] as $k1 => $v1){
                //取出这个分类下的所有商品id并返回一维数组
                $gids = $gModel->getGoodsIdByCatId($v1['id']);
                $ret[$k]['recSubCat'][$k1]['goods'] = $gModel->where(array(
                    'is_on_sale' => array('eq', '是'),
                    'is_floor' => array('eq', '是'),
                    'id' => array('in', $gids),
                ))->select();
            }
        }
        return $ret;
    }
    
    //
    /**
     * 取出一个父类的所有上级分类
     */
    public function parentPath($catId){
        static $ret = array();
        $info = $this->field("id, cat_name, parent_id")->find($catId);
        $ret[] = $info;
        if($info['parent_id'] !== 0){
            $this->parentPath($info['parent_id']);
        }
        return $ret;
    }
    
    public function getSearchConditionByGoodsId($goodsId){
        $ret = array(); //返回的数组
//        //取出该分类下的所有商品id
//        $gModel = D("Admin/goods");
//        $goodsId = $gModel->getGoodsIdByCatId($catId);
        //根据商品id取出品牌数据
        $gModel = D("Admin/goods");
        $ret['brand'] = $gModel->alias('a')->field("b.id, b.brand_name, b.logo")->join("LEFT JOIN __BRAND__ b ON b.id=a.brand_id")->where(array(
            'a.id' => array("in", $goodsId),
            'a.brand_id' => array("neq", 0)
        ))->group('b.id')->select();
        //取出这个分类下的最大和最小价格
        $priceInfo = $gModel->field("max(shop_price) max_price, min(shop_price) min_price")->where(array(
            'id' => array('in', $goodsId)
        ))->find();
        $priceSection = $priceInfo['max_price'] - $priceInfo['min_price']; //最大和最小价格区间
        $sectionCount = 6;
        $count = count($goodsId);
        if($count > 1){   //根据商品数量计算出价格的分段数         
            if($priceSection < 100)
                $sectionCount = 2;
            elseif($priceSection < 1000)
                $sectionCount = 4;
            elseif($priceSection < 10000)
                $sectionCount = 6;
            else
                $sectionCount = 7;      
        
            $pricePerSection = $priceSection / $sectionCount; //计算每段的范围
            $price = array();
            $firstPrice = $priceInfo['min_price'];
            for($i = 0; $i < $sectionCount; $i++){
                $tmp_end = $firstPrice + $pricePerSection;  //每段结束的价格
                $tmp_end = ceil(($tmp_end/100)*100-1);
                $price[] = $firstPrice.'-'.$tmp_end;
                $firstPrice = $tmp_end + 1;               //下一段开始的价格
            }
            $ret['price'] = $price;
        }
        //商品属性
        $gaModel = D('goods_attr');
        $gaData = $gaModel->alias("a")
                ->field("a.attr_id, a.attr_value, b.attr_name")
                ->join("LEFT JOIN __ATTRIBUTE__ b ON b.id=a.attr_id")
                ->distinct("a.attr_id")
                ->where(array("a.goods_id" => array('in', $goodsId)))->select();
        foreach($gaData as $k => $v){
            $_gaData[$v['attr_name']][] = $v;
        }
        $ret['gaData'] = $_gaData;
        return $ret;
        
    }
    
    
    public function getSection($catId){
        //返回的数组
        $ret = array();
        //获取商品id
        $gModel = D('Admin/goods');
        $goodsId = $gModel->getGoodsIdByCatId($catId);
        //获取品牌数据
        $brand = $gModel->alias("a")->field("b.brand_name, b.logo")
                ->join("LEFT JOIN __BRAND__ b ON b.id=a.brand_id")
                ->group('a.brand_id')
                ->where(array(
            'a.id' => array('in', $goodsId),
            'a.brand_id' => array('NEQ', 0)
        ))->select();
        $ret['brand'] = $brand;
        //
        $price = $gModel->field("max(shop_price) max_price, min(shop_price) min_price")->find();
        $priceSection = $price['max_price'] - $price['min_price'];   //价格区间
        $count = count($goodsId);    //计算商品数量
        //根据商品数量对价格分段
        $sectionCount = 2;
        if($count && $count <100)
            $sectionCount = 2;
        elseif($count && $count <1000)
            $sectionCount = 4;
        elseif($count && $count <1000)
            $sectionCount = 6;
        else
            $sectionCount = 7;
        $section = $priceSection / $sectionCount;  //每段价格的范围
        $first = $price['min_price'];
        for($i = 0; $i < $sectionCount; $i++){
            $tmp_end = $first + $section;
            $tmp_end = $tmp_end/100*100-1;
            $priceInfo[] = $first.'-'.$tmp_end;
            $first = $tmp_end+1;
        }
        $ret['price'] = $priceInfo;
        
        //
        $gaModel = D('goods_attr');
        $gaData = $gaModel->alias("a")->field("a.attr_id, a.attr_value, b.attr_name")->join("LEFT JOIN __ATTRIBUTE__ b ON b.id=a.attr_id")->where(array(
            'a.goods_id' => array('in', $goodsId),            
        ))->select();
        $_gaData = array();
        foreach($gaData as $k => $v){
            $_gaData[$v['attr_name']][] = $v; 
        }
        $ret['gaData'] = $_gaData;
        return $ret;
    }
    
    public function getTre($data, $parentId = 0, $level = 0, $html='--'){
        static $ret = array();
        static $arr = array();
        foreach($data as $k => $v){
            if($v['parent_id'] == $parentId){
                if(in_array($v['id'], $arr))
                        continue;
                $v['level'] = $level;
                $v['html'] = str_repeat($html, 8*$level);
                $ret[] = $v;
                $arr[] = $v['id'];
                $this->getTre($data, $v['id'], $level+1);
            }
        }
        return $ret;
    }
    
    public function getChd($data, $catId, $is_clear = false){
        static $ret = array();
        if($is_clear == true)
            $ret = array();
        foreach($data as $k => $v){
            if(in_array($v['id'], $ret))
                    continue;
            if($v['parent_id'] == $catId){
                $ret[] = $v['id'];
                $this->getChd($data, $v['id']);
            }
        }
        return $ret; 
    }
}
