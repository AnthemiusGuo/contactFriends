<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apiqq {
	private $CI;
	public $uid;
	public $cookie_onlineId;

	public function __construct() {
		$this->CI =& get_instance();
		//$this->_user = $this->CI->session->userdata('user');
	}

	public function getQQConfig(){
		$qq = $this->CI->config->item('qq');
		$qq['callback'] = site_url('index/doQQLogin');
		return $qq;
	}

	public function filter_qq_user_info($info){
		$real_info = array();
		$real_info['name'] = $info['nickname'];
		$real_info['province'] = $info['province'];
		$real_info['head_img'] = $info['figureurl_2'];
		return $real_info;
		// 			'ret' => int 0
		//   'msg' => string '' (length=0)
		//   'is_lost' => int 0
		//   'nickname' => string 'Guo Jia郭佳' (length=13)
		//   'gender' => string '男' (length=3)
		//   'province' => string '上海' (length=6)
		//   'city' => string '浦东新区' (length=12)
		//   'year' => string '1980' (length=4)
		//   'figureurl' => string 'http://qzapp.qlogo.cn/qzapp/101200862/0F1E0950223BC1A016710899003113F0/30' (length=73)
		//   'figureurl_1' => string 'http://qzapp.qlogo.cn/qzapp/101200862/0F1E0950223BC1A016710899003113F0/50' (length=73)
		//   'figureurl_2' => string 'http://qzapp.qlogo.cn/qzapp/101200862/0F1E0950223BC1A016710899003113F0/100' (length=74)
		//   'figureurl_qq_1' => string 'http://q.qlogo.cn/qqapp/101200862/0F1E0950223BC1A016710899003113F0/40' (length=69)
		//   'figureurl_qq_2' => string 'http://q.qlogo.cn/qqapp/101200862/0F1E0950223BC1A016710899003113F0/100' (length=70)
		//   'is_yellow_vip' => string '0' (length=1)
		//   'vip' => string '0' (length=1)
		//   'yellow_vip_level' => string '0' (length=1)
		//   'level' => string '0' (length=1)
		//   'is_yellow_year_vip' => string '0' (length=1)
	}

	public function qq_login($appid, $scope, $callback)
	{
	    // $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection

	    $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
	        . $appid . "&redirect_uri=" . urlencode($callback)
	        . "&state=" .  md5(uniqid(rand(), TRUE))
	        . "&scope=".$scope;
	    header("Location:$login_url");
	}

	public function qq_get_user_info($openid,$access_token)
	{
		$qq = $this->getQQConfig();
	    $get_user_info = "https://graph.qq.com/user/get_user_info?"
	        . "access_token=" . $access_token
	        . "&oauth_consumer_key=" . $qq['appid']
	        . "&openid=" . $openid
	        . "&format=json";

	    $info = file_get_contents($get_user_info);
	    $arr = json_decode($info, true);

	    return $arr;
	}

	public function qq_callback($code)
	{
		$qq = $this->getQQConfig();
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
            . "client_id=" . $qq["appid"]. "&redirect_uri=" . urlencode($qq["callback"])
            . "&client_secret=" . $qq["appkey"]. "&code=" . $code;

        $response = file_get_contents($token_url);

        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
				return array('rst'=>-1,'error'=>$msg->error,'msg'=>$msg->error_description);
            }
        }

        $params = array();
        parse_str($response, $params);

        //debug
        return array('rst'=>1,'params'=>$params);
	}

	public function get_openid($access_token)
	{
	    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
	        . $access_token;

	    $str  = file_get_contents($graph_url);
	    if (strpos($str, "callback") !== false)
	    {
	        $lpos = strpos($str, "(");
	        $rpos = strrpos($str, ")");
	        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
	    }

	    $user = json_decode($str);
	    if (isset($user->error))
	    {
			return array('rst'=>-1,'error'=>$msg->error,'msg'=>$msg->error_description);
	    }

	    //debug
	    //echo("Hello " . $user->openid);
	    //set openid to session
		return array('rst'=>1,'openid'=>$user->openid,'client_id'=>$user->client_id);
	}
}
