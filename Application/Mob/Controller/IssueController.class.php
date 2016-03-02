<?php


namespace Mob\Controller;

use Think\Controller;
use Mob\Api\MobApi;


class IssueController extends BaseController
{
    public function _initialize()
    {
        $this->_top_menu_list = array(
            'left' => array(
                array('type' => 'home', 'href' => U('Mob/user/main')),
                array('type' => 'message'),
            ),
            'center' => array('title' => '首页')
        );
        if (is_login()) {
           // if (check_auth('addIssueContent')) {
                $this->_top_menu_list['right'][] = array('type' => 'edit', 'href' => U('Mob/Issue/orderlist'));
           /* } else {*/
           //     $this->_top_menu_list['right'][] = array('type' => 'edit', 'info' => '你没有发布权限！');
            //}
        } else {
            $this->_top_menu_list['right'][] = array('type' => 'edit', 'info' => '登录后才能操作！');
        }
        $this->setMobTitle('活动报名');
        $this->assign('top_menu_list', $this->_top_menu_list);
    }

    /**
     *
	 *	 显示主页专辑
     */
    public function index()
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $totalCount = D('Issue_content')->where(array('status' => 1,'flag'=>0))->count();
        $issue = D('Issue_content')->where(array('status' => 1,'flag'=>0))->page($aPage, $aCount)->order('create_time desc')->select();

