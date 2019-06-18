<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:94:"D:\myphp_www\PHPTutorial\WWW\mall\web/../source/application/store\view\user\pay_bank\index.php";i:1560503983;s:82:"D:\myphp_www\PHPTutorial\WWW\mall\source\application\store\view\layouts\layout.php";i:1560391351;}*/ ?>
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
                    <div class="widget-title am-cf">提现列表</div>
                </div>
                <div class="am-btn-toolbar">
                    <a class="am-btn am-btn-default am-btn-success am-radius" style="font-size: 12px;"
                       href="<?= check_url('user.pay_bank/setting',$uid) ?>">
                        <span class="am-icon-setting"></span> 提现设置
                    </a>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户</th>
                                <th>提现金额</th>
                                <th>银行信息</th>
                                <th>提现交易号</th>
                                <th>状态</th>
                                <th>原因</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle">
                                        <p><?= $item['user']['nickName'] ?></p>
                                        <p class="am-link-muted">(用户id：<?= $item['user']['user_id'] ?>)</p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p>提现总金额：<?= $item['price'] ?></p>
                                        <p>打款金额：<?= $item['price']-round(($item['price']*$lv['service_fee']/100),2) ?> 元</p>
                                        <p>手续费：<?= round(($item['price']*$lv['service_fee']/100),2) ?>元</p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p>真实姓名：<?= $item['real_name'] ?></p>
                                        <p>银行名称：<?= $item['bank_name'] ?></p>
                                        <p>开户支行名称：<?= $item['open_bank_name'] ?></p>
                                        <p>银行卡号：<?= $item['card_no'] ?></p>
                                    </td>
                                    <td class="am-text-middle"><?= $item['payment_no'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['status']['text'] ?></td>
                                    <td class="am-text-middle"><?= $item['remark'] ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <?php if ($item['status']['value'] == 10) :?>
                                        <div class="tpl-table-black-operation">
                                            <a href="javascript:;" class="success" data-id="<?= $item['id'] ?>" style="display: <?= check_auth('user.pay_bank/pay_success',$uid) ? 'inline-block':'none' ?>">
                                                打款成功
                                            </a>
                                            <a href="javascript:;" class="error item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['id'] ?>" style="display: <?= check_auth('user.pay_bank/pay_error',$uid) ? 'inline-block':'none' ?>">
                                                打款失败
                                            </a>
                                        </div>
                                        <?php endif; ?>
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

<script id="tpl-pay-success" type="text/template">
    <div class="am-padding-top-sm tpl-form-line-form">
        <form class="form-pay-success am-form tpl-form-line-form" method="post"
              action="<?=url('user.pay_bank/pay_success') ?>">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label">交易号 </label>
                <div class="am-u-sm-9">
                    <input type="text" class="tpl-form-input" name="payment_no"
                           value="" required>
                </div>
                <input type="hidden" name="id" value="{{ id }}">
            </div>
    </div>
</script>

<script id="tpl-pay-error" type="text/template">
    <div class="am-padding-top-sm tpl-form-line-form">
        <form class="form-pay-error am-form tpl-form-line-form" method="post"
              action="<?=url('user.pay_bank/pay_error') ?>">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label">失败原因 </label>
                <div class="am-u-sm-9">
                    <input type="text" class="tpl-form-input" name="remark"
                           value="" required>
                </div>
                <input type="hidden" name="id" value="{{ id }}">
            </div>
    </div>
</script>
<script>
    $(function () {
        $(".success").click(function () {
            // 验证权限
            if (!<?= check_auth('user.pay_bank/pay_success',$uid) ?>) {
                return false;
            }
            var data = $(this).data();
            layer.open({
                type: 1
                , title: '打款成功'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: template('tpl-pay-success', {id:data.id})
                , success: function (layero) {

                }
                , yes: function (index) {
                    if($("input[name='payment_no']").val() == ''){
                        $.show_error('输入交易号');
                        return false;
                    }
                    layer.confirm('确认打款成功，提交后无法修改！',{
                        btn:['确认','取消']
                    },function () {
                        var load_index = layer.load();
                        // 表单提交
                        $('.form-pay-success').ajaxSubmit({
                            type: "post",
                            dataType: "json",
                            success: function (result) {
                                layer.close(load_index);
                                if(result.code == 1){
                                    $.show_success(result.msg, result.url);
                                    layer.close(index);
                                }else{
                                    $.show_error(result.msg);
                                }
                            }
                        });
                    })

                    // layer.close(index);
                }
            });
        })


        $(".error").click(function () {
            // 验证权限
            if (!<?= check_auth('user.pay_bank/pay_error',$uid) ?>) {
                return false;
            }
            var data = $(this).data();
            layer.open({
                type: 1
                , title: '打款失败'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: template('tpl-pay-error', {id:data.id})
                , success: function (layero) {

                }
                , yes: function (index) {
                    if($("input[name='remark']").val() == ''){
                        $.show_error('输入失败原因');
                        return false;
                    }
                    // 表单提交
                    $('.form-pay-error').ajaxSubmit({
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
        })
    });
</script>


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
<script src="/assets//store/js/select.data.js"></script>

</body>

</html>
