<?php


namespace Admin\Controller;

use  Admin\Controller\AdminController;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;

use Common\Model\MemberModel;
use User\Api\UserApi;
use Think\Controller;
use Mob\Api\MobApi;
//use Admin\Common\function;

class SenglinController extends AdminController
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
		$name=$_GET['name'];
    //       var_dump($_POST);    
         if (is_numeric($name)) {
		   if(strlen($name) >=8){
		      $map['tel'] = array('like', '%' . $name . '%');
        } else {
            $map['name'] = array('like', '%' . (string)$name . '%');
        }
	   }
	
		$map['flag'] = array('egt', 0);
		$list = M('user_member')->where($map)->order('create_time desc')->page($page, $r)->select();
		///数据处理
		foreach($list as $key=>$value){
		 $list[$key]['childinfo']='';
		 $list[$key]['sum']=$list[$key]['fee']*1+$list[$key]['discount']*1+$list[$key]['busfee']*1;
		 $list[$key]['needpay']=$list[$key]['fee']*1+$list[$key]['busfee']*1;
		 $list[$key]['feedesc']='[课程费用 - VIP优惠('.$list[$key]['discount'].')='.$list[$key]['fee'].'元]+乘车费用:'.$list[$key]['busfee'];
		 
		 for($i=0;$i<$value['count'];$i++){
		 
		 $list[$key]['childinfo']= $list[$key]['childinfo'].'['.$i.']: 姓名:'.$value['c_name_'.$i].','.$value['c_sex_'.$i].',年龄 :'.$value['c_age_'.$i].'<br/>';
		
		 }	
	
		}
	
	
	
        $totalCount = M('user_member')->where($map)->count();
	        int_to_string($list); 
		// var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("用户报名资料列表");
        $builder->meta_title = '用户报名资料列表';
		$builder->buttonSetStatus(U('setpay'), 0, '未付费用', array('target-form'=>'ids'));
		$builder->buttonSetStatus(U('setpay'), 1, '收到费用', array('target-form'=>'ids'))->buttonSetStatus(U('setpay'), 2, '签到成功', array('target-form'=>'ids'));
		//$builder->buttonModalPopup(U('loadchild'), 0, '孩子信息',array('target-form'=>'ids'));
        $builder->setSearchPostUrl(U('Admin/Senglin/index'))->search('搜索', 'name', 'text', '请输入姓名或者电话号码');
		$builder->keyText('orderid', "流水号");
		$builder->keyText('title', '会议')->keyText("lesson_date", "时间")->keyText('name', '姓名')->keyText('tel', '电话号码')->keyText('wx_id', '微信');
		//$builder->keyText('payment', '支付方式')->keyText('sum', '总金额(元)')->keyText('needpay', '应支付');
		$builder->keyText('count', '人数')->keyHtml('c_comments', '备注')->keyHtml('addr', '地址信息');
		$builder->orderStatus('flag', "状态");

	
        $builder->data($list);
	
        $builder->pagination($totalCount, $r);
	
        $builder->display('senglin');
      
    }
	
	
	
	

    public function manage($page = 1, $r = 20)
    {
		$name=$_GET['name'];
    //       var_dump($_POST);    
         if (is_numeric($name)) {
		   if(strlen($name) >=18){
		     $map['idcard'] = array('like', '%' . $name . '%');
		   }else{
             $map['tel'] = array('like', '%' . $name . '%');}
			
        } else {
            $map['nickname'] = array('like', '%' . (string)$name . '%');
        }
	   
		$list = M('vipcard')->join('JOIN ocenter_member ON ocenter_vipcard.uid = ocenter_member.uid' )->field('ocenter_vipcard.Id,ocenter_vipcard.uid,ocenter_vipcard.count,ocenter_vipcard.title,ocenter_vipcard.status as vip,ocenter_member.nickname')->where($map)->order('Id desc')->page($page, $r)->select();
		
	
	       $totalCount = M('vipcard')->where($map)->count();
	 //   
        int_to_string($list); 
		// var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("VIP用户列表");
        $builder->meta_title = 'VIP用户资料列表';
		$builder->buttonSetStatus(U('vipset'), 1, '激活', array('target-form'=>'ids'))->buttonSetStatus(U('vipset'), 0, '失效', array('target-form'=>'ids'));
		//$builder->buttonModalPopup(U('loadchild'), 0, '孩子信息',array('target-form'=>'ids'));
        $builder->setSearchPostUrl(U('Admin/Senglin/manage'))->search('搜索', 'name', 'text', '请输入会员账号名');
		$builder->keyText('uid', "用户ID");
		$builder->keyText('nickname', '昵称')->keyText("title", "卡类型")->keyText('count', '剩余次数');
		$builder->VipStatus('vip', "状态");
				/*
        $builder->keyId()->keyLink('name', "昵称", 'Senglin/index?uid=###');
  }**********************************/
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('vip');      
    }
	
	
	
	public function mainpage($page = 1, $r = 20)
    {
		$name=$_GET['name'];
    //       var_dump($_POST);    
         if (is_numeric($name)) {
		   if(strlen($name) >=6){
		     $map['desc'] = array('like', '%' . $name . '%');
		   } else {
            $map['nickname'] = array('like', '%' . (string)$name . '%');
        }
	   }
	    $list=M('main_page')->select();
		//$span=M('main_page')->where(array('type' => 'span', 'flag' => '0'))->select();
		//$list = M('vipcard_order')->join('JOIN ocenter_member ON ocenter_vipcard_order.uid = ocenter_member.uid' )->field('ocenter_vipcard_order.orderid,ocenter_vipcard_order.payment,ocenter_vipcard_order.vipfee,ocenter_vipcard_order.uid,ocenter_vipcard_order.vipcnt,ocenter_vipcard_order.title,ocenter_vipcard_order.status as vip,ocenter_member.nickname')->where($map)->order('Id desc')->page($page, $r)->select();
		
			foreach($list as $key=>$value){
			$list[$key]['id']=$list[$key]['Id'];
			
			}
	    $totalCount = M('main_page')->where($map)->count();
	 //   
        int_to_string($list); 
		//dump($list);
	//	 var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("主页配置列表");
        $builder->meta_title = '元素资料列表';
		$builder->buttonSetStatus(U('setvipcard'), 1, '启动', array('target-form'=>'ids'))->buttonSetStatus(U('setvipcard'), -1, '失效', array('target-form'=>'ids'));;
        $builder->setSearchPostUrl(U('Admin/Senglin/mainpage'))->search('搜索', 'name', 'text', '请输入会员账号名或者流水ID');
		$builder->keyText('Id', "ID")->keyText('desc', "名称")->keyText('type', "类型");
		$builder->keyText('url', '跳转地址')->keyHtml("source", "元素内容");
		$builder->keyStatus('flag', "状态");
		 $builder->keyDoAction('Senglin/edititem?id=###', '编辑');
				/***********************************/
     //   $builder->keyId()->keyLink('Id', "link", 'Senglin/index?uid=###');
  
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('senglin');      
    }

	
	
	
	
		
	public function questconfig($page = 1, $r = 20)
    {
		$name=$_GET['name'];
		   if(strlen($name) >=6){
		     $map['desc'] = array('like', '%' . $name . '%');
		   } else {
            $map['name'] = array('like', '%' . (string)$name . '%');
        }
	
	    $list=M('question')->select();
		//$span=M('main_page')->where(array('type' => 'span', 'flag' => '0'))->select();
		foreach($list as $key=>$value){
			$list[$key]['id']=$list[$key]['Id'];
			
			}
	    $totalCount = M('question')->where($map)->count();
	 //   
        int_to_string($list); 
		//dump($list);
	//	 var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("每日问题配置列表");
        $builder->meta_title = '每日问题配置列表';  //setquest
		$builder->buttonSetStatus(U('setquest'), 1, '启动', array('target-form'=>'ids'))->buttonSetStatus(U('setquest'), -1, '失效', array('target-form'=>'ids'));;
        $builder->setSearchPostUrl(U('Admin/Senglin/questconfig'))->search('搜索', 'name', 'text', '请输入题目名字或者描述备注');
		$builder->keyText('Id', "ID")->keyText('name', "题目")->keyText('qtype', "类型");
		$builder->keyHtml('question_txt', '内容')->keyHtml("answer_txt", "答案");
		$builder->keyStatus('flag', "状态");
		 $builder->keyDoAction('Senglin/editquestion?id=###', '编辑');
				/***********************************/
     //   $builder->keyId()->keyLink('Id', "link", 'Senglin/index?uid=###');
  
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('senglin');      
    }
	
	
		public function menuicon($page = 1, $r = 20)
    {

       $map['group']=99;
	   $list=M('menu_config')->where($map)->select();
		foreach($list as $key=>$value){
			$list[$key]['id']=$list[$key]['Id'];
			
			}
	    $totalCount = M('main_page')->where($map)->count();
	 //   
        int_to_string($list); 
		//dump($list);
	//	 var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("移动菜单配置");
        $builder->meta_title = '移动菜单配置';
		//$builder->buttonSetStatus(U('setvipcard'), 0, '显示', array('target-form'=>'ids'))->buttonSetStatus(U('setvipcard'), -1, '失效', array('target-form'=>'ids'));;
       // $builder->setSearchPostUrl(U('Admin/Senglin/menuicon'))->search('搜索', 'name', 'text', '请输入会员账号名或者流水ID');
		$builder->keyText('Id', "ID")->keyText('title', "名称")->keyText('icon', "图标");
		$builder->keyText('sort', '排序');
		$builder->keyStatus('flag', "状态");
		 $builder->keyDoAction('Senglin/editmobmenu?id=###', '编辑');
		 $builder->keyDoAction('Senglin/submenuicon?id=###', '编辑子菜单'); 
				/***********************************/
     //   $builder->keyId()->keyLink('Id', "link", 'Senglin/index?uid=###');
  
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('senglin');      
    }
	
	
		public function submenuicon($page = 1, $r = 20)
    {

	     $map['group']=array('neq',99);
	//	 dump($_GET);
		 if(strlen($_GET['id']) >=1){
		   $subid['Id']=$_GET['id'];
		   $id=M('menu_config')->where($subid)->find();
	//	   dump($id);
		   $map['group']=$id['sort'];
		 }
	     $list=M('menu_config')->where($map)->order("`group` asc,sort asc")->select();
	//	 var_dump(M()->getlastsql());
		foreach($list as $key=>$value){
			$list[$key]['id']=$list[$key]['Id'];
			
			}
	    $totalCount = M('menu_config')->where($map)->count();
	 //   
        int_to_string($list); 
		//dump($list);
	//	 var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("移动子菜单配置");
        $builder->meta_title = '移动子菜单配置';
		$builder->buttonSetStatus(U('setvipcard'), 1, '启动', array('target-form'=>'ids'))->buttonSetStatus(U('setvipcard'), -1, '失效', array('target-form'=>'ids'));;
       // $builder->setSearchPostUrl(U('Admin/Senglin/menuicon'))->search('搜索', 'name', 'text', '请输入会员账号名或者流水ID');
		$builder->keyText('Id', "ID")->keyText('title', "名称")->keyText('icon', "图标");
		$builder->keyText('group', '菜单分组')->keyText('sort', '排序');
		$builder->keyStatus('flag', "状态");
		 $builder->keyDoAction('Senglin/editmobmenu?id=###', '编辑');
				/***********************************/
     //   $builder->keyId()->keyLink('Id', "link", 'Senglin/index?uid=###');
  
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('senglin');      
    }
	
	
	
	
	
	

	
		public function datelist($page = 1, $r = 20)
    {
		$name=$_GET['name'];
    //       var_dump($_POST);    
         if (is_numeric($name)) {
		   if(strlen($name) >=6){
		     $map['desc'] = array('like', '%' . $name . '%');
		   } else {
            $map['nickname'] = array('like', '%' . (string)$name . '%');
        }
	   }	   
	   
	   $list=M('Issue_content')->order('id desc')->page($page, $r)->select();
	   foreach($list as $key=>$value){
		
		$map['Issueid']=$list[$key]['id'];
	    $totalCount = M('dateorder')->where($map)->count();
		$list[$key]['count']=  $totalCount;	
			}
	    $totalCount = M('Issue_content')->where($map)->count();
	 //   
        int_to_string($list); 
	//dump($list);
	//	 var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("当前活动/课程/会议/任务列表");
        $builder->meta_title = '时间安排';
	//	$builder->buttonSetStatus(U('setvipcard'), 1, '收到费用', array('target-form'=>'ids'))->buttonSetStatus(U('setvipcard'), -1, '忽略', array('target-form'=>'ids'));;
        $builder->setSearchPostUrl(U('Admin/Senglin/datelist'))->search('搜索', 'name', 'text', '请输入活动名或者ID');
		$builder->keyText('id', "ID")->keyText('title', "名称")->keyText('title', "活动名")->keyText('issue_addr', "地点");
	//	$builder->keyText('owner', '相关人员/机构')->keyHtml("desc", "备注");
		$builder->keyText('count', "活动总次数")->keyStatus('flag', "状态");
		$builder->keyDoAction('Senglin/dateorder?id=###', '时间安排编辑');
				/***********************************/
     //   $builder->keyId()->keyLink('Id', "link", 'Senglin/index?uid=###');
  
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('senglin');      
    }
	

	
	
	//时间列表
	
		public function dateorder($page = 1, $r = 20)
    {
		  $name=$_GET['id'];
    //       var_dump($_POST);    
       if (is_numeric($name)) {
		   if(strlen($name) >=6){
		     $map['desc'] = array('like', '%' . $name . '%');
		   } else {
            $map['Issueid'] = array('eq',(string)$name);
        }
	   }
	   $map['Issueid'] =$_GET['id']; 
	    $list=M('dateorder')->join('JOIN ocenter_issue_content ON ocenter_dateorder.Issueid = ocenter_issue_content.id')->where($map)->order('lesson_date desc')->page($page, $r)->select();;
	//var_dump(M()->getlastsql());
	//dump($list);
		//$span=M('main_page')->where(array('type' => 'span', 'flag' => '0'))->select();
		//$list = M('vipcard_order')->join('JOIN ocenter_member ON ocenter_vipcard_order.uid = ocenter_member.uid' )->field('ocenter_vipcard_order.orderid,ocenter_vipcard_order.payment,ocenter_vipcard_order.vipfee,ocenter_vipcard_order.uid,ocenter_vipcard_order.vipcnt,ocenter_vipcard_order.title,ocenter_vipcard_order.status as vip,ocenter_member.nickname')->where($map)->order('Id desc')->page($page, $r)->select();
		foreach($list as $key=>$value){
			$list[$key]['id']=$list[$key]['Id'];
			
			}
			
	    $totalCount = M('dateorder')->where($map)->count();
	 //   
     
		
		int_to_string($list); 
		//dump($list);
	//	 var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("时间安排列表");
        $builder->meta_title = '资料数据列表';
		//$builder->buttonSetStatus(U('setvipcard'), 1, '收到费用', array('target-form'=>'ids'))->buttonSetStatus(U('setvipcard'), -1, '忽略', array('target-form'=>'ids'));;
       $builder->buttonNew(U('editdate', array('id' => '0')));       
	   $builder->setSearchPostUrl(U('Admin/Senglin/dateorder'))->search('搜索', 'name', 'text', '请输入活动名或者流水ID');
		$builder->keyText('Id', "ID")->keyText('Issueid', "活动id")->keyText('title', "活动名")->keyText('lesson_date', "时间");
		$builder->keyText('owner', '相关人员/机构')->keyHtml("desc", "备注");
	//	$builder->keyStatus('flag', "状态");
	    $builder->keyDoAction('Senglin/editdate?id=###', '编辑');
				/***********************************/
     //   $builder->keyId()->keyLink('Id', "link", 'Senglin/index?uid=###');
  
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('senglin');      
    }
	
	
	
	
	
	
	
	    public function vipcard($page = 1, $r = 20)
    {
		$name=$_GET['name'];
    //       var_dump($_POST);    
         if (is_numeric($name)) {
		   if(strlen($name) >=6){
		     $map['orderid'] = array('like', '%' . $name . '%');
		   } else {
            $map['nickname'] = array('like', '%' . (string)$name . '%');
        }
	   }
		$list = M('vipcard_order')->join('JOIN ocenter_member ON ocenter_vipcard_order.uid = ocenter_member.uid' )->field('ocenter_vipcard_order.orderid,ocenter_vipcard_order.payment,ocenter_vipcard_order.vipfee,ocenter_vipcard_order.uid,ocenter_vipcard_order.vipcnt,ocenter_vipcard_order.title,ocenter_vipcard_order.status as vip,ocenter_member.nickname')->where($map)->order('Id desc')->page($page, $r)->select();
		
	
	    $totalCount = M('vipcard_order')->where($map)->count();
	 //   
        int_to_string($list); 
		// var_dump($list);
        $builder = new AdminListBuilder();
        $builder->title("VIP卡冲值请求列表");
        $builder->meta_title = 'VIP用户资料列表';
		$builder->buttonSetStatus(U('setvipcard'), 1, '收到费用', array('target-form'=>'ids'))->buttonSetStatus(U('setvipcard'), -1, '忽略', array('target-form'=>'ids'));;
		//$builder->buttonModalPopup(U('loadchild'), 0, '孩子信息',array('target-form'=>'ids'));
        $builder->setSearchPostUrl(U('Admin/Senglin/vipcard'))->search('搜索', 'name', 'text', '请输入会员账号名或者流水ID');
		$builder->keyText('orderid', "流水ID")->keyText('uid', "用户ID");
		$builder->keyText('nickname', '昵称')->keyText("title", "卡类型")->keyText('vipcnt', '冲值次数');
		$builder->keyText('vipfee', '应收费用')->keyText("payment", "支付方式");
		$builder->VipStatus('vip', "购卡状态");
				/*
        $builder->keyId()->keyLink('name', "昵称", 'Senglin/index?uid=###');
  }**********************************/
		$builder->data($list);	
        $builder->pagination($totalCount, $r);	
        $builder->display('senglin');      
    }
	
	
	//hong 更新页面元素配置
	   public function edititem($id = 0)
    {
	
	       if (IS_POST) {
		   //submit logic
		//   dump($_POST);
		   $id=$_POST['Id'];
		    if ($_POST['etype'] == '') {
                $this->error('元素类型不能为空！');
            }
			if ($_POST['source'] == '') {
                $this->error('元素配置不能为空！');
            }
			
		      if ($id != '') {
                $res = D('main_page')->where('Id=' . $id)->save($_POST);
            } else {
                $res = D('main_page')->add($_POST);
            }

            $this->success($id == '' ? "添加元素成功" : "编辑元素成功", U('edititem', array('Id' => $id)));		   
		   
		   }else{
          $list = D('main_page')->where(array('Id' => $id))->find();
		
		 $type_default = array(
            'img' => '图片',
            'span' => '标签',
            'checkbox' => '多选框',
            'select' => '下拉选择框',
            'textarea' => '多行文本框'
        );
	  //	var_dump( $list);
		//$list = M('user_member')->where($map)->order('create_time desc')->select();
		$builder = new AdminConfigBuilder();
		$builder->title("主页元素列表");
	    $builder->meta_title = '主页元素配置';
		$builder->keyReadOnly('Id', 'ID')->keyText("desc", "名称")->keySelect("etype", "元素类型", '', $type_default)->keyText("url", "跳转地址")->keyText('source', '元素配置');
		$builder->keyText("onclick", "触发JS函数名");
		$builder->keyBool('flag', '是否启用');
		$builder->data($list);
	    $builder->buttonSubmit(U('edititem'), $id == 0 ? "添加" : "修改")->buttonBack();
		$builder->display();  	   
		   }
	}
	
