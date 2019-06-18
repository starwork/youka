<style>
    a{
        color: #666;
    }
</style>
<div class="row-content am-cf index">
    <div class="am-g floor1">
        <div class="am-u-md-3 am-u-sm-6 price-total">
            <div class="widget height-auto">
                <div class="title">今日销售额</div>
                <div class="total"><span>￥</span><?=$total_data['price_total'] ?></div>
                <img src="__ASSETS__/store/img/price_total.png" alt="">
            </div>
        </div>
        <div class="am-u-md-3 am-u-sm-6 order-total">
            <div class="widget height-auto">
                <div class="title">今日订单数</div>
                <div class="total"><?=$total_data['order_total'] ?></div>
                <img src="__ASSETS__/store/img/order_total.png" alt="">
            </div>
        </div>
        <div class="am-u-md-3 am-u-sm-6 goods-total">
            <div class="widget height-auto">
                <div class="title">出售商品数</div>
                <div class="total"><?=$total_data['goods_total'] ?></div>
                <img src="__ASSETS__/store/img/goods_total.png" alt="">
            </div>
        </div>
        <div class="am-u-md-3 am-u-sm-6 user-total">
            <div class="widget height-auto">
                <div class="title">总会员数</div>
                <div class="total"><?=$total_data['user_total'] ?></div>
                <img src="__ASSETS__/store/img/user_total.png" alt="">
            </div>
        </div>
    </div>

    <div class="am-g">
        <div class="am-u-md-6 am-u-sm-12">
            <div class="widget height-auto">
                <div class="title">商品总览 </div>
                <div class="line"></div>
                <div class="am-g item-header">
                    <div class="am-u-sm-3">已下架</div>
                    <div class="am-u-sm-3">已上架</div>
                    <div class="am-u-sm-3">库存紧张</div>
                    <div class="am-u-sm-3">全部商品</div>
                </div>
                <div class="am-g item-body">
                    <div class="am-u-sm-3"><?=$goods_data['down'] ?></div>
                    <div class="am-u-sm-3"><?=$goods_data['up'] ?></div>
                    <div class="am-u-sm-3" style="color: red;"><?=$goods_data['stock'] ?></div>
                    <div class="am-u-sm-3"><?=$goods_data['count'] ?></div>
                </div>
            </div>
        </div>

        <div class="am-u-md-6 am-u-sm-12">
            <div class="widget height-auto">
                <div class="title">用户总览</div>
                <div class="line"></div>
                <div class="am-g item-header">
                    <div class="am-u-sm-3">今日新增</div>
                    <div class="am-u-sm-3">昨日新增</div>
                    <div class="am-u-sm-3">本月新增</div>
                    <div class="am-u-sm-3">会员总数</div>
                </div>
                <div class="am-g item-body">
                    <div class="am-u-sm-3"><?=$user_data['today'] ?></div>
                    <div class="am-u-sm-3"><?=$user_data['yesterday'] ?></div>
                    <div class="am-u-sm-3"><?=$user_data['month'] ?></div>
                    <div class="am-u-sm-3"><?=$user_data['all'] ?></div>
                </div>
            </div>
        </div>
    </div>


    <div class="am-g">
        <div class="am-u-md-6 am-u-sm-12">
            <div class="widget">
                <div class="title">待处理</div>
                <div class="line"></div>
                <div class="am-g item-header">
                    <div class="am-u-sm-3">
                        <img src="__ASSETS__/store/img/order_delivery.png" alt="">
                        <p>待发货</p>
                        <p class="item-body"><?=$order_data['delivery'] ?></p>
                    </div>
                    <div class="am-u-sm-3">
                        <img src="__ASSETS__/store/img/order_pay.png" alt="">
                        <p>待付款</p>
                        <p class="item-body"><?=$order_data['pay'] ?></p>
                    </div>
                    <div class="am-u-sm-3">
                        <img src="__ASSETS__/store/img/order_receipt.png" alt="">
                        <p>待收货</p>
                        <p class="item-body"><?=$order_data['receipt'] ?></p>
                    </div>
                    <div class="am-u-sm-3">
                        <img src="__ASSETS__/store/img/order_comment.png" alt="">
                        <p>已完成</p>
                        <p class="item-body"><?=$order_data['comment'] ?></p>
                    </div>
                </div>
            </div>

            <div class="widget">
                <div class="title">常用菜单</div>
                <div class="line"></div>
                <div class="am-g item-header">
                    <div class="am-u-sm-3">
                        <a href="<?=url('goods/index')?>">
                            <img src="__ASSETS__/store/img/goods.png" alt="">
                            <p>商品管理</p>
                        </a>
                    </div>
                    <div class="am-u-sm-3">
                        <a href="<?=url('banner/index')?>">
                            <img src="__ASSETS__/store/img/banner.png" alt="">
                            <p>首页轮播图</p>
                        </a>
                    </div>
                    <div class="am-u-sm-3">
                        <a href="<?=url('user/index')?>">
                            <img src="__ASSETS__/store/img/user.png" alt="">
                            <p>用户管理</p>
                        </a>
                    </div>
                    <div class="am-u-sm-3">
                        <a href="<?=url('setting/store')?>">
                            <img src="__ASSETS__/store/img/setting.png" alt="">
                            <p>设置</p>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="am-u-md-6 am-u-sm-12">
            <div class="widget">
                <div class="title">热销商品</div>
                <div class="line"></div>
                <table class="am-table table-header">
                    <thead>
                        <tr>
                            <td>商品名称</td>
                            <td>分类</td>
                            <td>销量</td>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                    <?php if (!$hot_goods->isEmpty()): foreach ($hot_goods as $item): ?>
                        <tr>
                            <td><?=$item['goods_name'] ?></td>
                            <td><?=$item['category']['name'] ?></td>
                            <td><?=$item['sales_actual'] ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="3" class="am-text-center">暂无记录</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="am-g">
        <div class="am-u-sm-12">
            <div class="widget">
                <div class="tab am-g">
                    <div class="tab-l am-u-md-6 am-u-sm-12">
                        <div class="tab-item-l title active">
                            订单统计
                            <div class="line"></div>
                        </div>
                        <div class="tab-item-l title">销售统计
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="tab-r am-u-md-6 am-u-sm-12">
                        <div class="tab-item-r am-form am-form-inline ">
                            <div class="am-form-group">
                                <select id="order_select">
                                    <option value="">今日</option>
                                    <option value="week">本周</option>
                                    <option value="month">本月</option>
                                </select>
                            </div>
                            <div class="am-form-group">
                                <input type="text" id="order_date" class="am-form-field" placeholder="日历组件" />
                            </div>
                        </div>
                        <div class="tab-item-r am-form am-form-inline disabled ">
                            <div class="am-form-group">
                                <select id="price_select">
                                    <option value="">今日</option>
                                    <option value="week">本周</option>
                                    <option value="month">本月</option>
                                </select>
                            </div>
                            <div class="am-form-group">
                                <input type="text" id="price_date" class="am-form-field" placeholder="日历组件" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-body am-g">
                        <div class="am-u-md-3 am-u-sm-12" style="border-right: 1px solid #eee;max-width: 200px">
                            <div class="item">
                                <p>本月订单总数</p>
                                <p class="num"><?=$order_data['month_order_count'] ?></p>
                                <p><span <?= $order_data['month_bl'] < 0 ? "style='color:red;'" : ''?>>
                                        <?= $order_data['month_bl'] < 0 ? '-'.$order_data['month_bl'] : '+'.$order_data['month_bl']?>%
                                    </span>同比上月
                                </p>
                            </div>
                            <div class="item">
                                <p>本周订单总数</p>
                                <p class="num"><?=$order_data['week_order_count'] ?></p>
                                <p><span <?= $order_data['week_bl'] < 0 ? "style='color:red;'" : ''?>>
                                        <?= $order_data['week_bl'] < 0 ? ''.$order_data['week_bl'] : '+'.$order_data['week_bl']?>%
                                    </span>同比上周</p>
                            </div>
                        </div>
                        <div class="am-u-md-9 am-u-sm-12">
