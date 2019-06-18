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
    <link rel="stylesheet" href="__ASSETS__/store/css/amazeui.min.css"/>
    <link rel="stylesheet" href="__ASSETS__/store/css/app.css"/>
    <link rel="stylesheet" href="//at.alicdn.com/t/font_783249_fc0v7ysdt1k.css">
    <script src="__ASSETS__/store/js/jquery.min.js"></script>
    <script src="//at.alicdn.com/t/font_783249_e5yrsf08rap.js"></script>
    <script>
        BASE_URL = '<?= isset($base_url) ? $base_url : '' ?>';
        STORE_URL = '<?= isset($store_url) ? $store_url : '' ?>';
    </script>
</head>

<body data-type="">
<div class="am-g tpl-g">
    <!-- 内容区域 start -->
    {__CONTENT__}
    <!-- 内容区域 end -->
</div>
<script src="__ASSETS__/layer/layer.js"></script>
<script src="__ASSETS__/store/js/jquery.form.min.js"></script>
<script src="__ASSETS__/store/js/amazeui.min.js"></script>
<script src="__ASSETS__/store/js/webuploader.html5only.js"></script>
<script src="__ASSETS__/store/js/art-template.js"></script>
<script src="__ASSETS__/store/js/app.js"></script>
</body>

</html>