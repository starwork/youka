<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-16 14:34
 */
// [ 获取微信用户信息入口文件 ]

// 手动定义路由
$_GET['s'] = '/api/user/getWechatUser';

// 定义运行目录
define('WEB_PATH', __DIR__ . '/');

// 定义应用目录
define('APP_PATH', WEB_PATH . '../source/application/');

// 加载框架引导文件
require APP_PATH . '../thinkphp/start.php';