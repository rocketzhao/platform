<?php if (!defined('THINK_PATH')) exit();?><div id="right_panel" class="friend_panel visible-xs visible-lg">
        <div id="right_panel_main">
           <!--开始好友板-->
<div id="friend_panel_main">
    <?php $friends=D('Common/Follow')->getAllFriends(); ?>
    <!-- <div>
         <div class="input-group search-friend">
             <input type="text" class="form-control" placeholder="用户名">
             <span class="input-group-addon"><i class="icon icon-search"></i></span>
         </div>
     </div>-->


    <ul id="friend_list" class="friend_list">
       <?php if(is_array($friends)): $i = 0; $__LIST__ = $friends;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$friend): $mod = ($i % 2 );++$i; $friend=query_user(array('avatar64','nickname','space_url','uid'),$friend['follow_who']) ?>
            <li id="friends_li_<?php echo ($friend["id"]); ?>">
                <div class="row">
                    <div class="col-xs-6">
                        <a target="_blank" uid="<?php echo ($friend["uid"]); ?>" title=" <?php echo (op_t($friend["nickname"])); ?>"
                           onclick="talker.start_talk(<?php echo ($friend["uid"]); ?>)">
                            <img src="<?php echo ($friend["avatar64"]); ?>" class="avatar-img" style="width: 45px;">
                        </a>
                    </div>
                    <div class="col-xs-6" style="padding-left: 0">
                        <div>
                            <a style="width: 100%" target="_blank"
                               title=" <?php echo (op_t($friend["nickname"])); ?>"
                               href="<?php echo ($friend["space_url"]); ?>">
                                <?php echo ($friend["nickname"]); ?>
                            </a>
                        </div>
                        <div>
                            <a onclick="talker.start_talk(<?php echo ($friend["uid"]); ?>)"><i title="发起新聊天"
                                                                             class="icon-chat-dot"></i></a>
                        </div>
                    </div>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>

    <script>
        $(function () {
            $('#friend_list').slimScroll({
                height: $('#right_panel').height()
            });
            ucard();
        })

    </script>


</div>
<!--结束聊天版-->
        </div>
</div>
<!--开始聊天板-->
<div id="chat_box" class="row chat_panel weibo_post_box">

    <div class="col-xs-5">
       <!-- <div class="chat-left row">
            <div class="input-group search-friend">
                <span class="input-group-addon">@</span>
                <input type="text" class="form-control" placeholder="用户名">
                <span class="input-group-addon"><i class="icon icon-search"></i></span>
            </div>
        </div>-->
        <div class="row">
            <script>
               $(function(){
                    $('#scrollArea_session').slimScroll({
                        height: '405px',
                        alwaysVisible: false
                    });
                })
            </script>
            <?php $currentSession=D('Common/Talk')->getCurrentSessions(); ?>
            <?php if(count($currentSession) != 0): ?><script>
                    $(function(){
                        talker.open("<?php echo ($currentSession["0"]["id"]); ?>");
                    })
                </script><?php endif; ?>
            <div id="scrollArea_session">
                <div id="scrollContainer_session">
                    <ul id="chat-list" class="chat-list ">
                        <?php if(is_array($currentSession)): $i = 0; $__LIST__ = $currentSession;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$session): $mod = ($i % 2 );++$i;?><li id="chat_li_<?php echo ($session["id"]); ?>">
                                <a target="_blank" onclick="talker.open(<?php echo ($session["id"]); ?>)"
                                   title="<?php echo (op_t($session["title"])); ?>">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <img src="<?php echo ($session["first_user"]["avatar64"]); ?>"
                                                 class="avatar-img"
                                                 style="width: 40px;max-width: 200%">
                                        </div>
                                        <div class="col-xs-8" style="padding-left: 0">
                                            <div class="text-more talk-name" style="width: 90%">
                                                <?php echo ($session["title"]); ?>
                                            </div><span class="btn-close" onclick="talker.exit(<?php echo ($session["id"]); ?>)"><i
                                                title="退出聊天"
                                                class="icon-remove"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="col-xs-7 chat-board">
        <div class="panel_title row"><img id="chat_ico" class="chat_avatar avatar-img" src="/platform/Public/images/coffee.png">

            <div id="chat_title" class="title pull-left text-more"></div>
            <div class="control_btns pull-right"><a><i onclick="$('#talker').hide();"
                                                       class="icon-minus"></i></a><!-- <a
                ><i class="glyphicon glyphicon-off"></i></a>--></div>
        </div>
        <div class="row talk-body ">
            <div id="scrollArea_chat" class="row ">
                <div id="scrollContainer_chat">
                    <div class="text-muted" style="line-height: 258px;text-align: center;font-size: 32px">未发起聊天</div>
                </div>
            </div>

        </div>

        <div class="send_box">
            <input id="chat_id" type="hidden" value="0">
            <?php $talk_self=query_user(array('avatar128')); ?>
            <script>
                var myhead = "<?php echo ($talk_self["avatar128"]); ?>";
            </script>
            <textarea id="chat_content" class="form-control"></textarea>

        </div>


        <div class="row">
            <div class="col-xs-6">
                <button class=" btn btn-danger" onclick="talker.exit()"
                        style="margin: 10px 10px" title="退出聊天"><i class="icon-off"></i>
                </button>
                <!--  <button class=" btn btn-success" onclick="chat_exit()"
                          style="margin: 10px 10px" title="邀请好友"><i class="glyphicon glyphicon-plus"></i>
                  </button>-->
                <a href="javascript:" onclick="insertFace($(this))"><i class="icon-smile"></i></a>
            </div>
            <div class="col-xs-6">

                <button class="pull-right btn btn-primary" onclick="talker.post_message()"
                        style="margin: 10px 10px"> 发送
                </button>
            </div>
            <div id="emot_content" class="emot_content" style="margin-top: -165px;margin-left: -415px;"></div>


        </div>
    </div>

</div>