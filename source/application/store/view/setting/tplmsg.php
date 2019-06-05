<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">短信通知（ 阿里云短信 )</div>
                            </div>
                            <input type="hidden" name="sms[default]" value="aliyun">
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> AccessKeyId </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="sms[engine][aliyun][AccessKeyId]"
                                           value="<?= $values['engine']['aliyun']['AccessKeyId'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> AccessKeySecret </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="sms[engine][aliyun][AccessKeySecret]"
                                           value="<?= $values['engine']['aliyun']['AccessKeySecret'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 短信签名 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="sms[engine][aliyun][sign]"
                                           value="<?= $values['engine']['aliyun']['sign'] ?>" required>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">用户注册短信</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否开启短信提醒
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][resgiter][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['resgiter']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][resgiter][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['resgiter']['is_enable'] === '0' ? 'checked' : '' ?>>
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
                                           name="sms[engine][aliyun][resgiter][template_code]"
                                           value="<?= $values['engine']['aliyun']['resgiter']['template_code'] ?>">
                                    <small>例如：SMS_139800030</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <small>模板内容：您正在申请手机注册，验证码为：${code}，5分钟内有效！</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 接收手机号 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="sms[engine][aliyun][resgiter][accept_phone]"
                                           value="<?= $values['engine']['aliyun']['resgiter']['accept_phone'] ?>">
                                    <div class="help-block">
                                        <small>接收测试： <a class="j-sendTestMsg" data-msg-type="resgiter"
                                                        href="javascript:void(0);">点击发送</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">找回密码短信</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否开启短信提醒
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][resetpwd][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['resetpwd']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][resetpwd][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['resetpwd']['is_enable'] === '0' ? 'checked' : '' ?>>
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
                                           name="sms[engine][aliyun][resetpwd][template_code]"
                                           value="<?= !empty($values['engine']['aliyun']['resetpwd']['template_code']) ? $values['engine']['aliyun']['resetpwd']['template_code'] : '' ?>">
                                    <small>例如：SMS_139800030</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 接收手机号 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="sms[engine][aliyun][resetpwd][accept_phone]"
                                           value="<?= $values['engine']['aliyun']['resetpwd']['accept_phone'] ?>">
                                    <div class="help-block">
                                        <small>接收测试： <a class="j-sendTestMsg" data-msg-type="resetpwd"
                                                        href="javascript:void(0);">点击发送</a>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <small>模板内容：您的动态码为：${code}，您正在进行密码重置操作，如非本人操作，请忽略本短信！</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">新付款订单提醒</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否开启短信提醒
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][order_pay][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['order_pay']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][order_pay][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['order_pay']['is_enable'] === '0' ? 'checked' : '' ?>>
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
                                           name="sms[engine][aliyun][order_pay][template_code]"
                                           value="<?= $values['engine']['aliyun']['order_pay']['template_code'] ?>">
                                    <small>例如：SMS_139800030</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <small>模板内容：您有一条新订单，订单号为：${order_no}，请注意查看。</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 接收手机号 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="sms[engine][aliyun][order_pay][accept_phone]"
                                           value="<?= $values['engine']['aliyun']['order_pay']['accept_phone'] ?>">
                                    <div class="help-block">
                                        <small>接收测试： <a class="j-sendTestMsg" data-msg-type="order_pay"
                                                        href="javascript:void(0);">点击发送</a>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">订单发货短信</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否开启短信提醒
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][delivery][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['delivery']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][delivery][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['delivery']['is_enable'] === '0' ? 'checked' : '' ?>>
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
                                           name="sms[engine][aliyun][delivery][template_code]"
                                           value="<?= !empty($values['engine']['aliyun']['delivery']['template_code']) ? $values['engine']['aliyun']['delivery']['template_code'] : '' ?>">
                                    <small>例如：SMS_139800030</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <small>模板内容：您的订单${order_no},使用${company}发货啦！快递单号${express_no}。</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 接收手机号 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="sms[engine][aliyun][delivery][accept_phone]"
                                           value="<?= $values['engine']['aliyun']['delivery']['accept_phone'] ?>">
                                    <div class="help-block">
                                        <small>接收测试： <a class="j-sendTestMsg" data-msg-type="delivery"
                                                        href="javascript:void(0);">点击发送</a>
                                        </small>
                                    </div>
                                </div>
                            </div>


                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">提现成功短信</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否开启短信提醒
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][pay_success][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['pay_success']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][pay_success][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['pay_success']['is_enable'] === '0' ? 'checked' : '' ?>>
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
                                           name="sms[engine][aliyun][pay_success][template_code]"
                                           value="<?= !empty($values['engine']['aliyun']['pay_success']['template_code']) ? $values['engine']['aliyun']['pay_success']['template_code'] : '' ?>">
                                    <small>例如：SMS_139800030</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <small>模板内容：您的提现申请通过,提现金额${price}。</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 接收手机号 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="sms[engine][aliyun][pay_success][accept_phone]"
                                           value="<?= $values['engine']['aliyun']['pay_success']['accept_phone'] ?>">
                                    <div class="help-block">
                                        <small>接收测试： <a class="j-sendTestMsg" data-msg-type="pay_success"
                                                        href="javascript:void(0);">点击发送</a>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">提现失败短信</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    是否开启短信提醒
                                </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][pay_error][is_enable]" value="1"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['pay_error']['is_enable'] === '1' ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sms[engine][aliyun][pay_error][is_enable]" value="0"
                                               data-am-ucheck
                                            <?= $values['engine']['aliyun']['pay_error']['is_enable'] === '0' ? 'checked' : '' ?>>
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
                                           name="sms[engine][aliyun][pay_error][template_code]"
                                           value="<?= !empty($values['engine']['aliyun']['pay_error']['template_code']) ? $values['engine']['aliyun']['pay_error']['template_code'] : '' ?>">
                                    <small>例如：SMS_139800030</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <small>模板内容：您的提现申请失败，失败原因:${content}。</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 接收手机号 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input"
                                           name="sms[engine][aliyun][pay_error][accept_phone]"
                                           value="<?= $values['engine']['aliyun']['pay_error']['accept_phone'] ?>">
                                    <div class="help-block">
                                        <small>接收测试： <a class="j-sendTestMsg" data-msg-type="pay_error"
                                                        href="javascript:void(0);">点击发送</a>
                                        </small>
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
