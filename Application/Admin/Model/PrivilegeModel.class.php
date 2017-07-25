<?php

namespace Admin\Model;
use Think\Model;
class PrivilegeModel extends Model{
    
    //获取分类树
    public function getTree(){
        $data = $this->select();
        return $this->_getTree($data);
    }
    protected function _getTree($data, $parentId=0, $level=0, $isClear=false){
         static $ret = array();
         if($isClear)
             $ret = array();
         foreach($data as $k => $v){
             if(in_array($v['id'], $ret['id']))
                     continue;
             if($v['parent_id'] == $parentId){
                 $v['level'] = $level;
                 $ret[] = $v;
                 $ret['id'][] = $v['id'];
                 $this->_getTree($data, $v['id'], $level+1);
             }
         }
         return $ret;    
     }
     //建立复选框
    public function buildSelect($selectName, $selectValue){
        $data = $this->getTree();
        $select = "";
        $select .= "<select name='".$selectName."'><option value='0'>顶级</option>";
        foreach($data as $k => $v){
            if(!$v['id']) 
                continue;
            if($selectValue && ($v['id'] == $selectValue))
                $selected = 'selected="selected"';
            else
                $selected = '';
            $select .= "<option ".$selected." value='".$v['id']."'>".str_repeat('-', 8*$v['level']).$v['pri_name']."</option>";   
        }
        $select .= "</select>";
        return $select;
    }
    
    //获取权限复选框
    public function buildCheckbox($checkArray=array()){
        $data = $this->getTree();
        foreach($data as $k => $v){
            if(!$v['id'])
                continue;
            if($checkArray && in_array($v['id'], $checkArray))
                    $checked = 'checked="checked"';
            else
                $checked = '';
            $checkbox .= str_repeat('-', 8*$v['level'])."<input type='checkbox' value='".$v['id']."' name='pri_id[]' ".$checked." />".$v['pri_name']."<br/>";
        }
        return $checkbox;
    }
    
    //检查是否有权限访问正在访问的页面
    public function checkPri(){
        $admin_id = session('id');
        if($admin_id == 1){    //超级管理员直接给出所有权限
            return true;
        }else{
            $raModel = D('role_admin');
            $has = $raModel->alias("a")
                    ->join("LEFT JOIN __ROLE_PRI__ b ON b.role_id=a.role_id
                            LEFT JOIN __PRIVILEGE__ c ON c.id=b.pri_Id")
                    ->where(array(
                        'a.admin_id' => array('eq', $admin_id),
                        'c.module_name' => array('eq', MODULE_NAME),
                        'c.controller_name' => array('eq', CONTROLLER_NAME),
                        'c.action_name' => array('eq', ACTION_NAME),
                        ))
                    ->select();
            return $has;
        } 
    }
    
    //取出当前用户能访问所有权限名称
    public function getBtns(){
        $admin_id = session('id');
        $raModel = D('role_admin');
        $has = $raModel->alias("a")
                ->field("c.*")
                ->distinct(true)
                ->join("LEFT JOIN __ROLE_PRI__ b ON b.role_id=a.role_id LEFT JOIN __PRIVILEGE__ c ON c.id=b.pri_id")
                ->where(array('admin_id' => array('eq', $admin_id)))->select();
        //从所有权限中挑出前两级的权限
        foreach($has as $k => $v){
            if($v['parent_id'] == 0){
                $arr[] = $v['id'];
                foreach($has as $k1 => $v1){
                    if(in_array($v1['id'], $arr))
                            continue;
                    if($v1['parent_id'] == $v['id']){
                        $arr[] = $v1['id'];
                        $v['children'][] = $v1 ;
                    }
                }
                $ret[] = $v;
            }
        }
        return $ret;
    }
}
