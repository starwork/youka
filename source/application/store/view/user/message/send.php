<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">系统消息</div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">消息类型</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <?php foreach ($type_arr as $key =>$type): ?>
                                        <input type="radio" name="message[type]" class="am-radio-inline" id="<?=$key ?>" <?=$key== 'system' ? 'checked' : ''?> value="<?=$key ?>" />
                                        <label for="<?=$key ?>"><?=$type ?></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="am-form-group" id="uids" style="display: none;">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">发送会员</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-u-sm-7">
                                        <input type="text" class="tpl-form-input" name="message[uids]" autocomplete="off"
                                               value="">
                                    </div>
                                    <small>输入会员ID,多个会员使用|隔开</small>
<!--                                    <button type="button"-->
<!--                                            class="select-user upload-file am-btn am-btn-secondary am-radius">-->
<!--                                        <i class="am-icon-cloud-upload"></i> 选择会员-->
<!--                                    </button>-->
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">消息标题</label>
                                <div class="am-u-sm-9 am-u-end">
                                   <div class="am-u-sm-7">
                                       <input type="text" class="tpl-form-input" name="message[title]" autocomplete="off"
                                              value="" required>
                                   </div>
                                    <small>输入标题</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">消息内容</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-u-sm-7">
                                        <textarea class="" name="message[content]" rows="5" id="doc-ta-1" required></textarea>
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
        $("input[name='message[type]']").change(function () {
            if($(this).val() == 'notice'){
                $("#uids").show();
            }else{
                $("#uids").hide();
            }
        })
        var UserList = [];
        var uids = [];
        $(".select-user").click(function () {
            $.selectData({
                title: '选择会员',
                uri: 'user/index',
                duplicate: false,
                dataIndex: 'uids',
                done: function (data) {
                    console.log(UserList)
                    data.forEach(function (item) {
                        UserList.push(item);
                        uids.push(item.user_id);
                    });
                    console.log(uids.join('|'))
                    $("input[name='message[uids]']").val(uids.join('|'))
                },
                getExistData: function () {
                    var uids = [];
                    console.log(UserList)
                    UserList.forEach(function (item) {
                        uids.push(item.user_id);
                    });
                    console.log(uids)
                    return uids;
                }

            });
        })

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm({
            // 自定义验证
            validation: function () {
                var Type = $('input:radio[name="message[type]"]:checked').val();
                if (Type === 'notice') {
                    var uids = $('input[name="message[uids]"]').val();
                    console.log(uids);
                    if(uids === ""){
                        layer.msg('输入发送会员');
                        return false;
                    }
                }
                return true;
            }
        });

    });
</script>
