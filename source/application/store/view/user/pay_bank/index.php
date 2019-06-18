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

