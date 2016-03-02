<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-26
 * Time: 上午10:43
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */


/**
 * send_weibo  发布微博
 * @param $content
 * @param $type
 * @param string $feed_data
 * @param string $from
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_mob_weibo($content,$type,$feed_data = '', $from=''){

    $uid = is_login();
    D('Topic')->addTopic($content);
    $weibo_id = D('Weibo')->addWeibo($uid, $content, $type, $feed_data,$from);
    if (!$weibo_id) {
        return false;
    }
    action_log('add_weibo', 'weibo', $weibo_id, $uid); //发微博
    $uids = get_at_uids($content);
    send_at_message($uids, $weibo_id, $content);
    clean_query_user_cache(is_login(), array('weibocount'));
    return $weibo_id;
}

/**
 * send_comment  发布评论
 * @param $weibo_id
 * @param $content
 * @param int $comment_id
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_comment($weibo_id, $content, $comment_id = 0){
    $uid = is_login();

    $result = D('WeiboComment')->addComment($uid, $weibo_id, $content, $comment_id);
    //行为日志
    action_log('add_weibo_comment', 'weibo_comment', $result, $uid);
    //通知微博作者
    $weibo =D('Weibo')->getWeiboDetail($weibo_id);
    D('Common/Message')->sendMessage($weibo['uid'], '评论消息' , "评论内容：$content",  'Weibo/Index/weiboDetail',array('id' => $weibo_id), is_login(), 1);
    //通知回复的人
    if ($comment_id) {
        $comment = D('WeiboComment')->getComment($comment_id);
        if ($comment['uid'] != $weibo['uid']) {
            D('Common/Message')->sendMessage($weibo['uid'], '评论消息' ,  "回复内容：$content",  'Weibo/Index/weiboDetail',array('id' => $weibo_id), is_login(), 1);
        }
    }

    $uids = get_at_uids($content);
    $uids = array_subtract($uids, array($weibo['uid'], $comment['uid']));
    send_at_message($uids, $weibo_id, $content);
    return $result;
}



/*function send_comment_message($uid, $id, $message,$pid){

 //   $title = '评论消息';
    $from_uid = is_login();

    $type = 1;
    switch($pid){
        case 'weibo':
            $title = '评论消息';
            D('Common/Message')->sendMessage($uid,$title, $message,  'Weibo/Index/weiboDetail',array('id' => $id), $from_uid, $type);
            break;
        case 'issue':
            if($uid==is_login()){
                break;
            }
            $title =get_nickname($from_uid). '评论了您';
            D('Common/Message')->sendMessage($uid,$title, $message,  'Issue/Index/IssueContentDetail',array('id' => $id), $from_uid, $type);
            break;
        case 'blog':
            if($uid==is_login()){
                break;
            }
            $title =get_nickname($from_uid). '评论了您';
            D('Common/Message')->sendMessage($uid,$title, $message,  'news/index/detail',array('id' => $id), $from_uid, $type);
            break;
    }

}*/


