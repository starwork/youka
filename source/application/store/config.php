<?php

return [
    // 默认输出类型
    'default_return_type'    => 'html',

    'template'               => [
        // layout布局
        'layout_on'     =>  true,
        'layout_name'   =>  'layouts/layout',
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'php',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}}',
        // 标签库标签开始标记
        'taglib_begin' => '{{',
        // 标签库标签结束标记
        'taglib_end'   => '}}',
    ],
    'dispatch_error_tmpl'    => APP_PATH . 'store/view/error.php',
    'auth' => [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'store_group', // 用户组数据表名
        'auth_group_access' => 'store_group_access', // 用户-用户组关系表
        'auth_rule'         => 'store_rules', // 权限规则表
        'auth_user'         => 'store_user', // 用户信息表
    ]
];
