<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-body am-fr">
                    <form class="toolbar-form page_toolbar" action="">
                        <div class="am-u-sm-12">
                            <div class="am fr">
                                <div class="am-form-group am-fl">
                                    <select name="level"
                                            data-am-selected="{btnSize: 'sm', placeholder: '会员等级'}">
                                        <option value=""></option>
                                        <option value="10" <?= $level == 10 ?  'selected' : ''?>
                                        >分销商
                                        </option>
                                        <option value="20" <?= $level == 20 ?  'selected' : ''?>
                                        >经销商
                                        </option>
                                        <option value="30" <?= $level == 30 ?  'selected' : ''?>
                                        >市级代理
                                        </option>
                                    </select>
                                </div>
                                <div class="am-form-group am-fl">
                                    <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                        <input type="text" class="am-form-field" name="nickName"
                                               placeholder="请输入会员昵称"
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
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>
                                    <label class="am-checkbox">
                                        <input data-am-ucheck data-check="all" type="checkbox">
                                    </label>
                                </th>
                                <th>用户ID</th>
                                <th>手机号</th>
                                <th>头像</th>
                                <th>昵称</th>
                                <th>用户余额</th>
                                <th>用户积分</th>
                                <th>性别</th>
                                <th>级别</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td>
                                        <label class="am-checkbox">
                                            <input data-am-ucheck data-check="item" data-params='<?= json_encode($item)?>' type="checkbox">
                                        </label>
                                    </td>
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
<script>

    /**
     * 获取已选择的数据
     * @returns {Array}
     */
    function getSelectedData() {
        var data = [];
        $('input[data-check=item]:checked').each(function () {
            data.push($(this).data('params'));
        });
        return data;
    }

    $(function () {

        // 全选框元素
        var $checkAll = $('input[data-check=all]')
            , $checkItem = $('input[data-check=item]')
            , itemCount = $checkItem.length;

        // 复选框: 全选和反选
        $checkAll.change(function () {
            $checkItem.prop('checked', this.checked);
        });

        // 复选框: 子元素
        $checkItem.change(function () {
            if (!this.checked) {
                $checkAll.prop('checked', false);
            } else {
                var checkedItemNum = $checkItem.filter(':checked').length;
                checkedItemNum === itemCount && $checkAll.prop('checked', true);
            }
        });

    });
</script>