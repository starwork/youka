<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"D:\myphp_www\PHPTutorial\WWW\mall\web/../source/application/store\view\user\index.php";i:1557821677;s:82:"D:\myphp_www\PHPTutorial\WWW\mall\source\application\store\view\layouts\layout.php";i:1557993091;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title><?= $setting['store']['values']['name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="assets/store/i/favicon.ico"/>
    <meta name="apple-mobile-web-app-title" content="<?= $setting['store']['values']['name'] ?>"/>
    <link rel="stylesheet" href="/assets//store/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets//store/css/app.css"/>
    <link rel="stylesheet" href="//at.alicdn.com/t/font_783249_fc0v7ysdt1k.css">
    <script src="/assets//store/js/jquery.min.js"></script>
    <script src="//at.alicdn.com/t/font_783249_e5yrsf08rap.js"></script>
    <script>
        BASE_URL = '<?= isset($base_url) ? $base_url : '' ?>';
        STORE_URL = '<?= isset($store_url) ? $store_url : '' ?>';
    </script>
</head>

<body data-type="">
<div class="am-g tpl-g">
    <!-- 头部 -->
    <header class="tpl-header">
        <!-- 右侧内容 -->
        <div class="tpl-header-fluid">
            <!-- 侧边切换 -->
            <div class="am-fl tpl-header-button switch-button">
                <i class="iconfont icon-menufold"></i>
            </div>
            <!-- 刷新页面 -->
            <div class="am-fl tpl-header-button refresh-button">
                <i class="iconfont icon-refresh"></i>
            </div>
            <!-- 其它功能-->
            <div class="am-fr tpl-header-navbar">
                <ul>
                    <!-- 欢迎语 -->
                    <li class="am-text-sm tpl-header-navbar-welcome">
                        <a href="<?= url('store.user/renew') ?>">欢迎你，<span><?= $store['user']['user_name'] ?></span>
                        </a>
                    </li>
                    <!-- 退出 -->
                    <li class="am-text-sm">
                        <a href="<?= url('passport/logout') ?>">
                            <i class="iconfont icon-tuichu"></i> 退出
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- 侧边导航栏 -->
    <div class="left-sidebar">
        <?php $menus = $menus ?: []; $group = $group ?: []; ?>
        <!-- 一级菜单 -->
        <ul class="sidebar-nav">
            <li class="sidebar-nav-heading"><?= $setting['store']['values']['name'] ?></li>
            <?php foreach ($menus as $key => $item): ?>
                <li class="sidebar-nav-link">
                    <a href="<?= isset($item['name']) ? url($item['name']) : 'javascript:void(0);' ?>"
                       class="<?= in_array($item['id'],$group) ? 'active' : '' ?>">
                        <?php if (isset($item['is_svg']) && $item['is_svg'] === true): ?>
                            <svg class="icon sidebar-nav-link-logo" aria-hidden="true">
                                <use xlink:href="#<?= $item['icon'] ?>"></use>
                            </svg>
                        <?php else: ?>
                            <i class="iconfont sidebar-nav-link-logo <?= $item['icon'] ?>"
                               style="<?= isset($item['color']) ? "color:{$item['color']};" : '' ?>"></i>
                        <?php endif; ?>
                        <?= $item['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- 子级菜单-->
        <?php $second =  isset($group[0]) && isset($menus[$group[0]]['submenu']) ? $menus[$group[0]]['submenu'] : []; if (!empty($second)) : ?>
            <ul class="left-sidebar-second">
                <li class="sidebar-second-title"><?= $menus[$group[0]]['title'] ?></li>
                <li class="sidebar-second-item">
                    <?php foreach ($second as $item) : if (!isset($item['submenu'])): ?>
                            <!-- 二级菜单-->
                            <a href="<?= url($item['name']) ?>" class="<?= in_array($item['id'],$group) ? 'active' : '' ?>">
                                <?= $item['title']; ?>
                            </a>
                        <?php else: ?>
                            <!-- 三级菜单-->
                            <div class="sidebar-third-item">
                                <a href="javascript:void(0);"
                                   class="sidebar-nav-sub-title <?= in_array($item['id'],$group) ? 'active' : '' ?>">
                                    <i class="iconfont icon-caret"></i>
                                    <?= $item['title']; ?>
                                </a>
                                <ul class="sidebar-third-nav-sub">
                                    <?php foreach ($item['submenu'] as $third) : ?>
                                        <li>
                                            <a class="<?= in_array($third['id'],$group) ? 'active' : '' ?>"
                                               href="<?= url($third['name']) ?>">
                                                <?= $third['title']; ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; endforeach; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>

    <!-- 内容区域 start -->
    <div class="tpl-content-wrapper <?= empty($second) ? 'no-sidebar-second' : '' ?>">
        <div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">用户列表</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>手机号</th>
                                <th>头像</th>
                                <th>昵称</th>
                                <th>用户余额</th>
                                <th>用户积分</th>
                                <th>性别</th>
                                <th>级别</th>
                                <th>国家</th>
                                <th>省份</th>
                                <th>城市</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['user_id'] ?></td>
                                    <td class="am-text-middle"><?= $item['phone'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['avatarUrl'] ?>" width="72" height="72" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['nickName'] ?></td>
                                    <td class="am-text-middle"><?= $item['price'] ?></td>
                                    <td class="am-text-middle"><?= $item['point'] ?></td>
                                    <td class="am-text-middle"><?= $item['gender'] ?></td>
                                    <td class="am-text-middle">
                                        <span class="j-state am-badge x-cur-p level-<?= $item['level']['level'] ?>"
                                              data-id="<?= $item['user_id'] ?>"
                                              data-level="<?= $item['level']['level'] ?>"
                                        >
                                            <?= $item['level']['level_name'] ?>
                                        </span>
                                    </td>
                                    <td class="am-text-middle"><?= $item['country'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['province'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['city'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
<!--                                            <a class="j-recharge tpl-table-black-operation-default"-->
<!--                                               href="javascript:void(0);"-->
<!--                                               data-id="11635"-->
<!--                                               data-balance="0.00">-->
<!--                                                <i class="iconfont icon-qiandai" style="font-size: 12px;"></i>-->
<!--                                                充值-->
<!--                                            </a>-->
                                            <a class="j-delete tpl-table-black-operation-default"
                                               href="javascript:void(0);" style="display: <?=check_auth('user/delete',$uid) ? 'inline-block' : 'none'?>;"
                                               data-id="11635" title="删除">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>
                                            <div class="j-opSelect operation-select am-dropdown" style="display: <?=check_auth('order/index,user.price_log/index',$uid) ? 'inline-block' : 'none'?>;">
                                                <button type="button"
                                                        class="am-dropdown-toggle am-btn am-btn-sm am-btn-secondary">
                                                    <span>更多</span>
                                                    <span class="am-icon-caret-down"></span>
                                                </button>
                                                <ul class="am-dropdown-content" data-id="11635">
                                                    <li>
                                                        <a class="am-dropdown-item" target="_blank"
                                                           href="<?=check_url('order/all_list',$uid,['user_id'=>$item['user_id']])?>">用户订单</a>
                                                    </li>
                                                    <li>
                                                        <a class="am-dropdown-item" target="_blank"
                                                           href="<?=check_url('user.price_log/index',$uid,['user_id'=>$item['user_id']])?>">余额明细</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="8" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="am-u-lg-12 am-cf">
                        <div class="am-fr"><?= $list->render() ?> </div>
                        <div class="am-fr pagination-total am-margin-right">
                            <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="tpl-update-level" type="text/template">
    <div class="am-padding-top-sm tpl-form-line-form">
        <form class="form-update-level am-form tpl-form-line-form" method="post"
              action="<?=url('user/update_level') ?>">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label">会员等级 </label>
                <div class="am-u-sm-9">
                    <select name="level" required>
                        {{ each level_arr item }}
                        <option value="{{item.id}}" {{ if level == item.id }}selected{{ /if }}>{{item.name}}</option>
                        {{ /each }}
                    </select>
                </div>
                <input type="hidden" name="user_id" value="{{ user_id }}">
            </div>
    </div>
</script>
<script>
    $(function () {

        $('.j-state').click(function () {
            // 验证权限
            if (!<?= check_auth('user/update_level',$uid) ?>) {
                return false;
            }
            var data = $(this).data();
            var data ={
                level_arr:[
                    {id:0, name: '普通会员'},
                    {id:10, name: '分销商'},
                    {id:20, name: '经销商'},
                    {id:30, name: '市级代理'}
                ],
                level: data.level,
                user_id: data.id
            };
            layer.open({
                type: 1
                , title: '修改会员等级'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: template('tpl-update-level', data)
                , success: function (layero) {

                }
                , yes: function (index) {
                    // 表单提交
                    $('.form-update-level').ajaxSubmit({
                        type: "post",
                        dataType: "json",
                        success: function (result) {
                            result.code === 1 ? $.show_success(result.msg, result.url)
                                : $.show_error(result.msg);
                        }
                    });
                    layer.close(index);
                }
            });
        });
        /**
         * 注册操作事件
         * @type {jQuery|HTMLElement}
         */
        var $dropdown = $('.j-opSelect');
        $dropdown.dropdown();
        $dropdown.on('click', 'li a', function () {
            var $this = $(this);
            var id = $this.parent().parent().data('id');
            var type = $this.data('type');
            if (type === 'delete') {
                layer.confirm('删除后不可恢复，确定要删除吗？', function (index) {
                    $.post("index.php?s=/store/apps.dealer.user/delete", {dealer_id: id}, function (result) {
                        result.code === 1 ? $.show_success(result.msg, result.url)
                            : $.show_error(result.msg);
                    });
                    layer.close(index);
                });
            }
            $dropdown.dropdown('close');
        });
    });
</script>
<style>
    .level-0{
        background-color: #0a6aa1;
    }
    .level-10{
        background-color: #00e359;
    }
    .level-20{
        background-color: #42bde5;
    }
    .level-30{
        background-color: #8e44ad;
    }
</style>


    </div>
    <!-- 内容区域 end -->

</div>
<script src="/assets//layer/layer.js"></script>
<script src="/assets//store/js/jquery.form.min.js"></script>
<script src="/assets//store/js/amazeui.min.js"></script>
<script src="/assets//store/js/webuploader.html5only.js"></script>
<script src="/assets//store/js/art-template.js"></script>
<script src="/assets//store/js/app.js"></script>
<script src="/assets//store/js/file.library.js"></script>
</body>

</html>
