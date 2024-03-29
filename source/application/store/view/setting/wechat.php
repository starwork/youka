<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">微信公众号设置</div>
                            </div>
                            <div
                                 class="form-tab-group active">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label">
                                        AppID <span class="tpl-form-line-small-title"><span class="tpl-form-line-small-title">公众号ID</span></span>
                                    </label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" name="wechat[app_id]"
                                               value="<?= $values['app_id'] ?>">
                                    </div>
                                </div>
                                <div class="am-form-group ">
                                    <label class="am-u-sm-3 am-form-label">
                                        AppSecret  <span class="tpl-form-line-small-title">公众号密钥</span>
                                    </label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input"
                                               name="wechat[app_secret]"
                                               value="<?= $values['app_secret'] ?>">
                                    </div>
                                </div>


                                <div class="widget-head am-cf">
                                    <div class="widget-title am-fl">微信支付设置</div>
                                </div>

                                <div class="am-form-group ">
                                    <label class="am-u-sm-3 am-form-label">
                                        MCHID  <span class="tpl-form-line-small-title">微信支付商户号</span>
                                    </label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input"
                                               name="wechat[mchid]"
                                               value="<?= $values['mchid'] ?>">
                                    </div>
                                </div>

                                <div class="am-form-group ">
                                    <label class="am-u-sm-3 am-form-label">
                                        APIKEY  <span class="tpl-form-line-small-title">微信支付密钥</span>
                                    </label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input"
                                               name="wechat[apikey]"
                                               value="<?= $values['apikey'] ?>">
                                    </div>
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
