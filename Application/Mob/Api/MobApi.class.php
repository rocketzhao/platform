<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Mob\Api;
use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminSortBuilder;
use Common\Model\MemberModel;


class MobApi{
    /**
     * 构造方法，实例化操作模型
     */



	
	  /**用户扩展资料信息页
     * @param null $uid
     * @author hongtang
     */
    public function expandinfo_select($user_id,$profile_group_id= null)
    {
       
        $map['status'] = array('egt', 0);
		$map['uid']=$user_id;
        $list = M('Member')->where($map)->order('last_login_time desc')->select();
   	 //dump($list);
        //int_to_string($list);
        //扩展信息查询
        $map_profile['status'] = 1;
        $field_group = D('field_group')->where($map_profile)->select();
		
        $field_group_ids = array_column($field_group, 'id');
		//
        $map_profile['profile_group_id'] = array('eq', $profile_group_id);
		//$map_profile['required'] = array('eq', 1);
		$map_profile['visiable'] = array('eq', 1);
        $fields_list = D('field_setting')->where($map_profile)->order('sort asc')->getField('id,field_name,form_type,profile_group_id');
        $fields_list = array_combine(array_column($fields_list, 'field_name'), $fields_list);
        $fields_list = array_slice($fields_list, 0, 9);//取出前8条，用户扩展资料默认显示8条
	
            $map_field['uid'] = $user_id;
            foreach ($fields_list as $key => $val) {
                $map_field['field_id'] = $val['id'];
                $field_data = D('field')->where($map_field)->getField('field_data');
                if ($field_data == null || $field_data == '') {
                    $tkl[$key] = '';
                } else {
				    $tkl[$key] = $field_data;
					// echo "key:".$key ."val:".$field_data."<br/>";
                }
            }
      //$tkl = array_combine(array_column($tkl, 'field_name'), $tkl);
		return $tkl;
		/*
		  foreach ($tkl as $key => $value) {
            echo "<p>".$key."---".$value."</p><br/>";
        }
		*/

    }

	
	 /**
     * 获取用户VIP 卡优惠信息
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function get_orderinfo($uid,$stat){
	   
       $map_profile['flag'] = $stat; //1 启用
	   $map_profile['uid'] = $uid;
	   //$data = M('user_member')->where($map_profile)->field('orderid,title,lesson_date,count,create_time')->select();
	   $data = M('user_member')->where($map_profile)->order('id desc')->select();
	   
	  	foreach($data as $key=>$value){
		 $data[$key]['childinfo']='';
		 for($i=0;$i<$value['count'];$i++){
		 
		 $data[$key]['childinfo']= $data[$key]['childinfo'].'['.$i.']: 姓名:'.$value['c_name_'.$i].','.$value['c_sex_'.$i].',年龄 :'.$value['c_age_'.$i].'<br/>';
		
		 }	
	
		}
	 
      // dump('get_vipcardinfo: '.M()->getLastSql());
	  // dump($data);
       return  $data;
    }
	
	public function get_comfirmorder($uid){
	   
       $map_profile['flag'] = 1; //1 启用
	   $map_profile['uid'] = $uid;
	   $data = M('user_member')->where($map_profile)->select();
	   
      // dump('get_vipcardinfo: '.M()->getLastSql());
       return  $data;
    }
	
	
	
	
	 /**
     * 获取用户VIP 卡优惠信息
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function get_vipcardinfo($uid){
	
       $map_profile['status'] = 1; //1 启用
	   $map_profile['uid'] = get_uid();
	   $data = M('vipcard')->where($map_profile)->find();
       //dump('get_vipcardinfo: '.M()->getLastSql());
	 //  dump($data);
       return  $data;
    }
	
	
		   /**
     * @param $uid
     * @return mixed
     * 申请VIP资料 保存
     */
    public function setvip($orderid){
	    $cardorder=M('vipcard_order')->where(array('orderid'=>$orderid))->find();
	   	$map_profile['uid'] =$cardorder['uid'];
		$res=0;
		$cnt=0;
		$cnt=M('vipcard')->where($map_profile)->count();
		
	//	dump($cardorder);
		if ($cnt == 1){		
		
		$cardinfo=M('vipcard')->where($map_profile)->find();
		$cardinfo['status']=1;
		$cardinfo['count']=$cardinfo['count']*1+$cardorder['vipcnt']*1;		
		$res=M('vipcard')->where($map_profile)->save($cardinfo);
					
		}else{ 
		$cardinfo['status']=1;
		$cardinfo['uid'] =$cardorder['uid'];
		$cardinfo['title'] =$cardorder['title'];
		$cardinfo['count']=$cardorder['vipcnt']*1;
		$res=M('vipcard')->add($cardinfo);

		}
		
		
	    Return $res;
		
         }
	
	
	
	
		 /**
     * 获取用户VIP 卡优惠结果
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function discount($uid,$count){
	   $res=0;
       $map_profile['status'] = 1; //1 启用
	   $map_profile['uid'] = $uid;
	   $data = $this->get_vipcardinfo($uid);
	   
	   if( $data['count']*1>0 ){
		   $res=$data['count']-$count;
		    if($res>=0){
				$card['count']=$res;
			//	$result = D('vipcard')->where($map_profile)->save($card);
				return  $count; // 卡优惠结果
		   }else{
				$card['count']=0;
			//	$result = D('vipcard')->where($map_profile)->save($card);
				return  $data['count']; //部分卡优惠结果
		   }
	  }else{
	      return  0;
	  }	
        
    }
	
	 /**
     * 获取用户VIP 卡优惠扣减 
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function update_vip($uid,$discnt){
	
       $map_profile['status'] = 1; //1 启用
	   $map_profile['uid'] = $uid;
	   $data = $this->get_vipcardinfo($uid);
	   $card['count']=$data['count']-$discnt;// 卡优惠结果
	   $result = M('vipcard')->where($map_profile)->save($card);
	 //  dump(M()->getLastSql());
	 if($result !== false) {
			 return 0;
			}else{
			 return 1;
			}
	  
	  }	
        
    
	


    /**
     * 获取用户信息
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function info($uid, $is_username = false){
        return $this->model->info($uid, $is_username);
    }
    /**
     * 根据用户名和邮箱获取用户数据
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function lomi($username, $email){
        return $this->model->lomi($username, $email);
    }
    /**
     * 根据用户ID获取用户所以数据
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function reset($uid){
        return $this->model->reset($uid);
    }
    /**
     * 获取用户信息2
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function infos($regip){
        return $this->model->infos($regip);
    }
    /**
     * 检测用户名
     * @param  string  $field  用户名
     * @return integer         错误编号
     */
    public function checkUsername($username){
        return $this->model->checkField($username, 1);
    }

