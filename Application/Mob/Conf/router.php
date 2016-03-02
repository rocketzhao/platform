<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-8-27
 * Time: 下午4:54
 * @author 嘉兴想天信息科技有限公司-郑钟良<zzl@ourstu.com>
 */
return array(

    /**
     * 路由的key必须写全称,且必须全小写. 比如: 使用'wap/index/index', 而非'wap'.
     */
    'router' => array(

        /*微博*/
        'Weibo/Index/weiboDetail'               =>'Mob/Weibo/weiboDetail',

        /*专辑*/
        'Issue/Index/issueContentDetail'        =>'/mob/issue/issuedetail',

        //入口

        /*专辑*/
        'Issue/index/index'                 =>'mob/issue/index',

        /*会员*/
        'People/index/index'               =>'mob/people/index',

        /*微博*/
        'Weibo/index/index'                =>'mob/weibo/index',
        /*用户中心*/
        'Ucenter/index/index'              =>'mob/user/index',

    ),

);