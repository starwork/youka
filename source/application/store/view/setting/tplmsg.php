<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">支付成功通知</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否启用
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[order_pay][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['order_pay']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[order_pay][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['order_pay']['is_enable'] === '0' ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    模板ID <span class="tpl-form-line-small-title">Template Code</span>
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="tplmsg[order_pay][template_code]"
                                           value="<?= $values['order_pay']['template_code'] ?>">
                                    <small>模板编号AT0009，关键词 (订单编号、支付时间、订单金额、支付方式)</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">订单发货通知</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否启用
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[delivery][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['delivery']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[delivery][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['delivery']['is_enable'] === '0' ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    模板ID <span class="tpl-form-line-small-title">Template Code</span>
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="tplmsg[delivery][template_code]"
                                           value="<?= !empty($values['delivery']['template_code']) ? $values['delivery']['template_code'] : '' ?>">
                                    <small>模板编号AT0007，关键词 (订单编号、发货时间、物流公司、快递单号、收件信息)</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">佣金通知</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否启用
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[yongjin][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['yongjin']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[yongjin][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['yongjin']['is_enable'] === '0' ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    模板ID <span class="tpl-form-line-small-title">Template Code</span>
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="tplmsg[yongjin][template_code]"
                                           value="<?= !empty($values['yongjin']['template_code']) ? $values['yongjin']['template_code'] : '' ?>">
                                    <small>关键词 (订单号、佣金金额、时间)</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">等级变更</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否启用
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[level][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['level']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[level][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['level']['is_enable'] === '0' ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    模板ID <span class="tpl-form-line-small-title">Template Code</span>
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="tplmsg[level][template_code]"
                                           value="<?= !empty($values['level']['template_code']) ? $values['level']['template_code'] : '' ?>">
                                    <small>关键词 (当前等级、原始等级、升级时间)</small>
                                </div>
                            </div>


                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">提现通知</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否启用
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[pay_bank][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['pay_bank']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[pay_bank][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['pay_bank']['is_enable'] === '0' ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    模板ID <span class="tpl-form-line-small-title">Template Code</span>
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="tplmsg[pay_bank][template_code]"
                                           value="<?= !empty($values['pay_bank']['template_code']) ? $values['pay_bank']['template_code'] : '' ?>">
                                    <small>关键词 (提现金额、银行、银行卡号、审核结果，审核时间，描述)</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">会员加入通知</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否启用
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[child][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['child']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="tplmsg[child][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['child']['is_enable'] === '0' ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    模板ID <span class="tpl-form-line-small-title">Template Code</span>
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="tplmsg[child][template_code]"
                                           value="<?= !empty($values['child']['template_code']) ? $values['child']['template_code'] : '' ?>">
                                    <small>关键词 (时间,昵称、级别、描述)</small>
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

        /**
         * 发送测试短信
         */
        $('.j-sendTestMsg').click(function () {
            var msgType = $(this).data('msg-type')
                , formData = {
                AccessKeyId: $('input[name="sms[engine][aliyun][AccessKeyId]"]').val()
                , AccessKeySecret: $('input[name="sms[engine][aliyun][AccessKeySecret]"]').val()
                , sign: $('input[name="sms[engine][aliyun][sign]"]').val()
                , msg_type: msgType
                , template_code: $('input[name="sms[engine][aliyun][' + msgType + '][template_code]"]').val()
                , accept_phone: $('input[name="sms[engine][aliyun][' + msgType + '][accept_phone]"]').val()
            };
            if (!formData.AccessKeyId.length) {
                layer.msg('请填写 AccessKeyId');
                return false;
            }
            if (!formData.AccessKeySecret.length) {
                layer.msg('请填写 AccessKeySecret');
                return false;
            }
            if (!formData.sign.length) {
                layer.msg('请填写 短信签名');
                return false;
            }
            if (!formData.template_code.length) {
                layer.msg('请填写 模板ID');
                return false;
            }
            if (!formData.accept_phone.length) {
                layer.msg('请填写 接收手机号');
                return false;
            }
            layer.confirm('确定要发送测试短信吗', function (index) {
                var load = layer.load();
                var url = "<?= url('setting/smsTest') ?>";
                $.post(url, formData, function (result) {
                    layer.msg(result.msg);
                    layer.close(load);
                });
                layer.close(index);
            });
        });

    });
</script>
