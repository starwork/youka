<div class="row-content am-cf">
    <div class="widget am-cf">
        <div class="widget-body">
            <div class="tpl-page-state am-margin-top-xl">
                <?php switch ($code) {?>
                    <?php case 1:?>
                    <div class="tpl-page-state-title am-text-center">:)</div>
                    <div class="tpl-error-title-info"><p class="success"><?php echo(strip_tags($msg));?></p></div>
                    <?php break;?>
                    <?php case 0:?>
                        <div class="tpl-page-state-title am-text-center">:(</div>
                        <div class="tpl-error-title-info"><p class="error"><?php echo(strip_tags($msg));?></p></div>
                    <?php break;?>
                <?php } ?>
            </div>
            <div class="tpl-page-state-content tpl-error-content">
                <p class="jump">
                    页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
                </p>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>

