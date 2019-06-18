<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">商城设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    商城名称
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="store[name]"
                                           value="<?= isset($values['name']) ? $values['name'] : '' ?>" required>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">首页分类1 </label>
                                <div class="am-u-sm-9">
                                    <select name="store[category][0]" required
                                            data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder:'请选择商品分类'}">
                                        <option value=""></option>
                                        <?php if (isset($catgory)): foreach ($catgory as $first): ?>
                                            <option value="<?= $first['category_id'] ?>"
                                                <?= $values['category'][0] == $first['category_id'] ? 'selected' : '' ?>>
                                                <?= $first['name'] ?></option>
                                            <?php if (isset($first['child'])): foreach ($first['child'] as $two): ?>
                                                <option value="<?= $two['category_id'] ?>"
                                                    <?= $values['category'][0] == $two['category_id'] ? 'selected' : '' ?>>
                                                    　　<?= $two['name'] ?></option>
                                                <?php if (isset($two['child'])): foreach ($two['child'] as $three): ?>
                                                    <option value="<?= $three['category_id'] ?>"
                                                        <?= $values['category'][0] == $three['category_id'] ? 'selected' : '' ?>>
                                                        　　　<?= $three['name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            <?php endforeach; endif; ?>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">首页分类2 </label>
                                <div class="am-u-sm-9">
                                    <select name="store[category][1]" required
                                            data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder:'请选择商品分类'}">
                                        <option value=""></option>
                                        <?php if (isset($catgory)): foreach ($catgory as $first): ?>
                                            <option value="<?= $first['category_id'] ?>"
                                                <?= $values['category'][1] == $first['category_id'] ? 'selected' : '' ?>>
                                                <?= $first['name'] ?></option>
                                            <?php if (isset($first['child'])): foreach ($first['child'] as $two): ?>
                                                <option value="<?= $two['category_id'] ?>"
                                                    <?= $values['category'][1] == $two['category_id'] ? 'selected' : '' ?>>
                                                    　　<?= $two['name'] ?></option>
                                                <?php if (isset($two['child'])): foreach ($two['child'] as $three): ?>
                                                    <option value="<?= $three['category_id'] ?>"
                                                        <?= $values['category'][1] == $three['category_id'] ? 'selected' : '' ?>>
                                                        　　　<?= $three['name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            <?php endforeach; endif; ?>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
<!--                            <div class="am-form-group">-->
<!--                                <label class="am-u-sm-3 am-form-label">-->
<!--                                    是否显示首页公告-->
<!--                                </label>-->
<!--                                <div class="am-u-sm-9">-->
<!--                                    <label class="am-radio-inline">-->
<!--                                        <input type="radio" name="store[is_notice]" value="1"-->
<!--                                               data-am-ucheck disabled> 开启-->
<!--                                    </label>-->
<!--                                    <label class="am-radio-inline">-->
<!--                                        <input type="radio" name="store[is_notice]" value="0"-->
<!--                                               data-am-ucheck checked disabled> 关闭-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                            </div>-->
<!---->
<!--                            <div class="am-form-group">-->
<!--                                <label class="am-u-sm-3 am-form-label">-->
<!--                                    首页公告内容-->
<!--                                </label>-->
<!--                                <div class="am-u-sm-9">-->
<!--                                    <input type="text" class="tpl-form-input" name="store[notice]" disabled-->
<!--                                           value="">-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">物流查询API</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    快递鸟商户ID
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="store[express][id]"
                                           value="<?= isset($values['express']['id']) ? $values['express']['id'] : '' ?>">
                                    <small>用于查询物流信息，<a href="https://www.kdniao.com/" target="_blank">快递鸟注册</a></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    快递鸟Key
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="store[express][keyid]"
                                           value="<?= isset($values['express']['keyid']) ? $values['express']['keyid'] : '' ?>">
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

<!-- 图片文件列表模板 -->
<script id="tpl-file-item" type="text/template">
    {{ each list }}
    <div class="file-item">
        <img src="{{ $value.file_path }}">
        <input type="hidden" name="{{ name }}" value="{{ $value.file_path }}">
        <i class="iconfont icon-shanchu file-item-delete"></i>
    </div>
    {{ /each }}
</script>

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<script>
    $(function () {
        // 选择图片
        $('.upload-file').selectImages({
            name: 'store[banner]'
        });
        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