/**
 * send_at_message  发送@消息
 * @param $uids
 * @param $weibo_id
 * @param $content
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_at_message($uids, $weibo_id, $content)
{
    $my_username = query_user('nickname');
    foreach ($uids as $uid) {
        $message = '内容：' . $content;
        $title = $my_username . '@了您';
        $fromUid = get_uid();
        $messageType = 1;
        D('Common/Message')->sendMessage($uid, $title, $message, 'Weibo/Index/weiboDetail',array('id' => $weibo_id), $fromUid, $messageType);
    }
}
function parse_topic($content){
    //找出话题
    $topic = get_topic($content);

    //将##替换成链接
    foreach ($topic as $e) {
        $tik = D('Weibo/Topic')->where(array('name' => $e))->find();

        //没有这个话题的时候创建这个话题
        if($tik){
            //D('Weibo/Topic')->add(array('name'=> $e));
            $space_url = U('Weibo/Topic/index',array('topk'=>urlencode($e)));
            $content = str_replace("#$e#", "<a  href=\"$space_url\" target=\"_blank\">#$e# </a>", $content);
        }
    }

    //返回替换的文本
    return $content;
}

function get_topic($content){
    //正则表达式匹配
    $topic_pattern = "/#([^\\#|.]+)#/";
    preg_match_all($topic_pattern, $content, $topics);

    //返回话题列表
    return array_unique($topics[1]);
}

function group_is_exist($group_id){
    $group =  D('Group/Group')->getGroup($group_id);
    return $group ? true : false;
}

function get_group_admin($group_id){
    return D('Group/GroupMember')->getGroupAdmin($group_id);
}

//MOB模块得到用户头衔
function mob_get_head_title($user_id){
    $user_title = D('RankUser')->where(array('status' => 1, 'uid' => $user_id))->select();
    foreach($user_title as &$v){
        $v['title'] = D('Rank')->where(array( 'id' => $v['rank_id']))->find();
        $v['logo']= getThumbImageById($v['title']['logo']);
    }
    //  dump($user_title);exit;
    return $user_title;
}

function parse_weibo_mobile_content($content)
{
    $content = shorten_white_space($content);
    $content = op_t($content,false);
    $content = parse_expression($content);
    $content = parse_url_mobile_link($content);
    $content = parseWeiboContent($content);
    $content = parse_at_mob_users($content);
    return $content;
}
function parse_url_mobile_link($content)
{
    $content = preg_replace("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
        "'<a class=\"label label-badge\" href=\"$1\" target=\"_blank\"><i class=\"am-icon-link\" title=\"$1\"></i></a>$4'", $content
    );
    return $content;
}
function parse_at_mob_users($content,$disabel_hight=false)
{
    $content = $content . ' ';
    //找出被AT的用户
    $at_usernames = get_at_usernames($content);
    //将@用户替换成链接
    foreach ($at_usernames as $e) {
        $user = D('Member')->where(array('nickname' => $e))->find();
        if ($user) {
            $query_user = query_user(array('space_mob_url','avatar32'), $user['uid']);

            if(modC('HIGH_LIGHT_AT',1,'Weibo') && !$disabel_hight){
                $content = str_replace("@$e", " <a class='user-at hl ' ucard=\"$user[uid]\" href=\"$query_user[space_mob_url]\"><img class='am-circle' src=\"$query_user[avatar32]\">@$e </a> ", $content);
            }else{
                $content = str_replace("@$e", " <a ucard=\"$user[uid]\" href=\"$query_user[space_url]\">@$e </a> ", $content);
            }

        }
    }
    //返回替换的文本
    return $content;
}

function check_verify($code, $id = 1){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}




function local_comment_textarea($param=array()){
    $html = '<div class="am-form-group weibo_post_box am-cf" style="padding: 10px"><h6>评论</h6>';
    if (!is_login()) {
        $html.='<div  style=" position: relative; "><div class="am-g am-text-center" style="position: absolute;padding: 50px;"><span> 请先<a href="'.U('Mob/member/index').'">登录</a>
        后再评论</span></div> <textarea  rows="5"   disabled style="height: 8em; width: 100%;"></textarea></div>';
    }else{
        $html.='<textarea class="content" rows="5" id="comment_content_text"   style="height: 8em; width: 100%;"></textarea>';
    }

    $html.=' <div class="am-cf" style="margin: 5px 0px"><a href="javascript:" data-url=" '.U('Core/Expression/getSmile').'" onclick="insertFace($(this))"><img src="'.C('TMPL_PARSE_STRING.__IMG__').'/bq.png"></a> </div><div id="emot_content" class="emot_content"></div>';
    $html .= '<p>';
    $html.= '<button style="float: right"   data-role="do_local_comment" data-url="'.U('mob/base/addLocalComment',array('uid'=>$param['uid'],'count_model'=>$param['count_model'],'count_field'=>$param['count_field'])).'" data-this-url="'.$param['this_url'].'" data-path="'.$param['path'].'"  data-extra="'.http_build_query($param['extra']).'"  type="button"  class="am-btn-warning am-btn am-btn-block am-round ">提交</button>';
    $html .= '</p></div>';
    return $html;

}



function get_local_comment_list($path,$page=1){
    $html = '<div class="localcomment_list">';
    $html .=  A('Mob/Base')->getLocalCommentList($path,$page);
    $html .='</div>';
    $html.='    <div class="am-list-news-ft"><a class=" am-btn am-btn-secondary am-btn-block" href="javascript:" data-role="show_more_localcomment" data-path="'.$path.'">查看更多 &raquo;</a> </div>';
    return $html;
}


function mobU($url = '', $vars = '', $suffix = true, $domain = false){
    $url = preg_replace("/(?<=#)[\s\S]*$/","",$url);
    $link =  require('./Application/Mob/Conf/router.php');
    $url_mob = $link['router'][$url];
    return U($url_mob, $vars , $suffix, $domain);
}
//群组
function limit_picture_count($content){
    return   D('ContentHandler')->limitPicture($content,modC('GROUP_POST_IMG_COUNT',10,'GROUP'));
}

function get_group_name($group_id){
    $group =  D('Group')->getGroup($group_id);
    return $group['title'];
}