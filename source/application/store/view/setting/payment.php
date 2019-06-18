<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">支付设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    默认支付方式
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="payment[default]" value="wechat" data-am-ucheck
                                            <?= $values['default'] === 'wechat' ? 'checked' : '' ?>> 微信支付
                                    </label>
                                </div>
                            </div>
                            <div id="qiniu"
                                 class="form-tab-group <?= $values['default'] === 'wechat' ? 'active' : 'active' ?>">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label">
                                        微信支付商户号 <span class="tpl-form-line-small-title"><span class="tpl-form-line-small-title">MCHID</span></span>
                                    </label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" name="payment[engine][wechat][mchid]"
                                               value="<?= $values['engine']['wechat']['mchid'] ?>">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label">
                                        微信支付密钥 <span class="tpl-form-line-small-title">APIID</span>
                                    </label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input"
                                               name="payment[engine][wechat][app_id]"
                                               value="<?= $values['engine']['wechat']['app_id'] ?>">
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
