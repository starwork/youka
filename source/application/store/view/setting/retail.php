<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">分销设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">复购折扣 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[many_buy]"
                                               value="<?= $values['many_buy'] ?>"  required>
                                    </div>
                                    <small>范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">首次直推开发奖 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[first_money]"
                                               value="<?= $values['first_money'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">分销商设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">一级佣金比例 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[10][first_money]"
                                               value="<?= $values[10]['first_money'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">二级佣金比例 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[10][second_money]"
                                               value="<?= $values[10]['second_money'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">经销商设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">一级佣金比例 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][first_money]"
                                               value="<?= $values[20]['first_money'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">二级佣金比例 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][second_money]"
                                               value="<?= $values[20]['second_money'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">直辖区域维护奖 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][maintain][diect]"
                                               value="<?= $values[20]['maintain']['diect'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">直接育成维护奖 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[20][maintain][indirect]"
                                               value="<?= $values[20]['maintain']['indirect'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">市级代理设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">一级佣金比例 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][first_money]"
                                               value="<?= $values[30]['first_money'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">二级佣金比例 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][second_money]"
                                               value="<?= $values[30]['second_money'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">直辖区域维护奖 </label>
                                <div class="am-u-sm-9">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][maintain][diect]"
                                               value="<?= $values[30]['maintain']['diect'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">直接育成维护奖 </label>
                                <div class="am-u-sm-9 ">
                                    <div class="am-u-sm-7">
                                        <input type="number" class="am-form-field" name="retail[30][maintain][indirect]"
                                               value="<?= $values[30]['maintain']['indirect'] ?>"  required>
                                    </div>
                                    <small>佣金比例范围 0% - 100%</small>
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

    });
</script>
