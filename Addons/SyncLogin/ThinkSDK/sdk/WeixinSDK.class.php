<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: hongtang update <94775674@qq.com> 
// +----------------------------------------------------------------------
// | WeixiSDK.class.php 2015-12-03
// +----------------------------------------------------------------------


class WeixinSDK extends ThinkOauth
{
    /**
     * 获取requestCode的api接口
     * @var string
	 
https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf04b94894762ed07&redirect_uri=http%3A%2F%2Fwww.mybestfit.cn%2Fone%2Fwx.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect 

     */
    protected $GetRequestCodeURL = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://api.weixin.qq.com/';

    public function getRequestCodeURL()
    {
        $this->config();
        $params = array(
            'appid' => $this->AppKey,
			'redirect_uri' => $this->Callback,
		//	'redirect_uri' =>'http://www.mybestfit.cn/one/wx.php',
		//	'redirect_uri' =>'http://www.mybestfit.cn/one/index.php?s=/home/addons/execute/_addons/sync_login/_controller/base/_action/login/type/weixin',
            'response_type' => 'code',
            'scope' => 'snsapi_userinfo',//snsapi_userinfo.snsapi_login
			'state' => 'STATE',
        );
        
       // return $this->GetRequestCodeURL . '?' . http_build_query($params).'#wechat_redirect';
   //    return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf04b94894762ed07&redirect_uri=http%3A%2F%2Fwww.mybestfit.cn%2Fone%2Findex.php%3Fs%3D%2Fhome%2Faddons%2Fexecute%2F_addons%2Fsync_login%2F_controller%2Fbase%2F_action%2Fcallback%2Ftype%2Fweixin.html&response_type=code&scope=snsapi_userinfo&state=STATE&#wechat_redirect'; 
return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->AppKey.'&redirect_uri=http%3A%2F%2Fwww.mybestfit.cn%2Fguoxue%2Findex.php%3Fs%3D%2Fhome%2Faddons%2Fexecute%2F_addons%2Fsync_login%2F_controller%2Fbase%2F_action%2Fcallback%2Ftype%2Fweixin.html&response_type=code&scope=snsapi_userinfo&state=STATE&#wechat_redirect'; 

	}

    /**
     * 获取access_token
     * @param string $code 上一步请求到的code
     */
    public function getAccessToken($code, $extend = null)
    {
        $this->config();
        $params = array(
            'appid' => $this->AppKey,
            'secret' => $this->AppSecret,
            'grant_type' => $this->GrantType,
            'code' => $code,
        );

	//   $url=$this->GetAccessTokenURL . '?' . http_build_query($params);
	//  var_dump($url);
	//   $re = file_get_contents($url);		 
   //    $this->Token = $this->parseToken($re, $extend);
        $data = $this->http($this->GetAccessTokenURL, $params, 'POST');
        $this->Token = $this->parseToken($data, $extend);
        return $this->Token;
    }

    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api 微博API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false)
    {
        /* 腾讯微博调用公共参数 */
        $params = array(
            'access_token' => $this->Token['access_token'],
            'openid' => $this->openid(),
        );
        $vars = $this->param($params, $param);
        $data = $this->http($this->url($api), $vars, $method, array(), $multi);
        return json_decode($data, true);
    }


    /**
     * 解析access_token方法请求后的返回值
     */
    protected function parseToken($result, $extend)
    {
        $data = json_decode($result, true);
        //parse_str($result, $data);
        if ($data['access_token']) {
            $this->Token = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else
            throw new Exception("获取微信 ACCESS_TOKEN 出错：{$result}");
    }

    /**
     * 获取当前授权应用的openid
     */
    public function openid()
    {
        $data = $this->Token;
        if (!empty($data['openid']))
            return $data['openid'];
        else
            exit('没有获取到微信用户ID！');
    }
}

?>
