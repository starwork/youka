<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:89:"D:\myphp_www\PHPTutorial\WWW\mall\web/../source/application/store\view\setting\retail.php";i:1560475610;s:82:"D:\myphp_www\PHPTutorial\WWW\mall\source\application\store\view\layouts\layout.php";i:1560391351;}*/ ?>
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
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">分销设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">复购折扣 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[many_buy]"
                                               value="<?= $values['many_buy'] ?>" >
                                    </div>
                                    <small>范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">复购优惠描述 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="text" class="am-form-field" name="retail[many_msg]"
                                               value="<?= $values['many_msg'] ?>" >
                                    </div>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">首次直推开发奖 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[first_money]"
                                               value="<?= $values['first_money'] ?>" >
                                    </div>
                                    <small>固定金额，单位元</small>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">分销商设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">一级佣金比例 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[10][first_money]"
                                               value="<?= $values[10]['first_money'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">二级佣金比例 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[10][second_money]"
                                               value="<?= $values[10]['second_money'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">经销商设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">一级佣金比例 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][first_money]"
                                               value="<?= $values[20]['first_money'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">二级佣金比例 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][second_money]"
                                               value="<?= $values[20]['second_money'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">直辖区域维护奖 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][maintain][diect]"
                                               value="<?= $values[20]['maintain']['diect'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">直接育成维护奖 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][maintain][indirect]"
                                               value="<?= $values[20]['maintain']['indirect'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">市级代理设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">一级佣金比例 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][first_money]"
                                               value="<?= $values[30]['first_money'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">二级佣金比例 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][second_money]"
                                               value="<?= $values[30]['second_money'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">直辖区域维护奖 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][maintain][diect]"
                                               value="<?= $values[30]['maintain']['diect'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">直接育成维护奖 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][maintain][indirect]"
                                               value="<?= $values[30]['maintain']['indirect'] ?>" >
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>


                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

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
