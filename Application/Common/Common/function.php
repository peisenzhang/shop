<?php
//过滤
function removeXSS($data){
    require_once "./Public/HtmlPurifier/library/HTMLPurifier.auto.php";
    //require_once('HTMLPurifier/library/HTMLPurifier.auto.php');
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Core.Encoding', 'UTF-8');
	// 设置保留的标签
    $config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
    $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
    $config->set('HTML.TargetBlank', TRUE);
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($data);  
}

//显示图片
function showImg($url, $width='', $height=''){
    $ic = C('IMG_CONFIG');
    if($width)
        $width = "width=$width";
    if($height)
        $height = "height=$height";
    echo "<img $width $height src='{$ic['viewPath']}$url'/>";
}

//删除图片
function deleteImg($url){
    $ic = C('IMG_CONFIG');
    if(is_array($url)){
        foreach($url as $k => $v){
            unlink($ic['rootPath'].$v);
        }
    }else{
        unlink($ic['rootPath'].$url);
    } 
}

//上传图片
 function uploadOne($imgName, $savePath, $thumb=array()){
    if($_FILES[$imgName] && $_FILES[$imgName]['error']==0){
        $ic = C('IMG_CONFIG');
        $config = array(
            'maxSize' => $ic['maxSize'],
            'exts' => $ic['exts'],
            'rootPath' => $ic['rootPath'],
            'savePath' => $savePath.'/'
        );
        $Upload = new \Think\Upload($config);
        $info = $Upload->upload(array($imgName=>$_FILES[$imgName]));
        if(!$info){
            return array(
                'ok' => 0,
                'error' => $Upload->getError(),
            );
        }else{
            $ret = array();
            $ret['ok'] = 1;
            $ret['images'][0] = $logo = $info[$imgName]['savepath'].$info[$imgName]['savename'];
            //判断是否生成缩略图
            if($thumb){
                $Img = new \Think\Image();
                //$Img->open($ic[$rootPath].$logo);
                foreach($thumb as $k => $v){
                    //拼各缩略图的路径
                    $ret['images'][$k+1] = $th[$k] = $info[$imgName]['savepath'].'thumb_'.$k.'_'.$info[$imgName]['savename'];
                    $Img->open($ic['rootPath'].$logo);
                    $Img->thumb($v[0], $v[1])->save($ic['rootPath'].$th[$k]);
                }
            }
            return $ret;
        }
    }
}

/**
 * 
 * @param type $tableName 
 * @param type $selectName
 * @param type $valueFieldName
 * @param type $textFieldName
 * @param type $selectedValue
 */
function buildSelect($tableName, $selectName, $valueFieldName, $textFieldName, $selectedValue = '')
{
	$model = D($tableName);
	$data = $model->field("$valueFieldName,$textFieldName")->select();
	$select = "<select name='".$selectName."'><option value='0' >请选择</option>"; 
	foreach ($data as $k => $v)
	{
		$value = $v[$valueFieldName];
		$text = $v[$textFieldName];
		if($selectedValue && $selectedValue==$value)
			$selected = 'selected="selected"';
		else 
			$selected = '';
		$select .= '<option '.$selected.' value="'.$value.'">'.$text.'</option>';
	}
	$select .= '</select>';
	echo $select;
}

function makeAlipayBtn($orderId, $btnName = '去支付宝支付'){
    return require_once  "./Public/alipay/alipayapi.php";
}

/**
 * 从当前URL中去掉某个参数之后的URL
 *
 * @param unknown_type $param
 */
function filterUrl($param)
{
	// 先取出当前的URL地址
	$url = $_SERVER['PHP_SELF'];
	// 正则去掉某个参数
	$re = "/\/$param\/[^\/]+/";
	return preg_replace($re, '', $url);
}



    
