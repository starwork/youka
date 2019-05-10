<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">物流公司编码表</div>
                </div>
                <div class="tips am-margin-bottom-sm">
                    <div class="pre">
                        <p>友情提示：可使用Ctrl+F键 快速检索</p>
                    </div>
                </div>
                <div class="am-scrollable-horizontal">
                    <table class="regional-table am-table am-table-bordered
                                         am-table-centered am-margin-bottom-xs">
                        <tbody>
                        <tr>
                            <th>公司名称</th>
                            <th>公司编码</th>
                        </tr>
                        <?php foreach ($list as $item): ?>
                        <tr>
                            <td><?=$item['name'] ?></td>
                            <td><?=$item['code'] ?></td>
                        </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

