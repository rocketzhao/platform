<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|管理后台</title>
    <link href="/platform/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">


    <!--zui-->
    <link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/zui/css/zui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/css/admin.css" media="all">
    <link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/css/ext.css" media="all">
    <!--zui end-->

    <!--
        <link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/css/base.css" media="all">
        <link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/css/common.css" media="all"-->
    <link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/css/module.css">
    <link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/css/style.css" media="all">
    <!--<link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">-->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/platform/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/platform/Public/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/platform/Application/Admin/Static/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
    <script type="text/javascript">
        var ThinkPHP = window.Think = {
            "ROOT": "/platform", //当前网站地址
            "APP": "/platform/index.php?s=", //当前项目地址
            "PUBLIC": "/platform/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            'URL_MODEL': "<?php echo C('URL_MODEL');?>"
        }
        var _ROOT_="/platform";
    </script>
</head>
<body>
<style>

</style>
<div class="panel-header">
    <nav class="navbar navbar-inverse admin-bar" role="navigation">
        <div class="navbar-header">
            <a href="<?php echo U('Index/index');?>" class="navbar-brand">
                OpenSNS</a>
        </div>
        <div class="collapse navbar-collapse navbar-collapse-example">
            <ul id="nav_bar" class="nav navbar-nav">
                <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; if(($menu["hide"]) != "1"): ?><li data-id="<?php echo ($menu["id"]); ?>" class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>">
                            <?php if(($menu["icon"]) != ""): ?><i class="icon-<?php echo ($menu["icon"]); ?>"></i>&nbsp;<?php endif; ?>
                            <?php echo ($menu["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <li><a href="javascript:;"  onclick="clear_cache()"><i class="icon-trash"></i> 清空缓存</a></li>
                <li><a target="_blank" href="<?php echo U('Home/Index/index');?>"><i class="icon-copy"></i> 打开前台</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>
                        <?php echo session('user_auth.username');?> <b
                                class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                        <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
                    </ul>
                </li>
                <script>
                    function clear_cache() {
                        var msg = new $.Messager('清理缓存成功。', {placement: 'bottom'});
                        $.get('/cc.php');
                        msg.show()
                    }
                </script>
            </ul>
        </div>
    </nav>

    <div class="admin-title">

    </div>

</div>
<div class="panel-menu">
    <ul class="nav nav-primary nav-stacked">
     
        <?php if(is_array($__MODULE_MENU__)): $i = 0; $__LIST__ = $__MODULE_MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['is_setup'] AND $v['admin_entry']): ?><li>
                    <a  href="<?php echo U($v['admin_entry']);?>" title="<?php echo (text($v["alias"])); ?>" class="text-ellipsis text-center">
                        <i class="icon-<?php echo ($v['icon']); ?>"></i><br/><?php echo ($v["alias"]); ?>
                    </a>
                </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	     
	
		
		 		 <!--li>
                    <a  href="<?php echo U('/admin/issue/contents');?>" title="信息发布" class="text-ellipsis text-center">
                        <i class="icon-th"></i><br/>发布-专辑管理
                    </a>
					</li>
				<li>
                    <a  href="<?php echo U('/admin/user/index');?>" title="用户列表" class="text-ellipsis text-center">
                        <i class="icon-user"></i><br/>用户中心
                    </a>
					</li-->
	                <li>
                    <a  href="<?php echo U('/admin/senglin/index');?>" title="订单管理列表" class="text-ellipsis text-center">
                        <i class="icon-book"></i><br/>配置管理
                    </a>
                  </li>
				  <!--li>
                    <a  href="<?php echo U('/admin/senglin/manage');?>" title="VIP管理" class="text-ellipsis text-center">
                        <i class="icon-list"></i><br/>VIP管理
                    </a>
                </li>
				 <li>
                    <a  href="<?php echo U('/admin/senglin/vipcard');?>" title="冲值管理" class="text-ellipsis text-center">
                        <i class="icon-info"></i><br/>冲值管理 
                    </a>
                </li-->
	
		
		
    </ul>

</div>

<div class="panel-main" style="float:left;">

    <div class="">


    <div class="clearfix " style="">
        <?php if(!empty($__MENU__["child"])): ?><div class="sub_menu_wrapper" style="background: rgb(245, 246, 247); bottom: -10px;top: 0;position: absolute;width: 180px;overflow: auto">
                <div>
                    <nav id="sub_menu" class="menu" data-toggle="menu">
                        <ul class="nav nav-primary">
                            
                                <!--     <?php if(!empty($_extra_menu)): ?>
                                         <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>-->
                                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                                    <?php if(!empty($sub_menu)): ?><li class="show">
                                            <a href="#">
                                                <?php if(!empty($key)): echo ($key); endif; ?>
                                            </a>
                                            <ul class="nav">
                                                <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                                        <a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </li><?php endif; ?>
                                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>

                            

                        </ul>
                    </nav>
                </div>
            </div><?php endif; ?>


        <?php if(!empty($__MENU__["child"])): $col=10; ?>
            <?php else: ?>
            <?php $col=12; endif; ?>
        <div id="main-content" class="" style="padding:10px;padding-left:0;padding-bottom:10px;left: 180px;position:absolute;right: 0;bottom: 0;top: 0;overflow: auto">
           <?php if(($update) == "1"): ?><!--div id="top-alert" class="alert alert-success alert-dismissable" style="position: absolute;left: 50%;margin-left: -150px;width: 300px;box-shadow: rgba(95, 95, 95, 0.4) 3px 3px 3px;z-index:999">
                   <i class="icon-ok-sign" style="font-size: 28px"></i>  &nbsp;&nbsp; 有新版本可更新！<a class="alert-link" href="<?php echo U('Cloud/update');?>">去更新！</a>
                   <button class="close fixed" style="margin-top: 4px;">&times;</button>
               </div--><?php endif; ?>

            <div id="main" style="overflow-y:auto;overflow-x:hidden;">
                
                    <!-- nav -->
                    <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                            <span>您的位置:</span>
                            <?php $i = '1'; ?>
                            <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                                    <?php else: ?>
                                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                                <?php $i = $i+1; endforeach; endif; ?>
                        </div><?php endif; ?>
                    <!-- nav -->
                

                <div class="admin-main-container">
                    
    <div class="main-title">
        <h2>网站设置</h2>
    </div>
    <div class="tab-wrap with-padding">
        <ul class="nav nav-secondary">
            <?php if(is_array(C("CONFIG_GROUP_LIST"))): $i = 0; $__LIST__ = C("CONFIG_GROUP_LIST");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?><li
                <?php if(($id) == $key): ?>class="active"<?php endif; ?>
                ><a href="<?php echo U('?id='.$key);?>"><?php echo ($group); ?>配置</a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>

    </div>
    <div class="tab-content with-padding">
    <div class="col-md-12">
        <form action="<?php echo U('save');?>" method="post" class="form-horizontal">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;?><div class="form-item">
                    <label class="item-label"><?php echo ($config["title"]); ?><span class="check-tips">（<?php echo ($config["remark"]); ?>）</span>
                    </label>

                    <div class="controls">
                        <?php switch($config["type"]): case "0": ?><input type="text" class="text input-small form-control" name="config[<?php echo ($config["name"]); ?>]" style="width: 180px"
                                       value="<?php echo ($config["value"]); ?>"><?php break;?>
                            <?php case "1": ?><input type="text" class="text input-large form-control" name="config[<?php echo ($config["name"]); ?>]" style="width: 400px"
                                       value="<?php echo ($config["value"]); ?>"><?php break;?>
                            <?php case "2": ?><label class="textarea input-large">
                                    <textarea name="config[<?php echo ($config["name"]); ?>]" class="form-control" style="width: 400px;height: 200px" ><?php echo ($config["value"]); ?></textarea>
                                </label><?php break;?>
                            <?php case "3": ?><label class="textarea input-large">
                                    <textarea name="config[<?php echo ($config["name"]); ?>]" class="form-control" style="width: 400px;min-height: 120px;" ><?php echo ($config["value"]); ?></textarea>
                                </label><?php break;?>
                            <?php case "4": ?><select name="config[<?php echo ($config["name"]); ?>]" class="form-control" style="width: auto">
                                    <?php $_result=parse_config_attr($config['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"
                                        <?php if(($config["value"]) == $key): ?>selected<?php endif; ?>
                                        ><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select><?php break;?>
                            <?php case "5": ?><!--增加富文本和非明文-->

                                <?php echo W('Common/Ueditor/editor',array($config['name'],'config['.$config['name'].']',$config['value'],'500px','300px')); break;?>
                            <?php case "6": ?><input type="password" class="text input-large form-control" style="width:400px;" name="config[<?php echo ($config["name"]); ?>]" autoComplete="off"
                                       value="<?php echo ($config["value"]); ?>"><?php break;?>
                            <?php case "7": ?><script type="text/javascript" charset="utf-8" src="/platform/Public/js/ext/webuploader/js/webuploader.js"></script>
                                <link href="/platform/Public/js/ext/webuploader/css/webuploader.css" type="text/css" rel="stylesheet">
                                <div class="controls">
                                    <div id="upload_single_image_<?php echo ($config["name"]); ?>" style="padding-bottom: 5px;">选择图片</div>
                                    <input class="attach" type="hidden" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config['value']); ?>"/>
                                    <div class="upload-img-box">
                                        <div class="upload-pre-item popup-gallery">
                                            <?php if(!empty($config["value"])): ?><div class="each">
                                                    <a href="<?php echo (get_cover($config["value"],'path')); ?>" title="点击查看大图">
                                                        <img src="<?php echo (get_cover($config["value"],'path')); ?>">
                                                    </a>
                                                    <div class="text-center opacity del_btn" ></div>
                                                    <div onclick="admin_image.removeImage($(this),'<?php echo ($config["value"]); ?>')"  class="text-center del_btn">删除</div>
                                                </div><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(function () {
                                        var uploader_<?php echo ($config["name"]); ?>= WebUploader.create({
                                            // 选完文件后，是否自动上传。
                                            auto: true,
                                            // swf文件路径
                                            swf: 'Uploader.swf',
                                            // 文件接收服务端。
                                            server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
                                            // 选择文件的按钮。可选。
                                            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                                            pick: '#upload_single_image_<?php echo ($config["name"]); ?>',
                                            // 只允许选择图片文件。
                                            accept: {
                                                title: 'Images',
                                                extensions: 'gif,jpg,jpeg,bmp,png',
                                                mimeTypes: 'image/*'
                                            }
                                        });
                                        uploader_<?php echo ($config["name"]); ?>.on('fileQueued', function (file) {
                                            uploader_<?php echo ($config["name"]); ?>.upload();
                                        });
                                        /*上传成功*/
                                        uploader_<?php echo ($config["name"]); ?>.on('uploadSuccess', function (file, data) {
                                            if (data.status) {
                                                $("[name='config[<?php echo ($config["name"]); ?>]']").val(data.id);
                                                $("[name='config[<?php echo ($config["name"]); ?>]']").parent().find('.upload-pre-item').html(
                                                        ' <div class="each"><a href="'+ data.path+'" title="点击查看大图"><img src="'+ data.path+'"></a><div class="text-center opacity del_btn" ></div>' +
                                                                '<div onclick="admin_image.removeImage($(this),'+data.id+')"  class="text-center del_btn">删除</div></div>'
                                                );
                                                uploader_<?php echo ($config["name"]); ?>.reset();
                                            } else {
                                                updateAlert(data.info);
                                                setTimeout(function () {
                                                    $('#top-alert').find('button').click();
                                                    $(that).removeClass('disabled').prop('disabled', false);
                                                }, 1500);
                                            }
                                        });
                                    })
                                </script><?php break;?>


                            <?php case "8": $config['value_array'] = explode(',', $config['value']); $config['extra'] = explode("\r\n", $config['extra']); $config['opt'] = array(); foreach( $config['extra'] as &$val){ $val = explode(':', $val); $config['opt'][$val[0]] = $val[1]; } ?>
                                <?php if(is_array($config["opt"])): $i = 0; $__LIST__ = $config["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = in_array($key,$config['value_array']) ? 'checked' : ''; $inputId = "id_$config[name]_$key"; ?>
                                    <label for="<?php echo ($inputId); ?>">
                                        <input type="checkbox" value="<?php echo ($key); ?>" id="<?php echo ($inputId); ?>" class="oneplus-checkbox" data-field-name="<?php echo ($config["name"]); ?>" <?php echo ($checked); ?>/>
                                        <?php echo (htmlspecialchars($option)); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
                                <input type="hidden" name="config[<?php echo ($config["name"]); ?>]" class="oneplus-checkbox-hidden"
                                       data-field-name="<?php echo ($config["name"]); ?>" value="<?php echo ($config["value"]); ?>"/>

                                    <script>
                                        $(function () {
                                            function implode(x, list) {
                                                var result = "";
                                                for (var i = 0; i < list.length; i++) {
                                                    if (result == "") {
                                                        result += list[i];
                                                    } else {
                                                        result += ',' + list[i];
                                                    }
                                                }
                                                return result;
                                            }

                                            $('.oneplus-checkbox').change(function (e) {
                                                var fieldName = $(this).attr('data-field-name');
                                                var checked = $('.oneplus-checkbox[data-field-name=' + fieldName + ']:checked');
                                                var result = [];
                                                for (var i = 0; i < checked.length; i++) {
                                                    var checkbox = $(checked.get(i));
                                                    result.push(checkbox.attr('value'));
                                                }
                                                result = implode(',', result);
                                                $('.oneplus-checkbox-hidden[data-field-name=' + fieldName + ']').val(result);
                                            });
                                        })
                                    </script><?php break; endswitch;?>

                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="form-item">
                <label class="item-label"></label>

                <div class="controls">
                    <?php if(empty($list)): ?><button type="submit" disabled class="btn submit-btn disabled"
                                target-form="form-horizontal">确 定
                        </button>
                        <?php else: ?>
                        <button type="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定
                        </button><?php endif; ?>

                    <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
                </div>
            </div>
        </form>
    </div>

</div>

                </div>

            </div>
        </div>
    </div>
    </div>
</div>



<?php if($report){ ?>
<div  class="report_feedback" title="填写四格体验报告" data-toggle="modal" data-target="#myModal">
    <div class="report_icon" ></div>
    <span class="label label-badge label-danger report_point">1</span>
</div>




<div class="modal fade in" id="myModal" aria-hidden="false"  style="display: none">
    <div class="modal-dialog" >
        <div class="modal-content">
            <form class="report_form"  action="<?php echo U('admin/admin/submitReport');?>" method="post">


            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title">四格体验报告</h4>
            </div>

            <div class="modal-body">

                    <div class="row">
                        <!-- 帖子分类 -->
                        <div class="col-sm-12">
<div>感谢您使用我们的产品，希望您的认真反馈有助于我们改善产品。</div>

                                <label class="item-label">我的更新心情</label>
                            <div>
                                <select name="q1" class="report-select" style="width:400px;">
                                    <option value="0">请选择</option>
                                    <option>开心</option>
                                    <option>悲伤</option>
                                    <option>超有爱</option>
                                    <option>期待</option>
                                </select>
                        </div>

                                <label class="item-label">我选择的最有爱更新</label>
                            <div>
                                <select name="q2" class="report-select" style="width:400px;">
                                    <option value="0">请选择</option>
                                    <?php if(is_array($this_update)): $i = 0; $__LIST__ = $this_update;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><option value="<?php echo (htmlspecialchars($option)); ?>"><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>

                                <label class="item-label">我选择的最不给力更新
                                </label>
                            <div>
                                <select name="q3" class="report-select" style="width:400px;">
                                    <option value="0">请选择</option>
                                    <?php if(is_array($this_update)): $i = 0; $__LIST__ = $this_update;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><option value="<?php echo (htmlspecialchars($option)); ?>"><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>


                                <label class="item-label">我选择的期待功能
                                </label>
                            <div>
                                <select name="q4" class="report-select" style="width:400px;">
                                    <option value="0">请选择</option>
                                    <?php if(is_array($future_update)): $i = 0; $__LIST__ = $future_update;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><option value="<?php echo (htmlspecialchars($option)); ?>"><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                    </div>
                    </div>
            </div>
            <div class="modal-footer">
                <?php if(strval($report['url'])!=''){ ?>
                <a class="pull-left" href="<?php echo ($report['url']); ?>" target="_blank" >查看更新详情</a>
                <?php } ?>
                <button type="submit" class="btn ajax-post" target-form="report_form">确定</button>
            </div>

            </form>
        </div>
    </div>
</div>



<?php } ?>


<script>
    $(function () {
        var config = {
            '.chosen-select'           : {search_contains: true, drop_width: 400,no_results_text:'找不到匹配的选项'},
            '.report-select'           : {search_contains: true, width: '400px',no_results_text:'找不到匹配的选项'}
        };
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

    });
</script>


<script src="/platform/Application/Admin/Static/zui/lib/chosen/chosen.js"></script>
<link href="/platform/Application/Admin/Static/zui/lib/chosen/chosen.css" type="text/css" rel="stylesheet">




<!-- 内容区 -->

<!-- /内容区 -->
<script type="text/javascript">
    (function () {
        var ThinkPHP = window.Think = {
            "ROOT": "/platform", //当前网站地址
            "APP": "/platform/index.php?s=", //当前项目地址
            "PUBLIC": "/platform/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            'URL_MODEL': "<?php echo C('URL_MODEL');?>"
        }
    })();
</script>
<script type="text/javascript" src="/platform/Public/static/think.js"></script>

<!--zui-->
<script type="text/javascript" src="/platform/Application/Admin/Static/js/common.js"></script>
<script type="text/javascript" src="/platform/Application/Admin/Static/js/com/com.toast.class.js"></script>
<script type="text/javascript" src="/platform/Application/Admin/Static/zui/js/zui.js"></script>
<script type="text/javascript" src="/platform/Application/Admin/Static/zui/lib/autotrigger/autotrigger.min.js"></script>
<!--zui end-->
<link rel="stylesheet" type="text/css" href="/platform/Application/Admin/Static/js/kanban/kanban.css" media="all">
<script type="text/javascript" src="/platform/Application/Admin/Static/js/kanban/kanban.js"></script>
<script type="text/javascript">
    +function () {
        var $window = $(window), $subnav = $("#subnav"), url;
        $window.resize(function () {
            $("#main").css("min-height", $window.height() - 130);
        }).resize();


        // 导航栏超出窗口高度后的模拟滚动条
        var sHeight = $(".sidebar").height();
        var subHeight = $(".subnav").height();
        var diff = subHeight - sHeight; //250
        var sub = $(".subnav");
        if (diff > 0) {
            $(window).mousewheel(function (event, delta) {
                if (delta > 0) {
                    if (parseInt(sub.css('marginTop')) > -10) {
                        sub.css('marginTop', '0px');
                    } else {
                        sub.css('marginTop', '+=' + 10);
                    }
                } else {
                    if (parseInt(sub.css('marginTop')) < '-' + (diff - 10)) {
                        sub.css('marginTop', '-' + (diff - 10));
                    } else {
                        sub.css('marginTop', '-=' + 10);
                    }
                }
            });
        }
    }();
    highlight_subnav("<?php echo U('Admin'.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$_GET);?>")
</script>





</body>
</html>