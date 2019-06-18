<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">提现设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">每周提现次数</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-u-sm-5">
                                        <input type="text" class="tpl-form-input" name="max_num" autocomplete="off"
                                               value="<?= $config['max_num'] ?>" required>
                                    </div>
                                    <small>次<mall>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">最低提现金额</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-u-sm-5">
                                        <input type="text" class="tpl-form-input" name="min_price" autocomplete="off"
                                               value="<?= $config['min_price'] ?>" required>
                                    </div>
                                    <small><mall>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">提现手续费</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-u-sm-5">
                                        <input type="text" class="tpl-form-input" name="service_fee" autocomplete="off"
                                               value="<?= $config['service_fee'] ?>" required>
                                    </div>
                                    <small>百分比 0-100<mall>
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
