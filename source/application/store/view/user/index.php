<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">用户列表</div>
                </div>
                <div class="widget-body am-fr">
                <form class="toolbar-form page_toolbar" action="" id="form-search">
                    <input type="hidden" name="s" value="/store/user/index">
                    <div class="am-u-sm-12 am-u-md-3">
                        <div class="am-form-group">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a class="j-export am-btn am-btn-success am-radius"
                                       href="javascript:void(0);" style="display: <?= check_auth('user/export', $uid) ? 'inline-block':'none' ?>;">
                                        <i class="iconfont icon-daochu am-margin-right-xs"></i>用户导出
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-md-9">
                        <div class="am fr">
                            <div class="am-form-group am-fl">

                            </div>

                            <div class="am-form-group am-fl">
                                <select name="level"
                                        data-am-selected="{btnSize: 'sm', placeholder: '等级'}">
                                    <option value=""></option>
                                    <option value="0" <?= $level == 0 ?  'selected' : ''?>
                                    >全部
                                    </option>
                                    <option value="10" <?= $level == 10 ?  'selected' : ''?>
                                    >分销商
                                    </option>
                                    <option value="20" <?= $level == 20 ?  'selected' : ''?>
                                    >经销商
                                    </option>
                                    <option value="30" <?= $level == 20 ?  'selected' : ''?>
                                    >市级代理
                                    </option>
                                </select>
                            </div>
                            <div class="am-form-group am-fl">
                                <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                    <input type="text" class="am-form-field" name="nickName"
                                           placeholder="请输入用户昵称"
                                           value="<?=$nickName  ?>">
                                    <div class="am-input-group-btn">
                                        <button class="am-btn am-btn-default am-icon-search"
                                                type="submit"></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>手机号</th>
                                <th>头像</th>
                                <th>昵称</th>
                                <th>用户余额</th>
                                <th>用户咖豆</th>
                                <th>性别</th>
                                <th>级别</th>
                                <th>上级</th>
                                <th>上上级</th>
                                <th>直属下级数</th>
                                <th>团队人数数</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['user_id'] ?></td>
                                    <td class="am-text-middle"><?= $item['phone'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['avatarUrl'] ?>" width="72" height="72" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['nickName'] ?></td>
                                    <td class="am-text-middle"><?= $item['price'] ?></td>
                                    <td class="am-text-middle"><?= $item['point'] ?></td>
                                    <td class="am-text-middle"><?= $item['gender'] ?></td>
                                    <td class="am-text-middle">
                                        <span class="j-state am-badge x-cur-p level-<?= $item['level']['level'] ?>"
                                              data-id="<?= $item['user_id'] ?>"
                                              data-level="<?= $item['level']['level'] ?>"
                                        >
                                            <?= $item['level']['level_name'] ?>
                                        </span>
                                    </td>
                                    <td class="am-text-middle">
                                        <a href="<?=url('user/index','pid='.$item['parent']['user_id']) ?>"><?=$item['parent']['nickName'] ?></a>
                                    </td>
                                    <td class="am-text-middle">
                                        <a href="<?=url('user/index','ppid='.$item['pparent']['user_id']) ?>"><?=$item['pparent']['nickName'] ?></a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['child_total'] ?></td>
                                    <td class="am-text-middle"><?= $item['trem_count'] ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
<!--                                            <a class="j-recharge tpl-table-black-operation-default"-->
<!--                                               href="javascript:void(0);"-->
<!--                                               data-id="11635"-->
<!--                                               data-balance="0.00">-->
<!--                                                <i class="iconfont icon-qiandai" style="font-size: 12px;"></i>-->
<!--                                                充值-->
<!--                                            </a>-->
                                            <!--<a class="j-delete tpl-table-black-operation-default"
                                               href="javascript:void(0);" style="display: <?=check_auth('user/delete',$uid) ? 'inline-block' : 'none'?>;"
                                               data-id="11635" title="删除">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>-->
                                            <div class="j-opSelect operation-select am-dropdown" style="display: <?=check_auth('order/index,user.price_log/index',$uid) ? 'inline-block' : 'none'?>;">
                                                <button type="button"
                                                        class="am-dropdown-toggle am-btn am-btn-sm am-btn-secondary">
                                                    <span>更多</span>
                                                    <span class="am-icon-caret-down"></span>
                                                </button>
                                                <ul class="am-dropdown-content" data-id="11635">
                                                    <li>
                                                        <a class="am-dropdown-item" target="_blank"
                                                           href="<?=check_url('order/all_list',$uid,['user_id'=>$item['user_id']])?>">用户订单</a>
                                                    </li>
                                                    <li>
                                                        <a class="am-dropdown-item" target="_blank"
                                                           href="<?=check_url('user.price_log/index',$uid,['user_id'=>$item['user_id']])?>">余额明细</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
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
<script id="tpl-update-level" type="text/template">
    <div class="am-padding-top-sm tpl-form-line-form">
        <form class="form-update-level am-form tpl-form-line-form" method="post"
              action="<?=url('user/update_level') ?>">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label">会员等级 </label>
                <div class="am-u-sm-9">
                    <select name="level" required>
                        {{ each level_arr item }}
                        <option value="{{item.id}}" {{ if level == item.id }}selected{{ /if }}>{{item.name}}</option>
                        {{ /each }}
                    </select>
                </div>
                <input type="hidden" name="user_id" value="{{ user_id }}">
            </div>
    </div>
</script>
<script>
    $(function () {

        $('.j-state').click(function () {
            // 验证权限
            if (!<?= check_auth('user/update_level',$uid) ?>) {
                return false;
            }
            var data = $(this).data();
            var data ={
                level_arr:[
                    {id:0, name: '普通会员'},
                    {id:10, name: '分销商'},
                    {id:20, name: '经销商'},
                    {id:30, name: '市级代理'}
                ],
                level: data.level,
                user_id: data.id
            };
            layer.open({
                type: 1
                , title: '修改会员等级'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: template('tpl-update-level', data)
                , success: function (layero) {

                }
                , yes: function (index) {
                    // 表单提交
                    $('.form-update-level').ajaxSubmit({
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
        });
        /**
         * 注册操作事件
         * @type {jQuery|HTMLElement}
         */
        var $dropdown = $('.j-opSelect');
        $dropdown.dropdown();
        $dropdown.on('click', 'li a', function () {
            var $this = $(this);
            var id = $this.parent().parent().data('id');
            var type = $this.data('type');
            if (type === 'delete') {
                layer.confirm('删除后不可恢复，确定要删除吗？', function (index) {
                    $.post("index.php?s=/store/apps.dealer.user/delete", {dealer_id: id}, function (result) {
                        result.code === 1 ? $.show_success(result.msg, result.url)
                            : $.show_error(result.msg);
                    });
                    layer.close(index);
                });
            }
            $dropdown.dropdown('close');
        });
    });
</script>
<style>
    .level-0{
        background-color: #0a6aa1;
    }
    .level-10{
        background-color: #00e359;
    }
    .level-20{
        background-color: #42bde5;
    }
    .level-30{
        background-color: #8e44ad;
    }
</style>

