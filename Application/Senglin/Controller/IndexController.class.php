<?php


namespace Senglin\Controller;

use  Admin\Controller\AdminController;
use Senglin\Builder\SenglinAdminListBuilder;

use Common\Model\MemberModel;
use User\Api\UserApi;
use Think\Controller;

//use Admin\Common\function;

class IndexController extends AdminController
{
    /**
     * 业务逻辑都放在 WeiboApi 中
     * @var
     */
	
    public function _initialize()
    {
        
		
        $sub_menu =
            array(
                'left' =>
                    array(
                        array('tab' => 'home', 'title' => '首页', 'href' => U('Issue/index/index')),
                    ),
            );
        if (check_auth('addIssueContent')) {
            $sub_menu['right'] = array(
                array('tab' => 'post', 'title' => '发布', 'href' => '#frm-post-popup','a_class'=>'open-popup-link')
            );
        }
        
        $this->assign('sub_menu', $sub_menu);

    }

    public function index($page = 1, $r = 20)
    {
       
	    $map['status'] = array('egt', 0);
		
        if (is_numeric($nickname)) {
            $map['uid|nickname'] = array(intval($nickname), array('like', '%' . $nickname . '%'), '_multi' => true);
        } else {
            $map['nickname'] = array('like', '%' . (string)$nickname . '%');
        }
        $list = M('Member')->where($map)->order('last_login_time desc')->page($page, $r)->select();
        $totalCount = M('Member')->where($map)->count();
        int_to_string($list);
		
        //扩展信息查询
        $map_profile['status'] = 1;
        $field_group = D('field_group')->where($map_profile)->select();
        $field_group_ids = array_column($field_group, 'id');
        $map_profile['profile_group_id'] = array('in', $field_group_ids);
        $fields_list = D('field_setting')->where($map_profile)->getField('id,field_name,form_type');
        $fields_list = array_combine(array_column($fields_list, 'field_name'), $fields_list);
        $fields_list = array_slice($fields_list, 0, 9);//取出前8条，用户扩展资料默认显示8条
        foreach ($list as &$tkl) {
            $tkl['id'] = $tkl['uid'];
            $map_field['uid'] = $tkl['uid'];
            foreach ($fields_list as $key => $val) {
                $map_field['field_id'] = $val['id'];
                $field_data = D('field')->where($map_field)->getField('field_data');
                if ($field_data == null || $field_data == '') {
                    $tkl[$key] = '';
                } else {
                    $tkl[$key] = $field_data;
                }
            }
        }

		
		 
         $builder = new SenglinAdminListBuilder();
        $builder->title("用户扩展资料列表");
        $builder->meta_title = '用户扩展资料列表';
        $builder->setSearchPostUrl(U('Senglin/index/index'))->search('搜索', 'nickname', 'text', '请输入用户昵称或者ID');
        $builder->keyId()->keyLink('nickname', "昵称", 'Senglin/index?uid=###');
        foreach ($fields_list as $vt) {
            $builder->keyText($vt['field_name'], $vt['field_name']);
        }
		
        $builder->data($list);
        $builder->pagination($totalCount, $r);
        $builder->display();
 
      
    }

   

    public function issueContentDetail($id = 0)
    {


        $issue_content = D('IssueContent')->find($id);
        if (!$issue_content) {
            $this->error('404 not found');
        }
        D('IssueContent')->where(array('id' => $id))->setInc('view_count');
        $issue = D('Issue')->find($issue_content['issue_id']);

        $this->assign('top_issue', $issue['pid'] == 0 ? $issue['id'] : $issue['pid']);
        $this->assign('issue_id', $issue['id']);
        $issue_content['user'] = query_user(array('id', 'nickname', 'space_url', 'space_link', 'avatar64', 'rank_html', 'signature'), $issue_content['uid']);
        $this->assign('content', $issue_content);
        $this->setTitle('{$content.title|op_t}' . '——专辑');
        $this->setKeywords($issue_content['title']);
        $this->display();
    }

    public function selectDropdown($pid)
    {
        $issues = D('Issue')->where(array('pid' => $pid, 'status' => 1))->limit(999)->select();
        exit(json_encode($issues));


    }

    public function edit($id)
    {
        if (!check_auth('addIssueContent') && !check_auth('editIssueContent')) {
            $this->error('抱歉，您不具备投稿权限。');
        }
        $issue_content = D('IssueContent')->find($id);
        if (!$issue_content) {
            $this->error('404 not found');
        }
        if (!check_auth('editIssueContent')) { //不是管理员则进行检测
            if ($issue_content['uid'] != is_login()) {
                $this->error('404 not found');
            }
        }

        $issue = D('Issue')->find($issue_content['issue_id']);
		
		$select0 = M('Issue')->where(array('level'=>0))->select();
		$select1 = M('Issue')->where(array('level'=>1))->select();
		$select2 = M('Issue')->where(array('level'=>2))->select();
		//$select = M('Issue')->select();
		 
		//hong level logic
		if($issue['level'] == 2){
		$tmp=D('Issue')->field('pid')->find($issue['pid']);
		$this->assign('issue_top', $tmp['pid']);
		$this->assign('issue_second',$issue['pid']); 
		$this->assign('issue_last', $issue['id']);
		 unset($tmp);
		// dump($issue);
		// dump($issue['id']);
		 
		}else{
		$this->assign('issue_last', 999);
		$this->assign('issue_second', $issue['level'] == 1 ? $issue['id'] : 999);
		$this->assign('issue_top', $issue['pid'] == 0 ? $issue['id'] : $issue['pid']);
		}
		
		//
		$this->assign('select0', $select0);
		  $this->assign('select1', $select1);
        $this->assign('tree', $select2);
        $this->assign('issue_id', $issue['id']);
        $issue_content['user'] = query_user(array('id', 'nickname', 'space_url', 'space_link', 'avatar64', 'rank_html', 'signature'), $issue_content['uid']);
        $this->assign('content', $issue_content);
		
		
        $this->display();
    }
}