    /**
     * 检测邮箱
     * @param  string  $email  邮箱
     * @return integer         错误编号
     */
    public function checkEmail($email){
        return $this->model->checkField($email, 2);
    }

    /**
     * 检测手机
     * @param  string  $mobile  手机
     * @return integer         错误编号
     */
    public function checkMobile($mobile){
        return $this->model->checkField($mobile, 3);
    }

    /**
     * 更新用户信息
     * @param int $uid 用户id
     * @param string $password 密码，用来验证
     * @param array $data 修改的字段数组
     * @return true 修改成功，false 修改失败
     * @author huajie <banhuajie@163.com>
     */
    public function updateInfo($uid, $password, $data){
        if($this->model->updateUserFields($uid, $password, $data) !== false){
            $return['status'] = true;
        }else{
            $return['status'] = false;
            $return['info'] = $this->model->getError();
        }
        return $return;
    }
    /**
     * 重置用户密码2
     * @param int $uid 用户id
     * @param string $password 密码，用来验证
     * @param array $data 修改的字段数组
     * @return true 修改成功，false 修改失败
     * @author huajie <banhuajie@163.com>
     */
    public function updateInfos($uid, $data){
        if($this->model->updateUserFieldss($uid, $data) !== false){
            $return['status'] = true;
        }else{
            $return['status'] = false;
            $return['info'] = $this->model->getError();
        }
        return $return;
    }

    public function addSyncData(){
        return $this->model->addSyncData();
    }

}
