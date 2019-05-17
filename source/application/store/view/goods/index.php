<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">出售中的商品</div>
                </div>
                <div class="widget-body am-fr">
                    <form class="toolbar-form page_toolbar" action="">
                        <input type="hidden" name="s" value="/store/goods/index">
                        <div class="am-u-sm-12 am-u-md-3">
                            <div class="am-form-group">
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                        <a class="am-btn am-btn-default am-btn-success am-radius"
                                           href="<?= check_url('goods/add',$uid) ?>">
                                            <span class="am-icon-plus"></span> 新增
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-sm-12 am-u-md-9">
                            <div class="am fr">
                                <div class="am-form-group am-fl">
                                    <select name="category_id" data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder: '商品分类', maxHeight: 400}">
                                        <option value=""></option>
                                        <option value="0">全部</option>
                                        <?php if (isset($catgory)): foreach ($catgory as $first): ?>
                                            <option value="<?= $first['category_id'] ?>" <?= $category_id == $first['category_id'] ?  'selected' : ''?> ><?= $first['name'] ?></option>
                                            <?php if (isset($first['child'])): foreach ($first['child'] as $two): ?>
                                                <option value="<?= $two['category_id'] ?>" <?= $category_id == $two['category_id'] ?  'selected' : ''?> >
                                                    　　<?= $two['name'] ?></option>
                                                <?php if (isset($two['child'])): foreach ($two['child'] as $three): ?>
                                                    <option value="<?= $three['category_id'] ?>" <?= $category_id == $three['category_id'] ?  'selected' : ''?> >
                                                        　　　<?= $three['name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            <?php endforeach; endif; ?>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>

                                <div class="am-form-group am-fl">
                                    <select name="goods_status"
                                            data-am-selected="{btnSize: 'sm', placeholder: '商品状态'}">
                                        <option value=""></option>
                                        <option value="10" <?= $goods_status == 10 ?  'selected' : ''?>
                                        >上架
                                        </option>
                                        <option value="20" <?= $goods_status == 20 ?  'selected' : ''?>
                                        >下架
                                        </option>
                                    </select>
                                </div>
                                <div class="am-form-group am-fl">
                                    <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                        <input type="text" class="am-form-field" name="goods_name"
                                               placeholder="请输入商品名称"
                                               value="<?=$goods_name  ?>">
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
                         tpl-table-black am-text-nowrap" id="table">
                            <thead>
                            <tr>
                                <th>商品ID</th>
                                <th>商品图片</th>
                                <th>商品名称</th>
                                <th>商品价格</th>
                                <th>商品分类</th>
                                <th>实际销量</th>
                                <th>商品排序</th>
                                <th>商品状态</th>
                                <th>添加时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['goods_id'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['image'][0]['file_path'] ?>"
                                           title="点击查看大图" target="_blank">
                                            <img src="<?= $item['image'][0]['file_path'] ?>"
                                                 width="50" height="50" alt="商品图片">
                                        </a>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['goods_name'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <?= $item['goods_min_price'] ?>-
                                        <?= $item['goods_max_price'] ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['category']['name'] ?></td>
                                    <td class="am-text-middle"><?= $item['sales_actual'] ?></td>
                                    <td class="am-text-middle"><?= $item['goods_sort'] ?></td>
                                    <td class="am-text-middle">
                                        <span class="j-state am-badge x-cur-p
                                           <?= $item['goods_status']['value'] == 10 ? 'am-badge-success':'' ?>" data-id="<?= $item['goods_id'] ?>" data-state="<?= $item['goods_status']['value'] ?>">
                                               <?= $item['goods_status']['text'] ?>
                                        </span>
                                    </td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <a href="<?= check_url('goods/edit',$uid,
                                                ['goods_id' => $item['goods_id']]) ?>">
                                                <i class="am-icon-pencil"></i> 编辑
                                            </a>
                                            <a href="javascript:;" class="item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['goods_id'] ?>" style="display: <?= check_auth('goods/delete',$uid) ? 'inline-block' : 'none' ?>;">
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
<script>
    $(function () {
        // 商品状态
        $('.j-state').click(function () {
            // 验证权限
            if (!<?= check_auth('goods/state',$uid) ?>) {
                return false;
            }
            var data = $(this).data();
            layer.confirm('确定要' + (parseInt(data.state) === 10 ? '下架' : '上架') + '该商品吗？'
                , {title: '友情提示'}
                , function (index) {
                    $.post("index.php?s=/store/goods/state"
                        , {
                            goods_id: data.id,
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
        var url = "<?= url('goods/delete') ?>";
        $('.item-delete').delete('goods_id', url);

    });
</script>