        foreach ($issue as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id'], 200, 150);
        }
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }

        $this->assign("issue", $issue);
        $this->assign("pid", $pid);
		//dump($issue);
        $this->display();
    }
	
	
	    public function orderlist()
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $totalCount = D('Issue_content')->where(array('status' => 1,'flag'=>1))->count();
        $issue = D('Issue_content')->where(array('status' => 1,'flag'=>1))->page($aPage, $aCount)->order('create_time desc')->select();
		
   
		
        foreach ($issue as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id'], 200, 150);
		    $v['count']=D('user_member')->where(array('IssueId'=>$v['id']))->sum('count')*1;
        }
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }
       
	 
        $this->assign("issue", $issue);
        $this->assign("pid", $pid);
	//	dump($issue);
        $this->display();
    }
	

	    public function dateorder()
    {
        if(is_login()){
		
	
      // 
	    $uid=get_uid();
       //$issue = M('user_member')->field('lesson_date,title,IssueId')->distinct(true)->where(array('uid' => $uid,'flag'=>1))->select();
	   $query = M('user_member')->field('IssueId')->distinct(true)->where(array('uid' => $uid,'flag'=>1))->select();
	   $i=0;
	   $inquery='';
	   foreach ($query as $v){

	     if($i == 0){
	      $inquery= $query[$i]['IssueId'];
		  }else{		   
	      $inquery= $inquery.','.$query[$i]['IssueId'];   
		  }
		  $i++;
		  } 
	   
     //  var_dump($inquery);
	
	   $order_query['Issueid']=array('in',$inquery);
	   $issue = M('dateorder')->join('JOIN ocenter_issue_content ON ocenter_dateorder.Issueid = ocenter_issue_content.id')->group('lesson_date')->where($order_query)->field('lesson_date,GROUP_CONCAT(`title`) as `title`,GROUP_CONCAT(`Issueid`) as `Issueid`,GROUP_CONCAT(`desc`) as `desc`,GROUP_CONCAT(`owner`) as `owner`,GROUP_CONCAT(`issue_addr`) as `issue_addr`,GROUP_CONCAT(timedesc) as timedesc')->select();
	   
	 //   select id,group_concat(name) from aa group by id;
	// dump(M()->getlastsql());
	//  var_dump($query);
	    $totalCount = D('user_member')->where(array('uid' => $uid))->count();
		}else{
		$totalCount = D('user_member')->count();
	    $issue = M('dateorder')->join('JOIN ocenter_issue_content ON ocenter_dateorder.Issueid = ocenter_issue_content.id')->group('lesson_date')->field('lesson_date,GROUP_CONCAT(`title`) as `title`,GROUP_CONCAT(`Issueid`) as `Issueid`,GROUP_CONCAT(`desc`) as `desc`,GROUP_CONCAT(`owner`) as `owner`,GROUP_CONCAT(`issue_addr`) as `issue_addr`,GROUP_CONCAT(timedesc) as timedesc')->select();
     //   dump(M()->getlastsql());
	/// var_dump($issue);
		}	
		
		$i=0;
        foreach ($issue as $v) {
            $dates[$i] = strtotime($issue[$i]['lesson_date']).'000';
			$date[$i] = $issue[$i]['lesson_date'];
			$IssueId[$i] = $issue[$i];
			$title[$i] = $issue[$i]['title'];
			if (strlen($issue[$i]['desc']) >0) {
			$IssueId[$i]['desc']=$issue[$i]['desc'];}else{
			$IssueId[$i]['desc']='无';
			}
			$IssueId[$i]['issue_time']=$issue[$i]['lesson_date'].' '.$issue[$i]['timedesc'];
			if (strlen($issue[$i]['owner']) >0) {
			$IssueId[$i]['owner']=$issue[$i]['owner'];}else{
			$IssueId[$i]['owner']='未记录';
			}
			
		    $i++;	
        }
       //  dump($IssueId);
	     
	    $this->assign("count", $totalCount);
        $this->assign("date", json_encode($date));
		$this->assign("dates", json_encode($dates));
        $this->assign("issue", json_encode($issue));
		$this->assign("title", $title);
		$this->assign("issueid", json_encode($IssueId));
	//	dump($issue);
        $this->display();
	
    }
		
	
	
	
	
    /**
     * 查看更多，加载更多专辑
     */
    public function addMoreIssue()
    {
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $issue = D('Issue_content')->where(array('status' => 1,))->page($aPage, $aCount)->order('create_time desc')->select();


        foreach ($issue as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id'], 200, 150);
        }
        if ($issue) {
            $data['html'] = "";
            foreach ($issue as $val) {
                $this->assign("vo", $val);
                $data['html'] .= $this->fetch("_issuelist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }

        $this->ajaxReturn($data);
    }


    /**
     * @param $id
     * 专辑详情页
     */
    public function issueDetail($id)
    {
        // dump($id);exit;
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $this->setTopTitle('内容详情');
        $map['id'] = array('eq', $id);
        $mapl['row_id'] = array('eq', $id);;
        $issuedetail = D('Issue_content')->where($map)->find();

        $issuedetail['meta']['description'] = mb_substr($issuedetail['content'], 0, 50, 'UTF-8');//取得前50个字符
        $issuedetail['meta']['keywords'] = D('Issue')->where(array('id' => $issuedetail['issue_id']))->field('title')->find();

        $totalCount = D('Local_comment')->where(array('status' => 1, $mapl))->count();
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }
        $issuecomment = D('Local_comment')->where(array('status' => 1, $mapl))->page($aPage, $aCount)->order('create_time desc')->select();
        D('IssueContent')->where(array('id' => $id))->setInc('view_count');//查看数加1

        $support['appname'] = 'Issue';                              //查找需要判断是否点赞的数据
        $support['table'] = 'issue_content';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        $issue_uid = D('IssueContent')->where(array('status' => 1, 'id' => $id))->find();//根据微博ID查找专辑发送人的UID


        $issuedetail['user'] = query_user(array('nickname', 'avatar32', 'uid'), $issuedetail['uid']);


        //获得原图
        $bi = M('Picture')->where(array('status' => 1))->getById($issuedetail['cover_id']);
        if (!is_bool(strpos($bi['path'], 'http://'))) {
            $issuedetail['cover_url'] = $bi['path'];
        } else {
            $issuedetail['cover_url'] = getRootUrl() . substr($bi['path'], 1);
        }

        if (in_array($issuedetail['id'], $is_zan)) {                         //判断是否已经点赞
            $issuedetail['is_support'] = '1';
        } else {
            $issuedetail['is_support'] = '0';
        }
        if (is_administrator(get_uid()) || $issue_uid['uid'] == get_uid()) {                                     //如果是管理员，则可以删除评论
            $issuedetail['is_admin_or_mine'] = '1';
        } else {
            $issuedetail['is_admin_or_mine'] = '0';
        }


        foreach ($issuecomment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id']);
            $v['content'] = parse_weibo_mobile_content($v['content']);

        }
        $this->setMobTitle($issuedetail['user']['nickname'], $issuedetail['title']);
        $this->setMobDescription($issuedetail['meta']['description']);
        $this->setMobKeywords($issuedetail['title'], $issuedetail['user']['nickname'], $issuedetail['meta']['keywords']['title']);
//dump($issuedetail);exit;
        $this->assign('issuedetail', $issuedetail);
        $this->assign('issuecomment', $issuecomment);
        $this->assign("pid", $pid);
        $this->display();

    }

    public function addMoreIssueComment()
    {
        $aId = I('post.id', '', 'op_t');
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');

        $map['row_id'] = array('eq', $aId);

        $issuecomment = D('Local_comment')->where(array('status' => 1, $map))->page($aPage, $aCount)->order('create_time desc')->select();
        foreach ($issuecomment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id']);
            $v['content'] = parse_weibo_mobile_content($v['content']);

        }
        if ($issuecomment) {
            $data['html'] = "";
            foreach ($issuecomment as $val) {
                $this->assign("vo", $val);
                $data['html'] .= $this->fetch("_issuecomment");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }

    /**
     * @param $id
     * @param $uid
     * 专辑点赞
     */
    public function support($id, $uid)
    {
        //$id微博的ID
        //$uid用户的ID
        if (!is_login()) {
            $this->error('亲，只有登录才能操作哦');
        }
        $row = $id;
        $message_uid = $uid;
        $support['appname'] = 'Issue';
        $support['table'] = 'issue_content';
        $support['row'] = $row;
        $support['uid'] = is_login();

        if (D('Support')->where($support)->count()) {
            $return['status'] = '0';
            $return['info'] = '亲，你已经点过赞了！';
        } else {
            $support['create_time'] = time();
            if (D('Support')->where($support)->add($support)) {

                $this->_clearCache($support);

                $user = query_user(array('username', 'uid'));
                D('Common/Message')->sendMessage($message_uid, $user['username'] . '给您点了个赞。', $title = $user['username'] . '赞了您。', 'Issue/issue/weiboDetail',array('id' => $id), is_login(), 1);
                $return['status'] = '1';
            } else {
                $return['status'] = ' 0';
                $return['info'] = '操作失败！';
            }

        }
        $this->ajaxReturn($return);
    }

    private function _clearCache($support)
    {
        unset($support['uid']);
        unset($support['create_time']);
        $cache_key = "support_count_" . implode('_', $support);
        S($cache_key, null);
    }

    /**
     * 增加专辑评论
     */
    public function doAddComment()
    {
        if (!is_login()) {
            $this->error('请您先进行登录', U('Mob/index/index'), 1);
        }

        $aContent = I('post.content', '', 'op_t');              //获取评论内容
        $aIssueId = I('post.issueId', 0, 'intval');             //获取当前专辑ID
        $aUid = I('post.uid', 0, 'intval');


        if (empty($aContent)) {
            $this->error('评论内容不能为空');
        }
        $uid = is_login();
        $result = D('LocalComment')->addIssueComment($uid, $aIssueId, $aContent);

        $title =get_nickname(is_login()). '评论了您';
        D('Common/Message')->sendMessage($aUid,$title, "评论内容：$aContent",  'Issue/Index/IssueContentDetail',array('id' => $aIssueId), is_login(), 0);
        action_log('add_issue_comment', 'local_comment', $result, $uid);

        $map['id'] = array('eq', $result);
        $issuecomment = D('Local_comment')->where(array('status' => 1, $map))->order('create_time desc')->select();

        foreach ($issuecomment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id']);
            $v['content'] = parse_weibo_mobile_content($v['content']);
        }
        if ($issuecomment) {
            $data['html'] = "";
            foreach ($issuecomment as $val) {
                $this->assign("vo", $val);
                $data['html'] .= $this->fetch("_issuecomment");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }

    /**
     * 删除评论
     */
    public function delIssueComment()
    {
        $comment_id = I('post.commentId', 0, 'intval');              //接收评论ID
        $issueId = I('post.issueId', 0, 'intval');                   //接收专辑ID


        $issue_uid = D('IssueContent')->where(array('status' => 1, 'id' => $issueId))->find();//根据微博ID查找专辑发送人的UID
        $comment_uid = D('LocalComment')->where(array('status' => 1, 'id' => $comment_id))->find();//根据评论ID查找评论发送人的UID
        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }


        if (is_administrator(get_uid()) || $issue_uid['uid'] == get_uid() || $comment_uid['uid'] == get_uid()) {                                     //如果是管理员，则可以删除评论
            $result = D('LocalComment')->deleteComment($comment_id);
        }
        if ($result) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['info'] = '删除失败';
        }
        $this->ajaxReturn($return);
    }

    /**
     * @param $id
     * @param $user
     * 专辑评论模态弹窗内的内容
     */
    public function atComment($id, $user, $uid)
    {
        //$id是发帖人的微博IDa
        //$uid是发帖人的ID
        $map['id'] = array('eq', $id);
        $issue = D('IssueContent')->where(array('status' => 1, $map))->select();
        $issue[0]['user_uid'] = $uid;
        // dump($issue);exit;


        foreach ($issue as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            $v['at_user_id'] = $user;

        }

        $this->assign('issue', $issue[0]);
        $this->display(T('Application://Mob@Issue/atcomment'));

    }

    /**
     * 专辑分类内容显示
     */
    public function issueSection()
    {

        $map['status']=1;
	    $map['pid']=0;
		
		//取出 可预订活动
			$group['group']=98;
	 		$menu=M('menu_config')->where($group)->find();
			
		$map['id']=array('neq',$menu['url']);
	    $issue_top = D('Issue')->where($map)->select();        //查找顶级分类pid=0的	
		
        //  dump($issue_top);exit;
        foreach ($issue_top as &$v) {
            $v['lever_two'] = D('Issue')->where(array('status' => 1, 'pid' => $v['id']))->select();        //查找二级分类pid=$issue_top的id
            $v['count'] = count($v['lever_two']);                //二级分类数量
            foreach ($v['lever_two'] as &$k) {
                $k['count_content'] = D('IssueContent')->where(array('status' => 1, 'issue_id' => $k['id']))->count();
				
            }
        }

        // dump($issue_top);exit;
        $this->assign("issue_top", $issue_top);         //顶级分类
        $this->display();
    }
	
	/**
     * 课程分类内容显示
	 * hong
     */
    public function lessonsection()
    {    //取出 可预订活动
		$group['group']=98;
	 	$menu=M('menu_config')->where($group)->find();	 

		//$lesson = D('Issue')->where(array('status' => 1, 'title' => '活动课程'))->find(); 
        $lesson = D('Issue')->where(array('status' => 1, 'title' => $menu['title']))->find(); 
		$issue_top = D('Issue')->where(array('status' => 1, 'pid' => $lesson['id']))->select();        //查找顶级分类pid=0的

        //  dump($issue_top);exit;
        foreach ($issue_top as &$v) {
            $v['lever_two'] = D('Issue')->where(array('status' => 1, 'pid' => $v['id']))->select();        //查找二级分类pid=$issue_top的id
            $v['count'] = count($v['lever_two']);                //二级分类数量
            foreach ($v['lever_two'] as &$k) {
                $k['count_content'] = D('IssueContent')->where(array('status' => 1, 'issue_id' => $k['id']))->count();
            }
        }

        // dump($issue_top);exit;
        $this->assign("issue_top", $issue_top);         //顶级分类
        $this->display('issuesection');
    }

    /**
     * 专辑二级标题点击进入查看的内容
     */
    public function issueLeverDetail($id)
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $totalCount = D('IssueContent')->where(array('status' => 1))->count();
        $issue = D('IssueContent')->where(array('status' => 1, 'issue_id' => $id))->page($aPage, $aCount)->select();        //查找顶级分类pid=0的
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }
        foreach ($issue as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id'], 200, 150);
        }
        $title = D('Issue')->where(array('status' => 1, 'id' => $id))->find();        //查找顶级分类pid=0的

        $this->assign("issue", $issue);
        $this->assign("title", $title);
        $this->assign("pid", $pid);
        $this->display(T('Application://Mob@Issue/index'));
    }

    /**
     * 专辑二级标题点击进入查看的内容
     * 查看更多
     */
    public function addMoreIssueClass()
    {
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $aId = I('post.id', '', 'op_t');
        $issue = D('IssueContent')->where(array('status' => 1, 'issue_id' => $aId))->page($aPage, $aCount)->select();        //查找顶级分类pid=0的

        foreach ($issue as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover_id'], 200, 150);
        }
        if ($issue) {
            $data['html'] = "";
            foreach ($issue as $val) {
                $this->assign("vo", $val);
                $data['html'] .= $this->fetch("_issuelist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }

        $this->ajaxReturn($data);

    }

    /**
     * 编辑专辑时里面选项的内容 ---修改为 报名活动
     */
    public function addIssue($id = 0)
    {

        $tree = D('Issue')->getTree();

        $issue = D('IssueContent')->where(array('status' => 1, 'id' => $id))->find();

        $issue['cover_url'] = getThumbImageById($issue['cover_id'], 72, 72);
		// hong$
		$user_id=get_uid();
	//	$user=query_user(array('nickname', 'avatar32', 'uid','avatar33'),$user_id);
	
	    $User = new MobApi();
        $user[1] = $User->expandinfo_select($user_id,1);
		$user[4] = $User->expandinfo_select($user_id,4);
	   // $user[1] = $User->expandinfo_select($user_id,1);
	
        if ($id != 0) {
		  
			$treeid=D('Issue')->field('pid')->where(array('id' => $issue['issue_id']))->find();
			  $issue['issue_top'] = $treeid['pid'];
            $issue['is_edit'] = 1;
            $this->_top_menu_list =array(
                'left'=>array(
                    array('type'=>'back','need_confirm'=>1,'confirm_info'=>'确定要返回？','a_class'=>'','span_class'=>''),
                ),
            );
            // dump($this->_top_menu_list);exit;
            $this->assign('top_menu_list', $this->_top_menu_list);
            $this->setTopTitle('报名活动课程');
			
	
        } else {
            $issue['is_edit'] = 0;
            $this->_top_menu_list =array(
                'left'=>array(
                    array('type'=>'back','need_confirm'=>1,'confirm_info'=>'确定要返回？','a_class'=>'','span_class'=>''),
                ),
            );
            // dump($this->_top_menu_list);exit;
            $this->assign('top_menu_list', $this->_top_menu_list);
            $this->setTopTitle('报名活动课程');
        }
     //     dump($tree);//exit;
      //   dump($issue);//exit;
        $this->assign('issue', $issue);
		$kecheng=$tree[1]['_'];
        $this->assign('tree', $tree[3]['_']);
		$this->assign('info', $user);
		$this->display('order');
	//	dump($tree);
    }

	
	//获取下拉选项的项目
		public function selecttitle($pid)
    {  //->limit(999)
        $issues = D('Issue_content')->where(array('issue_id' => $pid))->find();
		//dump($issue_top);
		if ($issues){
		$issues['status']=1;
		 }else{
		 $issues['status']=0;
		 }
	    exit(json_encode($issues));
    }
	
	
	
    public function selectDropdown($pid)
    {
        $issues = D('Issue')->where(array('pid' => $pid, 'status' => 1))->limit(999)->select();
		//dump($issue_top);
        exit(json_encode($issues));
    }
	
	public function doSendorder()
    {
	  
        $data['uid'] = $auid = get_uid();   
	  
       	$data['orderid'] = date("YmdHis").$auid; 
        $data['IssueId'] = $IssueId = I('post.issueId', 0, 'op_t');    //课程id
		//获取日期时间
		$date = I('post.url', '', 'op_t');
		$date=M('issue_content')->where(array('id'=>$IssueId))->find();
		//dump(M()->getlastsql());
	    $data['lesson_date'] = $date['issue_time'];
		
		$data['title'] = $aTitle = I('post.title', '', 'op_t');        //课程内容	  		
        $data['name'] =$acus=I('post.名称', 0, 'op_t');    
		$data['idcard'] =I('post.身份证号码', 0, 'op_t');    
		$data['tel'] =I('post.联系电话', 0, 'op_t');    
		$data['addr'] =I('post.家庭住址', 0, 'op_t'); 
		$data['wx_id'] =I('post.微信账号', 0, 'op_t'); 
        $data['payment'] = $apayment = I('post.payment', '', 'op_t');
		$data['takebus'] = $atakebus = I('post.takebus', '', 'op_t');
		$data['buscnt'] = $buscnt = I('post.buscnt', '', 'op_t');
		$data['price'] =$price= I('post.price', '', 'op_t');

		
		$data['count'] = I('post.count', '', 'op_t');
		$acount =$data['count']*1;
        $data['c_name']=I('post.c_name', '', 'op_t');
	//	$data['cid_no']=I('post.cid_no', '', 'op_t');
		$data['c_sex']=I('post.c_sex', '', 'op_t');
		$data['c_age']=I('post.c_age', '', 'op_t');
		$data['c_comments']=I('post.c_comments', '', 'op_t');
		 //优惠
		$mobapi = new MobApi();
        $disc = $mobapi->discount($auid,$acount);
		
        $data['fee']=($acount-$disc)*$price;
		$data['busfee']=$buscnt*50;
		
		$data['discount']=$disc*1;
		$data['disc']=$mobapi->discount($auid,$acount);
		unset($mobapi);
	

        if (trim($acus) == '') {
            $this->error('请输入姓名。');
        }
       /* if (trim(op_h($aUrl)) == '') {
            $this->error('请输入时间。');
        }*/
	  
	   $return['status'] = 1;
	   $return['info']=$data;
	   $this->ajaxReturn($return);
/*	
		for($i=0;$i<$acount;$i++){
	
        if (trim($data['c_name'][$i]) == '') {
            $this->error('请输入儿童姓名');
        }
      	if (trim($data['c_age'][$i]) == '') {
            $this->error('请输入儿童年龄');
        }
		
		}
	   // $rs = D('IssueContent')->add($content);
       if ($rs) {
             $this->success('编辑成功。', U('issueContentDetail', array('id' => $content['id'])));
            } else {
              $this->success('编辑失败。', '');
        }
*/
	  
	}
	
	
	
	
	
		public function confirmorder()
    {
	  
        $data['uid'] = $auid = get_uid();   
	  
       	$data['orderid'] = I('post.orderid', 0, 'op_t'); 
        $data['IssueId'] = $IssueId = I('post.issueId', 0, 'op_t');    //课程id
	    		//获取日期时间
		$date = I('post.url', '', 'op_t');
		$date=M('issue_content')->where(array('id'=>$IssueId))->find();
	    $data['lesson_date'] = $date['issue_time'];
		
		$data['title'] = $aTitle = I('post.title', '', 'op_t');        //课程id
  		$data['price'] =$price= I('post.price', '', 'op_t');   //获取单价
		$data['c_comments'] = I('post.c_comments', '', 'op_t');   
        $data['name'] =$acus=I('post.名称', 0, 'op_t');    
		$data['idcard'] =I('post.身份证号码', 0, 'op_t');
		$data['wx_id'] =I('post.微信账号', 0, 'op_t'); 
		$data['tel'] =I('post.联系电话', 0, 'op_t');    
		$data['addr'] =I('post.家庭住址', 0, 'op_t'); 
        $data['payment'] = $apayment = I('post.payment', '', 'op_t');
		$data['takebus'] = $atakebus = I('post.takebus', '', 'op_t');
		$data['buscnt'] = $buscnt = I('post.buscnt', '', 'op_t');
		$data['count'] = $acount = I('post.count', '', 'op_t');
   
   		 //优惠信息 
		$mobapi = new MobApi();
        $disc = $mobapi->discount($auid,$acount);
		$result = $mobapi->update_vip($auid,$disc);
		
        $data['fee']=($acount-$disc)*$price;
		$data['busfee']=$buscnt*50;
		$data['discount']=$disc*$price;
		$data['create_time']=date("Ymd H:i:s");
		if( $data['fee'] == 0){
		$data['flag']=1; // VIP 判定
		}
    	for($i=0;$i<$acount;$i++){
		
	    $data['c_name_'.$i]=$_POST['c_name'][$i];
		$data['c_cardno_'.$i]=$_POST['cid_no'][$i];
		$data['c_sex_'.$i]=$_POST['c_sex'][$i];
		$data['c_age_'.$i]=$_POST['c_age'][$i];
		$data['c_comments']=$data['c_comments'].$_POST['c_comments'][$i];
		}
//       dump($data);
	   	
	   $rs=D('user_member')->add($data);
	   
	   $rs=M('user_member')->where(array('orderid'=>$data['orderid']))->count();
	 
	 
	  // dump($rs."----".$result);
	   $return['status'] = ($rs*1-1)+$result*1;
	   $return['info']= '更新成功！'. M()->getLastSql();
	 //  dump($return);
	   $this->ajaxReturn($return);


	  
	}
	
	
    public function doSendIssue()
    {
        $data['cover_id'] = $aCoverId = I('post.one_attach_id', 0, 'op_t');
        $data['title'] = $aTitle = I('post.title', '', 'op_t');
        $data['id'] = $aId = I('post.id', 0, 'op_t');
        $data['IssueId'] = $IssueId = I('post.issueId', 0, 'op_t');                        //专辑的ID
        $data['issue_id'] = $aIssueId = I('post.issue_id', 0, 'op_t');      //专辑分类ID
        $data['url'] = $aUrl = I('post.url', '', 'op_t');
        $data['content'] = $aContent = I('post.content', '', 'op_t');


        $issue_id = intval($aIssueId);

        if (!$aCoverId) {
            $this->error('请上传封面。');
        }
       /* if (trim(op_t($aTitle)) == '') {
            $this->error('请输入标题。');
        }
		*/
        if (trim(op_h($aContent)) == '') {
            $this->error('请输入内容。');
        }
        if ($issue_id == 0) {
            $this->error('请选择分类。');
        }
        if (trim(op_h($aUrl)) == '') {
            $this->error('请输入网址。');
        }

        $content = D('Issue/IssueContent')->create($data);
        $content['content'] = op_h($content['content']);
        $content['title'] = op_t($content['title']);
        $content['url'] = op_t($content['url']); //新增链接框
        $content['issue_id'] = $issue_id;

        if ($IssueId) {
            $content_temp = D('IssueContent')->find($IssueId);
            if (!check_auth('editIssueContent')) { //不是管理员则进行检测
                if ($content_temp['uid'] != is_login()) {
                    $this->error('不可操作他人的内容。');
                }
            }
            $content['uid'] = $content_temp['uid']; //权限矫正，防止被改为管理员
            $content['id'] = $IssueId;

            $rs = D('IssueContent')->save($content);
            //   dump(D('IssueContent')->getLastSql());exit;


            if ($rs) {
                $this->success('编辑成功。', U('issueContentDetail', array('id' => $content['id'])));
            } else {
                $this->success('编辑失败。', '');
            }
        } else {
            if (modC('NEED_VERIFY', 0) && !is_administrator()) //需要审核且不是管理员
            {
                $content['status'] = 0;
                $tip = '但需管理员审核通过后才会显示在列表中，请耐心等待。';
                $user = query_user(array('nickname', 'uid'), is_login());
                $admin_uids = explode(',', C('USER_ADMINISTRATOR'));
                foreach ($admin_uids as $admin_uid) {
                    D('Common/Message')->sendMessage($admin_uid, $title = '专辑投稿提醒',"{$user['nickname']}向专辑投了一份稿件，请到后台审核。",  'Admin/Issue/verify',array('id' => $IssueId), is_login(), 2);
                }
            }
            // dump($content);exit;
            $rs = D('IssueContent')->add($content);
            if ($rs) {
                $return['status'] = 1;
            } else {
                $return['status'] = 0;
                $return['info'] = '发布失败';
            }
        }
        $this->ajaxReturn($return);

    }
	
	///table 
	    public function table()
    {
        $map = $this->setMap();
        $map['status'] = 1;
		$map['flag'] =1;
        $uid = get_uid();
	
        //dump($peoples);
        if (empty($peoples)) {
       //     $peoples = M('user_member')->where($map)->field('uid', 'lesson_date', 'Issue_id','title')->order('lesson_date desc')->findPage(20,false,array(),4);
			$peoples = D('Issue_content')->where($map)->page($aPage, $aCount)->order('create_time desc')->findPage(20,false,array(),4);
				
		   
				
				foreach ($peoples['data'] as &$v) {
					$v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
					$v['cover_url'] = getThumbImageById($v['cover_id'], 200, 150);
					$v['count']=D('user_member')->where(array('IssueId'=>$v['id']))->sum('count')*1;
					$status=D('user_member')->where(array('IssueId'=>$v['id'],'uid'=>$uid,'flag'=>array('lt',2)))->count();
					
					if($status>0){
					$v['status']=D('user_member')->where(array('IssueId'=>$v['id'],'uid'=>$uid,'flag'=>array('lt',2)))->sum('flag')*1;
					}else{
					$v['status']='-1';
					$v['url']='/mob/issue/issuedetail/id/'.$v['id'];
					}
			
					
					
					
				}
					
			
			
           }
		//dump($peoples);
        $this->setMobTitle('个人预订');
        $this->assign('page_data',$peoples);
        $this->display();
    }
	
	
	//分类展示
	
   public function subtable($type=0)
    {
        $map = $this->setMap();
        $map['status'] = 1;
		$map['flag'] =1;
        $uid = get_uid();
	
        //dump($peoples);
        if (empty($peoples)) {
       //     $peoples = M('user_member')->where($map)->field('uid', 'lesson_date', 'Issue_id','title')->order('lesson_date desc')->findPage(20,false,array(),4);
			$peoples = D('Issue_content')->where($map)->page($aPage, $aCount)->order('create_time desc')->findPage(20,false,array(),4);
				
		   
				
				foreach ($peoples['data'] as &$v) {
					$v['user'] = query_user(array('nickname', 'avatar32', 'uid'), $v['uid']);
					$v['cover_url'] = getThumbImageById($v['cover_id'], 200, 150);
					$v['count']=D('user_member')->where(array('IssueId'=>$v['id']))->sum('count')*1;
					$status=D('user_member')->where(array('IssueId'=>$v['id'],'uid'=>$uid,'flag'=>array('lt',2)))->count();
					
					if($status>0){
					$v['status']=D('user_member')->where(array('IssueId'=>$v['id'],'uid'=>$uid,'flag'=>array('lt',2)))->sum('flag')*1;
					}else{
					$v['status']='-1';
					$v['url']='/mob/issue/issuedetail/id/'.$v['id'];
					}
			
					
					
					
				}
					
			
			
           }
		//dump($peoples);
        $this->setMobTitle('个人预订');
        $this->assign('page_data',$peoples);
        $this->display('table');
    }
	
	

    private function setMap()
    {
        $map = array();
        $nickname = I('keywords', '', 'op_t');
        if ($nickname != '') {
            !isset($_GET['keywords']) && $_GET['keywords'] = $_POST['keywords'];
            $map['title'] = array('like', '%' . $nickname . '%');
            $this->assign('nickname', $nickname);
        }
        return $map;
    }

}