// 问题编辑
	   public function editquestion($id = 0)
    {
	
	       if (IS_POST) {
		   //submit logic
		//   dump($_POST);
		   $id=$_POST['Id'];
		    if ($_POST['qtype'] == '') {
                $this->error('类型不能为空！');
            }
			if ($_POST['name'] == '') {
                $this->error('题目不能为空！');
            }
			if ($_POST['question_txt'] == '') {
                $this->error('内容不能为空！');
            }
			
		     if ($id != '') {
                $res = D('question')->where('Id=' . $id)->save($_POST);
            } else {
                $res = D('question')->add($_POST);
            }

            $this->success($id == '' ? "添加成功" : "编辑成功", U('editquestion', array('Id' => $id)));		   
		   
		   }else{
          $list = D('question')->where(array('Id' => $id))->find();
		
		 $type_default = array(
            'txt' => '问答',
            'select' => '选择',
            'check' => '选框',
            'multselect' => '多选择框'
        );
		
	    //var_dump( $list);
		//$list = M('user_member')->where($map)->order('create_time desc')->select();
		$builder = new AdminConfigBuilder();
		$builder->title("每日问题配置");
	    $builder->meta_title = '每日问题配置';
		$builder->keyReadOnly('Id', 'ID')->keyText("name", "题目")->keySelect("qtype", "类型", '', $type_default)->keyTextarea("question_txt", "题目内容")->keyText('question_sel', '题目选项');
		$builder->keyTextarea("answer_txt", "题目答案")->keyText('answer_sel','答案选项（类型为选择）');
		$builder->keyBool('flag', '是否启用');
		$builder->data($list);
	    $builder->buttonSubmit(U('editquestion'), $id == 0 ? "添加" : "修改")->buttonBack();
		$builder->display();  	   
		   }
	}
	
	

	
	
	   public function editmobmenu($id = 0)
    {
	
	       if (IS_POST) {
		   //submit logic
		//   dump($_POST);
		   $id=$_POST['Id'];
		    if ($_POST['title'] == '') {
                $this->error('菜单名字不能为空！');
            }
			if ($_POST['icon'] == '') {
                $this->error('图标不能为空！');
            }
			
		      if ($id != '') {
                $res = D('menu_config')->where('Id=' . $id)->save($_POST);
            } else {
                $res = D('menu_config')->add($_POST);
            }

            $this->success($id == '' ? "添加成功" : "编辑成功", U('editmobmenu', array('Id' => $id)));		   
		   
		   }else{
          $list = M('menu_config')->where(array('Id' => $id))->find();	//		dump($list);


		 $type_default = array(
    		'1' => '第1个主菜单',
            '2' => '第2个主菜单',
            '3' => '第3个主菜单'
        );
		//dump($type_default );
		
		$builder = new AdminConfigBuilder();
		$builder->title("主页元素列表");
	    $builder->meta_title = '主页元素配置';
		$builder->keyReadOnly('Id', 'ID')->keyText("title", "名称");
		if($list['group'] <> 99){
				
	      $menu = M('menu_config')->where(array('group' => 99))->select();	
			foreach($menu as $key=>$value){
			$tmp[$value['sort']]=$value['title'];
		}
			//	dump($menu);
	//		dump($tmp);
			 $list['menutype']=$list['group'];
	    $builder->keySelect("menutype", "绑定菜单",'', $tmp);
		$builder->keyText("url", "跳转url");	
		$builder->keyText("sort", "排序");	
	

		}else{
		$builder->keySelect("sort", "绑定菜单",'', $type_default);	
		}

	  	$builder->keyText("icon", "图标");
		$builder->keyBool("flag", "是否启用");
		$builder->data($list);
	    $builder->buttonSubmit(U('editmobmenu'), $id == 0 ? "添加" : "修改")->buttonBack();
		$builder->display();  	   
		   }
	}
	
	
	

	//hong 更新时间表配置
	   public function editdate($id = 0)
    {
	
	       if (IS_POST) {
		   //submit logic
		//   dump($_POST);
		   $id=$_POST['Id'];
		    if ($_POST['lesson_date'] == '') {
                $this->error('时间不能为空！');
            }
			if ($_POST['owner'] == '') {
                $this->error('负责人/机构不能为空！');
            }
			if (strlen($_POST['lesson_date'])<>10) {
                $this->error('时间格式不正确！');
            }
			
		      if ($id != '') {
                $res = D('dateorder')->where('Id=' . $id)->save($_POST);
            } else {
                $res = D('dateorder')->add($_POST);
            }

            $this->success($id == '' ? "添加新时间成功" : "编辑时间表成功", U('editdate', array('Id' => $id)));		   
		   
		   }else{
        $list = D('dateorder')->where(array('Id' => $id))->find();
	
        $type=M('Issue_content')->field('id,title')->select();	
		
		foreach($type as $key=>$value){
					 $type_default[$type[$key]['id']]=$type[$key]['title'];
		}	

	
		//   var_dump( $type);
	   	//  var_dump( $type_default);
		//$list = M('user_member')->where($map)->order('create_time desc')->select();
		$builder = new AdminConfigBuilder();
		$builder->title("时间安排列表");
		$builder->meta_title = '修改时间信息';
		$builder->keyReadOnly('Id', 'ID')->keyText("lesson_date", "时间","例：2015-09-09")->keySelect("Issueid", "套餐/会议/课程名", '', $type_default)->keyText("timedesc", "当日具体时间")->keyText('desc', '补充说明');
		$builder->keyText("owner", "负责人/机构");
	//	$builder->keyBool('flag', '是否启用');
		$builder->data($list);
	    $builder->buttonSubmit(U('editdate'), $id == 0 ? "添加" : "修改")->buttonBack();
		$builder->display();  	   
		   }
	}



	
	
	
	
    public function loadchild($id = 1, $status = 0)
    {
	    $loop=$_POST['ids'];
		dump($_GET);
		$field_ids = array_column($loop, 'orderid');
		dump($field_ids);
        $map['orderid']=array('in',$field_ids);
		$list = M('user_member')->where($map)->order('create_time desc')->select();
		$builder = new AdminListBuilder();
		$builder->title("用户报名资料列表");
        $builder->meta_title = '用户报名儿童列表';
		$builder->keyText('title', '课程名')->keyTime("lesson_date", "活动时间时间")->keyText('name', '家长姓名')->keyText('idcard', '身份证号码')->keyText('c_name_0', '第一个儿童姓名')->keyText('c_name_1', '第二个儿童姓名')->keyText('c_name_3', '第三个儿童姓名');
		$builder->data($list);
		$builder->display('child');
	 //   $this->ajaxReturn($data);

	}
	
	

	

	  public function setpay($id = 0, $status = 0)
    {   $loop=$_POST['ids'];
		$i=0;
		//dump($id);
		foreach($loop as $key=>$value){
		$map['orderid']=$value;
		$data['flag']=$status;
		$res=M('user_member')->where($map)->save($data);	
		$i=$i+1;
		
		}
	   
	    $this->success('更新数据成功');
	 //   $this->ajaxReturn($data);

	}
	
  public function setvipcard($id = 0, $status = 0)
    {   
	
	    $loop=$_POST['ids'];
		$i=0;
		if( $status < 0){
			foreach($loop as $key=>$value){
				$map['orderid']=$value;
				$data['status']=$status;
				$res=M('vipcard_order')->where($map)->save($data);	
				$i=$i+1;
				
			}
		}else{
			   foreach($loop as $key=>$value){
				$map['orderid']=$value;
				$data['status']=$status;
				
				
				$res=M('vipcard_order')->where($map)->save($data);	
				if($res>0){
				$vip= new MobApi();
				$res2=$vip->setvip($value);
				
				}
				$i=$i+1;
				
			}
		
		}
		
		
	    if($res>0){
			$this->success('更新数据成功');
		}else{
			$this->error('无法更新状态请重试');
		}
	 //   $this->ajaxReturn($data);

	}

	
  public function vipset($id = 0, $status = 0)
    {   $loop=$_POST['ids'];
		$i=0;
		
		foreach($loop as $key=>$value){
			$map['Id']=$value;
			$data['status']=$status;
			$res=M('vipcard')->where($map)->save($data);	
			$i=$i+1;
			
		}
	    if($res>0){
			$this->success('更新数据成功');
		}else{
			$this->error('无法更新状态请重试');
		}
	 //   $this->ajaxReturn($data);

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