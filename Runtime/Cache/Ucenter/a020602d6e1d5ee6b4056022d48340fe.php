<?php if (!defined('THINK_PATH')) exit();?><div style="position: fixed;
  top: 50%;
  margin-top: -40px;
  left: 0;">
    <button class="btn btn-primary" id="show_qrcode"  title="扫描进入手机版">
            <span class="icon-qrcode" style="font-size: 32px" onclick="">
            </span>
    </button>
    <script>
        $(function () {
            $('#show_qrcode').modalTrigger({custom: '#mobile_content', 'title': '使用移动端访问'});
        });
    </script>
</div>
<div class="hidden">

    <div id="mobile_content">
        <div class="clearfix text-center">
            <?php if(($config["mob"]) == "1"): if(!S('qrcode_mob')){ qrcode(U($mobModule['entry'],null,null,true),'qrcode_mob.png','Uploads/Picture/Qrcode'); S('qrcode_mob',1); } ?>
                <div style="display: inline-block;width: 30%;">
                    <div class="text-center"><a target="_blank" href="<?php echo U($mobModule['entry'],null,null,true);?>"><img style="width: 100px"
                                                  src="/platform/Uploads/Picture/Qrcode/qrcode_mob.png"></a>
                        <div>扫描<a target="_blank" href="<?php echo U($mobModule['entry'],null,null,true);?>">进入手机版</a></div>
                    </div>

                </div><?php endif; ?>
            <?php if(($config["android"]) != ""): if(!S('qrcode_android')){ qrcode($config['android'],'qrcode_android.png','Uploads/Picture/Qrcode'); S('qrcode_android',1); } ?>
                <div style="display: inline-block;width: 30%;">
                    <div class="text-center"><a target="_blank" href="<?php echo ($config["android"]); ?>"><img style="width: 100px"
                                                  src="/platform/Uploads/Picture/Qrcode/qrcode_android.png"></a>

                        <div>扫描<a target="_blank" href="<?php echo ($config["android"]); ?>">下载安卓客户端</a></div>
                    </div>

                </div><?php endif; ?>
            <?php if(($config["ios"]) != ""): if(!S('qrcode_ios')){ qrcode($config['ios'],'qrcode_ios.png','Uploads/Picture/Qrcode'); S('qrcode_ios',1); } ?>
                <div style="display: inline-block;width: 30%;">
                    <div class="text-center"><a target="_blank" href="<?php echo ($config["ios"]); ?>"><img style="width: 100px"
                                                  src="/platform/Uploads/Picture/Qrcode/qrcode_ios.png"></a>

                        <div>扫描<a target="_blank" href="<?php echo ($config["ios"]); ?>">下载IOS客户端</a></div>
                    </div>

                </div><?php endif; ?>
            <hr>
            请通过上述方式使用移动端访问本站。
        </div>
    </div>


</div>