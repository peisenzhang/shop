<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE' =>  'pdo',     // mysql,mysqli,pdo
    'DB_DSN'    => 'mysql:host=localhost;dbname=shop_119;charset=utf8',
    'DB_USER' =>  'root',      // 用户名
    'DB_PWD' =>  '123456',          // 密码
    'DB_PORT' =>  '3306',        // 端口
    'DB_PREFIX' =>  'shop_',    // 数据库表前缀
    
    'IMG_CONFIG' => array(
        'maxSize' => 1024*1024,
        'exts' => array('jpeg', 'jpg', 'png', 'gif'),
        'rootPath' => './Public/Uploads/',
        'viewPath' => '/Public/Uploads/'
    ),
    
    'DEFAULT_FILTER' => 'trim,htmlspecialchars',
);