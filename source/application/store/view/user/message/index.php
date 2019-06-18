<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">消息列表</div>
                </div>
                <div class="widget-body am-fr">
                <form class="toolbar-form page_toolbar" action="" id="form-search">
                    <div class="am-u-sm-12 am-u-md-3">
                        <div class="am-form-group">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a class="j-export am-btn am-btn-success am-radius"
                                       href="<?= check_url('user.message/send', $uid) ?>" style="display: <?= check_auth('user.message/send', $uid) ? 'inline-block':'none' ?>;">
                                        <i class="iconfont icon-daochu am-margin-right-xs"></i>发送消息
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>发送用户</th>
                                <th>标题</th>
                                <th>内容</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['user']['nickName'] ? $item['user']['nickName'] : '用户'.$item['user_id'] ?></td>
                                    <td class="am-text-middle"><?= $item['title'] ?></td>
                                    <td class="am-text-middle"><?= $item['content'] ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <a href="javascript:;" class="item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['id'] ?>" style="display: <?= check_auth('user.message/delete',$uid) ? 'inline-block' : 'none' ?>;">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>
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
        // 删除元素
        var url = "<?= url('user.message/delete') ?>";
        $('.item-delete').delete('id', url);
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

