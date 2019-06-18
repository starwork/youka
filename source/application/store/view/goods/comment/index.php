<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">商品评价</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>商品图片</th>
                                <th>商品名称</th>
                                <th>用户</th>
                                <th>评价内容</th>
                                <th>显示状态</th>
                                <th>评价排序</th>
                                <th>评价时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['comment_id'] ?></td>
                                    <td class="am-text-middle">
                                        <img width="50" height="50" src="<?= $item['goods']['image'][0]['file_path'] ?>" alt="">
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['goods']['goods_name'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['user']['phone'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <?= $item['content'] ?>
                                    </td>
                                    <td class="am-text-middle">
                                            <span class="j-state am-badge x-cur-p <?= $item['status']['value'] == 10 ? 'am-badge-success'
                                                : '' ?>"  data-id="<?= $item['comment_id'] ?>" data-state="<?= $item['status']['value'] ?>">
                                            <?= $item['status']['text'] ?>
                                            </span>
                                    </td>
                                    <td class="am-text-middle"><?= $item['sort'] ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <a href="javascript:;" data-id="<?= $item['comment_id'] ?>" class="j-reply" style="display: <?=check_auth('goods.comment/reply',$uid) ? 'inline-block' : 'none'?>;">
                                                <i class="am-icon-pencil"></i> 回复
                                            </a>
                                            <a href="javascript:;" class="item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['comment_id'] ?>" style="display: <?=check_auth('goods.comment/delete',$uid) ? 'inline-block' : 'none'?>;">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="9" class="am-text-center">暂无记录</td>
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

<script id="tpl-reply" type="text/template">
    <div class="am-padding-top-sm tpl-form-line-form">
        <form class="form-reply am-form tpl-form-line-form" method="post"
              action="<?=url('goods.comment/reply') ?>">
            <div class="am-form-group">
                <div class="am-u-sm-12">
                    <textarea class="" rows="5" name="content"></textarea>
                </div>
                <input type="hidden" name="comment_id" value="{{ comment_id }}">
            </div>
    </div>
</script>
<script>
    $(function () {

        $('.j-reply').click(function () {
            // 验证权限
            if (!<?= check_auth('goods.comment/reply',$uid) ?>) {
                return false;
            }
            var data = $(this).data();
            var data ={
               comment_id: data.id
            };
            layer.open({
                type: 1
                , title: '回复'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: template('tpl-reply', data)
                , success: function (layero) {

                }
                , yes: function (index) {
                    // 表单提交
                    $('.form-reply').ajaxSubmit({
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

        // 状态
        $('.j-state').click(function () {
            // 验证权限
            if (!<?= check_auth('goods.comment/state',$uid) ?>) {
                return false;
            }
            var data = $(this).data();
            layer.confirm('确定要' + (parseInt(data.state) === 10 ? '隐藏' : '显示') + '该评论吗？'
                , {title: '友情提示'}
                , function (index) {
                    $.post("<?=url('goods.comment/state')?>"
                        , {
                            comment_id: data.id,
                            state: Number(!(parseInt(data.state) === 10))
                        }
                        , function (result) {
                            result.code === 1 ? $.show_success(result.msg, result.url)
                                : $.show_error(result.msg);
                        });
                    layer.close(index);
                });

        });
        // 删除元素
        var url = "<?= url('goods.comment/delete') ?>";
        $('.item-delete').delete('id', url);

    });
</script>

