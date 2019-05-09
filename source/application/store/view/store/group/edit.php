<link rel="stylesheet" href="/assets/jstree/dist/themes/default/style.min.css"/>
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">修改角色</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">角色名 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="group[title]"
                                           value="<?= $model['title'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">描述 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="group[description]"
                                           value="<?= $model['description'] ?>">
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">授权</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div id="jstree"></div>
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

<script src="/assets/jstree/dist/jstree.min.js"></script>
<script>
    $(function () {
        var $jstree = $('#jstree');
        $jstree.jstree({
            icon: false,
            plugins: ['checkbox'],
            core : {
                themes: {icons: false},
                checkbox: {
                    keep_selected_style: false
                },
                data: <?=$jstree ?>
            }
        })

        // 读取选中的条目
        $.jstree.core.prototype.get_all_checked = function (full) {
            var obj = this.get_selected(), i, j;
            console.log(obj)
            for (i = 0, j = obj.length; i < j; i++) {
                obj = obj.concat(this.get_node(obj[i]).parents);
            }
            obj = $.grep(obj, function (v) {
                return v !== '#';
            });
            obj = obj.filter(function (itm, i, a) {
                return i === a.indexOf(itm);
            });
            return full ? $.map(obj, $.proxy(function (i) {
                return this.get_node(i);
            }, this)) : obj;
        };
        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm({
            buildData: function () {
                return {
                    group: {
                        rules: $jstree.jstree('get_all_checked')
                    }
                }
            }
        });

    });
</script>
<style>
    .jstree-default .jstree-clicked{
        background: transparent;
    }
    #jstree{
        font-size: 12px;
    }
</style>