<!--                            <div class="title">本周订单统计</div>-->
                            <div id="order-echart" style="height:400px"></div>
                        </div>
                        <div class="am-u-sm-0"></div>
                    </div>
                    <div class="tab-body am-g disabled">
                        <div class="am-u-md-3 am-u-sm-12"  style="border-right: 1px solid #eee;max-width: 200px">
                            <div class="item">
                                <p>本月销售总额</p>
                                <p class="num"><?=$order_data['month_order_total'] ?></p>
                                <p><span <?= $order_data['month_price_bl'] < 0 ? "style='color:red;'" : ''?>>
                                        <?= $order_data['month_price_bl'] < 0 ? '-'.$order_data['month_price_bl'] : '+'.$order_data['month_price_bl']?>%
                                    </span>同比上月
                                </p>
                            </div>
                            <div class="item">
                                <p>本周销售总额</p>
                                <p class="num"><?=$order_data['week_order_total'] ?></p>
                                <p><span <?= $order_data['week_price_bl'] < 0 ? "style='color:red;'" : ''?>>
                                        <?= $order_data['week_price_bl'] < 0 ? ''.$order_data['week_price_bl'] : '+'.$order_data['week_price_bl']?>%
                                    </span>同比上周
                                </p>
                            </div>
                        </div>
                        <div class="am-u-md-9 am-u-sm-12 charts">
