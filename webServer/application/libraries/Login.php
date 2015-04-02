<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login {
	private $CI;
	public $uid;
	public $cookie_onlineId;

	public function __construct() {
		$this->CI =& get_instance();
		//$this->_user = $this->CI->session->userdata('user');
	}

	public function is_login() {
		$logininfo = get_cookie('uinfo');

		if ($logininfo==false){
			return false;
		}

		$loginUser = $this->decode_cookie_data($logininfo);

		if (substr(md5($loginUser['uuid'].$loginUser['login_ts'].'Sa34KJ9'), 10,8)!=$loginUser['auth']){
			return false;
		}
		$rememberme = false;
		if (substr(md5($loginUser['uuid'].$loginUser['login_ts'].'qwerrrr'), 10,8)==$loginUser['rememberme']){
			$rememberme = true;
		}

		//取出数据库的数据
		$sessionUser = $this->get_onlince_info();

		if ($sessionUser==false){
			if ($rememberme) {
				//自动登录

				//先校驗用戶是否存在TODO
				return $this->process_login($loginUser['loginname'],$loginUser['uuid'],true,true);

			} else {
				return false;
			}

		}


		if($loginUser['uuid'] == $sessionUser['uuid']) {
			//判断用户是否登录超时
			$current_ts = time();
			if(empty($sessionUser['login_ts'])
			|| $current_ts - $sessionUser['login_ts'] > $this->CI->config->item('login_expire')) {
				return false;
			}
			if ($current_ts - $sessionUser['login_ts'] > 60) {
				//60秒以上，刷新session
				$this->up_onlince_info(new MongoId($this->cookie_onlineId),array('login_ts'=>$current_ts));
			}


			$this->uid = $sessionUser['uuid'];
			return true;
		}

		return false;
	}

	public function encode_cookie_data($user){
		$cookie_data = base64_encode($user['uid']
									.'|'.$user['uuid']
									.'|'.$user['login_ts']
									.'|'.$user['auth']
									.'|'.$user['rememberme']
									.'|'.$user['onlineId']);
		return $cookie_data;
	}
	public function decode_cookie_data($data){

		$cookie_data = explode('|',base64_decode($data));
		if (count($cookie_data)!=6){
			$user = array(
				'uuid'      => '',
				'uid'      => '',
				'login_ts'  =>0,
				'auth'=> '',
				'rememberme'=>'',
				'onlineId'=>''
			);
		} else {
			$user['uid'] = $cookie_data[0];
			$user['uuid'] = $cookie_data[1];
			$user['login_ts'] = $cookie_data[2];
			$user['auth'] = $cookie_data[3];
			$user['rememberme'] = $cookie_data[4];
			$user['onlineId'] = $cookie_data[5];
		}
		$this->cookie_user = $user;
		$this->cookie_onlineId = $user['onlineId'];
		return $user;
	}

	public function get_onlince_info(){
		if ($this->cookie_onlineId==""){
			return false;
		} else {
			$id = new MongoId($this->cookie_onlineId);
			$this->CI->cimongo->where(array('_id'=>$id));

	        $query = $this->CI->cimongo->get('uOnlineInfo');

	        if ($query->num_rows() > 0)
	        {
	            $result = $query->row_array();
	            return $result;
			} else {
				return false;
			}
		}
	}

	public function save_onlince_info($id,$info){
		$info['_id'] = $id;
		$this->CI->cimongo->insert('uOnlineInfo',$info);
	}
	public function remove_onlince_info(){
		//TODO
	}
	public function up_onlince_info($id,$info){
		$this->CI->cimongo->where(array('_id'=>$id));
		$this->CI->cimongo->update('uOnlineInfo',$info);
	}


	public function process_login($loginname, $uid, $save_cookie = true,$auto_login = false) {
		$zeit =  time();

		$user = array(
			'loginname' => $loginname,
			'uid'      => $uid,
			'uuid'      => $uid,
			'login_ts'  =>$zeit,
			'last_op' =>$zeit,
			'auth'=> substr(md5($uid.$zeit.'Sa34KJ9'), 10,8),
			'rememberme'=>($save_cookie)?substr(md5($uid.$zeit.'qwerrrr'), 10,8):''
		);
		$id = new MongoId();
		$user['onlineId'] = $id->{'$id'};

		$this->save_onlince_info($id,$user);


		if($save_cookie){
			$cookie_timeout = $zeit+86400*30;

		} else {
			$cookie_timeout = '0';
		}

		$cookie = array(
			'name'   => 'uinfo',
			'value'  => $this->encode_cookie_data($user),
			'expire' => $cookie_timeout,
		);
		set_cookie($cookie);
		if ($loginname!=''){
			$cookie = array(
				'name'   => 'loginname',
				'value'  => $loginname,
				'expire' => $zeit+86400*10*365
			);
			set_cookie($cookie);
		}

		return true;
	}


	public function logout() {
		delete_cookie('uinfo');
	}
}