<!--                            <div class="title">本周订单统计</div>-->
                            <div id="price-echart" style="height:400px;width: 100%"></div>
                        </div>
                        <div class="am-u-sm-0"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="__ASSETS__/laydate/laydate.js"></script>
<script src="https://cdn.bootcss.com/echarts/4.2.1-rc1/echarts.min.js"></script>
<script>
    laydate.render({
        elem: '#order_date'
        ,range: true
        ,done: function (value,date,endDate) {
            getData('order',value);
        }
    })
    laydate.render({
        elem: '#price_date'
        ,range: true
        ,done: function (value,date,endDate) {
            getData('price',value);
        }
    })

    var type = 'order';
    $(".tab-l .tab-item-l").click(function () {
        var index = $(this).index();
        $(".tab-l .tab-item-l").removeClass('active');
        $(this).addClass('active');
        $(".tab-item-r").addClass('disabled');
        $(".tab-item-r:eq("+index+")").removeClass('disabled');
        $(".tab-content .tab-body").addClass('disabled');
        $(".tab-content .tab-body:eq("+index+")").removeClass('disabled');
        if(index == 0){
            getData('order',$('#order_date').val());
        }else{
            getData('price',$('#price_date').val());
        }

        PriceChart.resize();
    })
    
    $("#order_select").change(function () {
        getData('order',$(this).val());
    })
    $("#price_select").change(function () {
        getData('price',$(this).val());
    })


    // 基于准备好的dom，初始化echarts实例
    var OrderChart = echarts.init(document.getElementById('order-echart'));
    var PriceChart = echarts.init(document.getElementById('price-echart'));

    // 指定图表的配置项和数据
    var option = {
        tooltip: {},
        toolbox: {
            show: true,
            showTitle: false,
            feature: {
                mark: {show: true},
                magicType: {show: true, type: ['line', 'bar']}
            }
        },
        xAxis: {
            type : 'category',
            boundaryGap : false,
            data : ['周一','周二','周三','周四','周五','周六','周日']
        },
        yAxis: {},
        series: [{
            name: '',
            type: 'line',
            areaStyle: {normal: {color: '#D5E6FF'}},
            itemStyle:{normal: {color: '#7CD1F8',lineStyle: {color: '#71AAFD'}}},
            data:[220, 182, 191, 234, 290, 330, 310]
        }],
    };
    OrderChart.showLoading();
    PriceChart.showLoading();

    getData('order','');


    // 使用刚指定的配置项和数据显示图表。
    OrderChart.setOption(option);
    PriceChart.setOption(option);
    window.addEventListener("resize",function(){
        OrderChart.resize();
        PriceChart.resize();
    });
    function getData(type,time) {
        if(type == 'order'){
            OrderChart.showLoading();
        }else{
            PriceChart.showLoading();
        }
        $.post("<?=url('index/data') ?>",{ type:type,time:time },function (res) {
            if(type == 'order'){
                $("#order_date").val(res.value);
                OrderChart.hideLoading();
                OrderChart.setOption({
                    xAxis:{
                        data: res.key
                    },
                    series:{
                        name:'订单数',
                        data:res.data
                    }
                })
            }
            if(type == 'price'){
                $("#price_date").val(res.value);
                PriceChart.hideLoading();
                PriceChart.setOption({
                    xAxis:{
                        data: res.key
                    },
                    series:{
                        name:'销售额',
                        data:res.data
                    }
                })
            }
        })
    }
</script>