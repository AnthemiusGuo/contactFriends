<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utility {
	private $CI;

	const TOKEN_CODE_PRE  = 'T_';
	const EMAIL_CODE_PRE  = 'E_';
	const MOBILE_CODE_PRE = 'M_';

	function __construct() {
		$this->CI =& get_instance();
	}

	//判断请求是否是ajax
	function is_ajax_request() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			return true;
		}
		return false;
	}

	function mbstring_2_array($str,$charset='UTF-8') {
   		$strlen=mb_strlen($str);
   		while($strlen){
      		$array[]=mb_substr($str,0,1,$charset);
      		$str=mb_substr($str,1,$strlen,$charset);
      		$strlen=mb_strlen($str);
   		}
   		return $array;
   	}

	//取本月初,周初等各种时间
	function getTS($typ){
		//php获取今日开始时间戳和结束时间戳
		switch ($typ){
			case 'beginToday':
				$ts = mktime(0,0,0,date('m'),date('d'),date('Y'));
				break;
			case 'endToday':
				$ts = mktime(0,0,0,date('m'),date('d')+1,date('Y')-1);
				break;
			case 'beginThismonth':
				$ts = mktime(0,0,0,date('m'),1,date('Y'));
				break;
			case 'endThismonth':
				$ts = mktime(23,59,59,date('m'),date('t'),date('Y'));
				break;
			default:
				$ts = time();
				break;
		}
		return $ts;
	}

	function curl_post($url, $data)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $ret = curl_exec($ch);

	    curl_close($ch);
	    return $ret;
	}

	private function __calc_cn_money($input,$round){
        if ($input<10000){
            return $input;
        } else if ($input<100000000){
			if ($round){
				return round(($input/10000),1).'万';
			} else {
				return ($input/10000).'万';
			}

        } else {
			if ($round){
				return round(($input/100000000),1).'亿';
			} else {
				return ($input/100000000).'亿';
			}
		}
    }

	function calc_cn_money($input,$round=false){
		if (is_array($input)){
			foreach ($input as $k =>$v){
				$input[$k] = $this->__calc_cn_money($v,$round);
			}
			return $input;
		} else {
			return $this->__calc_cn_money($input*1);
		}
    }


	// 访问webservice接口
	function webservice($url, $params, $function) {
		$current_url = current_url();

        try{
			$soap   = new  SoapClient($url , array('soap_version'=>'1.2' , 'encoding' => 'utf-8' , 'style' => SOAP_RPC));
			$result = $soap->__soapCall($function , array('parameters'=>$params));
			//log_scribe('trace', 'webservice', "[{$current_url}] webservice_success: function:{$function} param=".var_export($params, true)."   return : ".var_export($this->object_to_array($result), true));
			return $this->object_to_array($result);
		}catch(Exception $e){
			$current_url = current_url();
			$server_ip = $_SERVER["SERVER_ADDR"];
			log_scribe('trace', 'webservice', "[{$current_url}] webservice_error: pass9ip: {$server_ip} param=".var_export($params, true)."   error_info : ".$e);
			return false;
		}
	}

	//通过代理机访问外部地址(GET方式)
	function get($url, $fields = '') {
		$proxy_url = $this->CI->passport->get('proxy_g');
		if(is_array($fields)) {
			$qry_str = http_build_query($fields);
		} else {
			$qry_str = $fields;
		}
		$proxy_url = $proxy_url.'?'.$qry_str.'&_actual_url='.urlencode($url);
		log_scribe('trace', 'proxy_php', 'GET Request: '.$proxy_url);

		$ch = curl_init();

		// Set query data here with the URL
		curl_setopt($ch, CURLOPT_URL, $proxy_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '10');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = trim(curl_exec($ch));
		curl_close($ch);
		log_scribe('trace', 'proxy_php', 'GET Response: '.$content);
		return $content;
	}

	//通过代理机访问外部地址(POST方式)
	function post($url, $fields) {
		$proxy_url = $this->CI->passport->get('proxy_p');
		if(is_array($fields)) {
			$qry_str = http_build_query($fields);
		} else {
			$qry_str = $fields;
		}
		$qry_str = $qry_str.'&_actual_url='.urlencode($url);
		log_scribe('trace', 'proxy_php', 'POST Request: '.$proxy_url.'?'.$qry_str);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $proxy_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// Set request method to POST
		curl_setopt($ch, CURLOPT_POST, 1);

		// Set query data here with CURLOPT_POSTFIELDS
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);

		$content = trim(curl_exec($ch));
		curl_close($ch);
		log_scribe('trace', 'proxy_php', 'POST Response: '.$content);
		return $content;
	}

	function object_to_array(stdClass $Class)
    {
        # Typecast to (array) automatically converts stdClass -> array.
        $Class = (array)$Class;

        # Iterate through the former properties looking for any stdClass properties.
        # Recursively apply (array).
        foreach($Class as $key => $value){
            if(is_object($value)&&get_class($value)==='stdClass'){
                $Class[$key] = $sthis->object_to_array($value);
            }
        }
        return $Class;
    }

	//长账号缩写
	function shorten_loginname($val) {
		if(strlen($val) > 10) {
			$val = substr($val, 0, 3).'***'.substr($val, -3, 3);
		}
		return $val;
	}

	//邮箱缩写
	function shorten_email($val) {
		$_t = explode('@', $val);
		if(strlen($_t[0]) > 3) {
			$name = substr($_t[0], 0, 3).'***';
		} else {
			$name = substr($_t[0], 0, 1).'***';
		}
		$domain = $_t[1];
		return $name.'@'.$domain;
	}

	//手机缩写
	function shorten_mobile($val) {
		$val = substr($val, 0, 3).'*****'.substr($val, -3, 3);
		return $val;
	}

	//身份证缩写
	function shorten_cert_id($val) {
		$val = substr($val, 0, 3).'************'.substr($val, -3, 3);
		return $val;
	}

	//检查时间格式
	function chk_timestamp($time, $format = 'YmdHis') {
		$tmp = date_parse_from_format($format, $time);
		if($tmp['warning_count'] > 0 || $tmp['error_count'] > 0) {
			$this->CI->error->set_error('10084');
			return false;
		}
		return $tmp;
	}

	//检查第三方订单号格式
	function chk_sp_order_id($val) {
		if(!preg_match('/^\w{1,20}$/', $val)) {
			$this->CI->error->set_error('16003');
			return false;
		}
		return true;
	}

	//检查服务编号格式
	function chk_service_id($val) {
		if(!preg_match('/^[0-9]{1,12}$/', $val)) {
			$this->CI->error->set_error('16002');
			return false;
		}
		return true;
	}

	//检查游戏简称及对应配置是否存在
	function chk_site_cd($val, $chk_config = true) {
		if(!preg_match('/^[A-Z0-9]{1,5}$/', $val)) {
			$this->CI->error->set_error('10010');
			return false;
		}
		$_game_array = $this->CI->passport->get('game_array');
		if(!isset($_game_array[$val]) && $chk_config === true) {
			$this->CI->error->set_error('10125');
			return false;
		}
		return true;
	}

	//检查游戏站点编号
	function chk_site_id($val) {
		if(!preg_match('/^[a-zA-Z0-9]{4}$/', $val)) {
			$this->CI->error->set_error('10126');
			return false;
		}
		return true;
	}

	/**
	 * 检查登录账号格式(所有类型)
	 * $restrict true - 不兼容老账号格式(主要用于新账号注册); false - 兼容老账号格式(用于功能中账号格式检查)
	 * $forbid true - 检查禁用的账号和前后缀; false - 不做禁用账号和前后缀校验
	 */
	function chk_loginname($val, $restrict = false, $forbid = true) {
		if(!$this->chk_normal_loginname($val, $restrict) && !$this->chk_email_loginname($val) && !$this->chk_mobile_loginname($val)) {
			$this->CI->error->set_error('10139');
			return false;
		}
		if($forbid === true) {
			$forbid_account = $this->CI->passport->get('register_forbid_account');
			if(in_array($val, $forbid_account)){
				$this->CI->error->set_error('10148');
				return false;
			}
			$forbid_pre = $this->CI->passport->get('register_forbid_pre');
			foreach($forbid_pre as $pre){
				if(substr($val, 0, strlen($pre)) == $pre){
					$this->CI->error->set_error('10146');
					return false;
				}
			}
			$forbid_suffix = $this->CI->passport->get('register_forbid_suffix');
			foreach($forbid_suffix as $suffix){
				if(substr($val, -1 * strlen($suffix)) == $suffix){
					$this->CI->error->set_error('10147');
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * 检查普通账号格式
	 * $restrict true - 不兼容老账号格式(主要用于新账号注册); false - 兼容老账号格式(用于功能中账号格式检查)
	 */
	function chk_normal_loginname($val, $restrict = false) {
		if($restrict === true) {
			if(strlen($val) < 6 || strlen($val) > 20) {
				$this->CI->error->set_error('10089');
				return false;
			}
			if(!preg_match('/^[A-Za-z_]{1}[A-Za-z0-9_\-]{5,19}$/', $val)) {
				$this->CI->error->set_error('10088');
				return false;
			}
			return true;
		} else {
			if(strlen($val) < 3 || strlen($val) > 20) {
				$this->CI->error->set_error('10089');
				return false;
			}
			if(!preg_match('/^[A-Za-z0-9_]{1}[A-Za-z0-9_\-]{2,19}$/', $val)) {
				$this->CI->error->set_error('10088');
				return false;
			}
			return true;
		}
	}

	//检查邮箱账号格式
	function chk_email_loginname($val) {
		if(!$this->chk_email($val)) {
			$this->CI->error->set_error('10087');
			return false;
		}
		return true;
	}

	//检查手机账号格式
	function chk_mobile_loginname($val) {
		return $this->chk_mobile($val);
	}

	//检查手机号码格式
	function chk_mobile($val) {
		if(!preg_match('/^(13|14|15|18)\d{9}$/', $val)) {
			$this->CI->error->set_error('10036');
			return false;
		}
		return true;
	}

	//检查登录密码格式
	function chk_pwd($val) {
		if(!preg_match('/^[a-zA-Z0-9_]{6,16}$/', $val)) {
			$this->CI->error->set_error('10029');
			return false;
		}
		return true;
	}

	//检查支付密码格式
	function chk_paypwd($val) {
		if(!preg_match('/^[a-zA-Z0-9_]{6,12}$/', $val)) {
			$this->CI->error->set_error('10149');
			return false;
		}
		return true;
	}

	//检查用户昵称格式
	function chk_nickname($val) {
		require_once 'Tuxedo/inc/forbidden_words.inc.php';
		if(!forbidden_nickname($val)) {
			$this->CI->error->set_error('10042');
			return false;
		}
		return true;
	}

	//检查用户姓名格式
	function chk_realname($val) {
		if(!$this->is_big5($val)) {
			$this->CI->error->set_error('10043');
			return false;
		}
		require_once 'Tuxedo/inc/forbidden_words.inc.php';
		if(!forbidden_realname($val)) {
			$this->CI->error->set_error('10043');
			return false;
		}
		return true;
	}

	//检查是否是中文
	function is_big5($val) {
		if(preg_match("/[0-9\.\@\,\+\-\=\a-z]/i", $val)) {
			return false;
		}
		return true;
	}

	//检查性别格式
	function chk_gender($val) {
		if($val != 'F' && $val != 'M') {
			$this->CI->error->set_error('10045');
			return false;
		}
		return true;
	}

	//检查婚否格式
	function chk_marriage ($val){
		if($val != 'Y' && $val != 'N'){
			$this->CI->error->set_error('10046');
			return false;
		}
		return true;
	}

	//检查地址格式
	function chk_address($val) {
		require_once 'Tuxedo/inc/forbidden_words.inc.php';
		if(strlen($val) > 80) {
			$this->CI->error->set_error('10058');
			return false;
		}
		if(!validate_text($val)) {
			$this->CI->error->set_error('10049');
			return false;
		}
		return true;
	}

	//检查邮编格式
	function chk_zipcode($val) {
		if(!preg_match('/^\d{6}$/', $val)) {
			$this->CI->error->set_error('10051');
			return false;
		}
		return true;
	}

	//检查固定电话格式
	function chk_telephone($val) {
		if(!preg_match('/^\d{3}-\d{8}$|^\d{4}-\d{7}$|^\d{4}-\d{8}$|^\d{7,8}$/', $val)) {
			$this->CI->error->set_error('10063');
			return false;
		}
		return true;
	}

	//检查即时聊天账号信息格式
	function chk_im($val) {
		if($this->chk_email($val)) {
			return true;
		} else if(preg_match('/^[1-9][0-9]{4,49}$/', $val)) {
			return true;
		}
		$this->CI->error->set_error('10065');
		return false;
	}

	//检查用户身份证号码
	function chk_cert_id($val) {
		$val = strtoupper($val);
		#验证身份证长度以及字符集(15位：全数字或18位：数字加字母X)
		if(!preg_match('/^(([0-9]{17}[0-9X]{1})|[0-9]{15})$/', $val)) {
			$this->CI->error->set_error('10028');
			return false;
		}
		$length = strlen($val);
		#截取身份证内的地区、出生年月日、扩展位信息
		$areaId = substr($val, 0, 6);
		if($length == 15) {
			$year = '19'.substr($val, 6, 2);
			$month = substr($val, 8, 2);
			$day = substr($val, 10, 2);
			$ext = substr($val, 12, 3);
		} else {
			$year = substr($val, 6, 4);
			$month = substr($val, 10, 2);
			$day = substr($val, 12, 2);
			$ext = substr($val, 14, 4);
		}
		#检查身份证内的地区信息
		$areaRangeList = array(
			'110000-110230',
			'120000-120230',
			'130000-133100',
			'140000-143000',
			'150000-153000',
			'210000-211500',
			'220000-229050',
			'230000-250550',
			'310000-312700',
			'320000-321400',
			'330000-339020',
			'340000-343000',
			'350000-359050',
			'360000-369050',
			'370000-380000',
			'410000-413100',
			'420000-429100',
			'430000-445400',
			'450000-452900',
			'460000-469500',
			'500000-500400',
			'510000-514000',
			'519000-519010',
			'520000-522800',
			'530000-533600',
			'540000-542700',
			'610000-612800',
			'620000-623100',
			'630000-632900',
			'640000-642300',
			'650000-659100',
			'710000-710100',
			'810000-810100',
			'820000-820100',
		);
		$area_check_flag = false;
		foreach($areaRangeList as $areaRange) {
			list($top, $bottom) = explode('-', $areaRange);
			if($areaId >= $top && $areaId <= $bottom) {
				$area_check_flag = true;
				break;
			}
		}
		if(!$area_check_flag) {
			$this->CI->error->set_error('10028');
			return false;
		}
		#验证出生年月日信息(日期格式合法且不得晚于当前日期)
		if(!checkdate($month, $day, $year) || date('Ymd') < $year.$month.$day) {
			$this->CI->error->set_error('10028');
			return false;
		}
		#针对18位身份证最后位进行校验
		if($length == 18) {
			$s = 0;
			$wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
			for($i = 0; $i < 17; $i++) {
				$s += $val[$i] * $wi[$i];
			}
			$y = $s % 11;
			switch($y) {
				case '0':
					$verify_code = '1';
					break;
				case '1':
					$verify_code = '0';
					break;
				case '2':
					$verify_code = 'X';
					break;
				case '3':
					$verify_code = '9';
					break;
				case '4':
					$verify_code = '8';
					break;
				case '5':
					$verify_code = '7';
					break;
				case '6':
					$verify_code = '6';
					break;
				case '7':
					$verify_code = '5';
					break;
				case '8':
					$verify_code = '4';
					break;
				case '9':
					$verify_code = '3';
					break;
				case '10':
					$verify_code = '2';
					break;
			}
			if(substr($val, 17, 1) != $verify_code) {
				$this->CI->error->set_error('10028');
				return false;
			}
		}
		return true;
	}

	/**
	 * 获取身份证内的出生日期
	 */
	function get_cert_id_birth($cert_id) {
		$length = strlen($cert_id);
		if($length == 15) {
			$cert_id = $this->cert_id_15to18($cert_id);
		}
		$year  = substr($cert_id, 6, 4);
		$month = substr($cert_id, 10, 2);
		$day   = substr($cert_id, 12, 2);
		$ext   = substr($cert_id, 14, 4);
		return array(
				'year'  => $year,
				'month' => $month,
				'day'   => $day,
				'ext'   => $ext,
		);
	}

	/**
	 *  校验是否小于18岁
	 */
	function is_cert_underage($cert_id) {
		$cert_date = $this->get_cert_id_birth($cert_id);

		if(($cert_date['year'].$cert_date['month'].$cert_date['day']) > date ("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y") - 18))) {
			return true;
		}
		return false;
	}

	/**
	 * 身份证从15位扩展至18位
	 */
	function cert_id_15to18($cert_id) {
		if (strlen($cert_id) != 15){
			//TODO
			return false;
		} else {
			// 如果身份证顺序码是996 997 998 999,这些是为百岁以上老人的特殊编码
			if(array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
				$cert_id = substr($cert_id, 0, 6).'18'.substr($cert_id, 6, 9);
			}else{
				$cert_id = substr($cert_id, 0, 6).'19'.substr($cert_id, 6, 9);
			}
		}
		$cert_id = $cert_id.$this->get_cert_id_verify_code($cert_id);
		return $cert_id;
	}

	/**
	 * 计算18位身份证校验位
	 * @param unknown_type $idcard_base
	 * @return boolean|string
	 */
	function get_cert_id_verify_code($cert_id_base){
		if(strlen($cert_id_base) != 17) {
			//TODO
			return false;
		}
		// 加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

		// 校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

		$checksum = 0;
		for($i = 0; $i < strlen($cert_id_base); $i++) {
			$checksum += substr($cert_id_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_code = $verify_number_list[$mod];

		return $verify_code;
	}

	//检查电子邮箱格式
	function chk_email($val) {
		if(strlen($val) < 6 || strlen($val) > 50) {
			$this->CI->error->set_error('10085');
			return false;
		}
		if(!preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*(\.[a-zA-Z0-9]+[-a-zA-Z0-9]*)+[a-zA-Z0-9]+$/', $val)) {
			$this->CI->error->set_error('10086');
			return false;
		}
		return true;
	}

	//根据email查询邮件供应商
	function email_vendor($email) {
		if(!$this->chk_email($email)) {
			return null;
		}
		$email_vendor_list = array(
			'126.com' => 'http://mail.126.com',
			'139.com' => 'http://mail.139.com',
			'163.cn' => 'http://mail.163.cn',
			'163.com' => 'http://mail.163.com',
			'163.net' => 'http://mail.163.net',
			'188.com' => 'http://mail.188.com',
			'189.cn' => 'http://mail.189.cn',
			'2008.sina.com' => 'http://mail.2008.sina.com',
			'21cn.com' => 'http://mail.21cn.com',
			'263.net' => 'http://mail.263.net',
			'520.com' => 'http://mail.520.com',
			'china.com' => 'http://mail.china.com',
			'chinaren.com' => 'http://mail.chinaren.com',
			'citiz.net' => 'http://mail.citiz.net',
			'eyou.com' => 'http://mail.eyou.com',
			'foxmail.com' => 'http://mail.foxmail.com',
			'live.cn' => 'http://mail.live.cn',
			'live.com' => 'http://mail.live.com',
			'msn.com' => 'http://mail.msn.com',
			'qq.com' => 'http://mail.qq.com',
			'sina.com' => 'http://mail.sina.com',
			'sina.com.cn' => 'http://mail.sina.com.cn',
			'sogou.com' => 'http://mail.sogou.com',
			'sohu.com' => 'http://mail.sohu.com',
			'tom.com' => 'http://mail.tom.com',
			'vip.qq.com' => 'http://mail.vip.qq.com',
			'yahoo.cn' => 'http://mail.yahoo.cn',
			'yahoo.com' => 'http://mail.yahoo.com',
			'yahoo.com.cn' => 'http://mail.yahoo.com.cn',
			'yahoo.com.hk' => 'http://mail.yahoo.com.hk',
			'yahoo.com.tw' => 'http://mail.yahoo.com.tw',
			'yeah.net' => 'http://mail.yeah.net',
			'ynet.com' => 'http://mail.ynet.com',
			'snda.com' => 'http://mail.snda.com',
			'corp.the9.com' => 'http://mail.corp.the9.com',
			'gmail.com' => 'http://gmail.com',
			'hotmail.com' => 'http://hotmail.com',
			'vip.163.com' => 'http://vip.163.com',
			'vip.sina.com' => 'http://vip.sina.com',
			'vip.sohu.com' => 'http://vip.sohu.com',
		);
		$_t = explode('@', $email);
		$email_domain = $_t[1];
		if(isset($email_vendor_list[$email_domain])) {
			return $email_vendor_list[$email_domain];
		}
		return null;
	}

	//检查推广号格式
	function chk_spread_id($val) {
		if(!preg_match('/^[_a-zA-Z0-9-]{3,50}$/', $val)) {
			$this->CI->error->set_error('12007');
			return false;
		}
		return true;
	}

	//检查密保卡序列号格式
	function chk_matrix_id($val) {
		if(!preg_match('/^[0-9A-Z]{20}$/', $val)) {
			$this->CI->error->set_error('14004');
			return false;
		}
		return true;
	}

	//检查九城实卡卡密格式是否是21位数字
	function chk_precard_no($val) {
		if(!preg_match('/^[0-9]{21}$/', $val)) {
			$this->CI->error->set_error('10017');
			return false;
		}
		return true;
	}

	//检查整数数字格式(如订单号、卡号等)
	//$digit - 数字位数
	//$fixed - 是否定长,如$digit为4,则匹配：1000～9999为真,其余为假
	//$zero_padding_left - 是否在左侧填充0值,如$digit为4,则匹配：0000～9999为真
	function chk_numeric($val, $digit = 16, $fixed = false, $zero_padding_left = false) {
		if(!is_numeric($val) || $digit <= 0) {
			return false;
		}
		if($fixed) {
			if($zero_padding_left) {
				$rexp = '/^[0-9]{'.$digit.'}$/';
			} else {
				if($digit == 1) {
					$rexp = '/^[0-9]$/';
				} else {
					$rexp = '/^[1-9]{1}[0-9]{'.($digit - 1).'}$/';
				}

			}
		} else {
			if($zero_padding_left) {
				$rexp = '/^[0-9]{1,'.$digit.'}$/';
			} else {
				if($digit == 1) {
					$rexp = '/^[0-9]$/';
				} else {
					$rexp = '/^[1-9]{1}[0-9]{1,'.($digit - 1).'}$/';
				}
			}
		}
		if(!preg_match($rexp, $val)) {
			return false;
		}
		return true;
	}

	//获取最近的年月份
	function get_year_monthes($n) {
		$date = array();
		for($i = 0 ; $i < $n ; $i++) {
			$tmp = date('Ym', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
			$date[$i] = $tmp;
		}
		return $date;
	}

	//获取token验证码
	function get_token_code($force = true) {
		$session_id = $this->CI->session->userdata('session_id');
		$expire = $this->CI->passport->get('token_code_expire');
		$length = $this->CI->passport->get('token_code_length');
		$this->CI->load->driver('cache');
		$token_code = $this->CI->cache->memcached->get(self::TOKEN_CODE_PRE.$session_id);
		if(empty($token_code) || $force) {
			$token_code = $this->gen_rand_str($length);
			$this->CI->cache->memcached->save(self::TOKEN_CODE_PRE.$session_id, $token_code, $expire);
		}
		return $token_code;
	}

	//验证token验证码
	function verify_token_code($token_code, $unset = true) {
		$this->CI->load->driver('cache');

		$session_id = $this->CI->session->userdata('session_id');
		$key = self::TOKEN_CODE_PRE.$session_id;
		$code = $this->CI->cache->memcached->get($key);
		if(empty($code)) {
			$this->CI->error->set_error('10136');
			if($unset) {
				$this->CI->cache->memcached->delete($key);
			}
			return false;
		}
		if($token_code != $code) {
			$this->CI->error->set_error('10136');
			if($unset) {
				$this->CI->cache->memcached->delete($key);
			}
			return false;
		}
		if($unset) {
			$this->CI->cache->memcached->delete($key);
		}
		return true;
	}

	//获取邮箱验证码
	function get_email_code($email, $force = true) {
		$url = current_url();
		$ip  = $this->CI->input->ip_address();

		$expire = $this->CI->passport->get('email_code_expire');
		$length = $this->CI->passport->get('email_code_length');
		$this->CI->load->driver('cache');
		$email_code = $this->CI->cache->memcached->get(self::EMAIL_CODE_PRE.$email);
		if(empty($email_code) || $force) {
			$email_code = $this->gen_rand_str($length, 'numeric');
			$ret = $this->CI->cache->memcached->save(self::EMAIL_CODE_PRE.$email, $email_code, $expire);
			$ret === false && log_scribe('trace', 'memcached', "{$ip} [{$url}] get_email_code : save_error key=".self::EMAIL_CODE_PRE.$email.' value='.$email_code);
		}
		return $email_code;
	}

	//验证邮箱验证码
	function verify_email_code($email, $email_code, $unset_on_true = false, $unset_on_false = false) {
		$url = current_url();
		$ip  = $this->CI->input->ip_address();

		$this->CI->load->driver('cache');
		if(!$this->chk_email($email)) {
			return false;
		}
		$code = $this->CI->cache->memcached->get(self::EMAIL_CODE_PRE.$email);
		$code === false && log_scribe('trace', 'memcached', "{$ip} [{$url}] verify_email_code : get_error key=".self::EMAIL_CODE_PRE.$email);

		if(empty($code)) {
			$this->CI->error->set_error('10132');
			if($unset_on_false) {
				$ret = $this->CI->cache->memcached->delete(self::EMAIL_CODE_PRE.$email);
				//$ret === false && log_scribe('trace', 'memcached', "{$ip} [{$url}] verify_email_code : delete_error key=".self::EMAIL_CODE_PRE.$email);
			}
			return false;
		}
		if($email_code != $code) {
			$this->CI->error->set_error('10133');
			if($unset_on_false) {
				$ret = $this->CI->cache->memcached->delete(self::EMAIL_CODE_PRE.$email);
				//$ret === false && log_scribe('trace', 'memcached', "{$ip} [{$url}] verify_email_code : delete_error key=".self::EMAIL_CODE_PRE.$email);
			}
			return false;
		}
		if($unset_on_true) {
			$ret = $this->CI->cache->memcached->delete(self::EMAIL_CODE_PRE.$email);
			//$ret === false && log_scribe('trace', 'memcached', "{$ip} [{$url}] verify_email_code : delete_error key=".self::EMAIL_CODE_PRE.$email);;
		}
		return true;
	}

	//获取手机验证码
	function get_mobile_code($mobile, $force = true) {
		$expire = $this->CI->passport->get('mobile_code_expire');
		$length = $this->CI->passport->get('mobile_code_length');
		$this->CI->load->driver('cache');
		$mobile_code = $this->CI->cache->memcached->get(self::MOBILE_CODE_PRE.$mobile);
		if(empty($mobile_code) || $force) {
			$mobile_code = $this->gen_rand_str($length, 'numeric');
			$this->CI->cache->memcached->save(self::MOBILE_CODE_PRE.$mobile, $mobile_code, $expire);
		}
		return $mobile_code;
	}

	//验证手机验证码
	function verify_mobile_code($mobile, $mobile_code, $unset_on_true = false, $unset_on_false = false) {
		$this->CI->load->driver('cache');
		if(!$this->chk_mobile($mobile)) {
			return false;
		}
		$code = $this->CI->cache->memcached->get(self::MOBILE_CODE_PRE.$mobile);
		if(empty($code)) {
			$this->CI->error->set_error('10128');
			if($unset_on_false) {
				$this->CI->cache->memcached->delete(self::MOBILE_CODE_PRE.$mobile);
			}
			return false;
		}
		if($mobile_code != $code) {
			$this->CI->error->set_error('10129');
			if($unset_on_false) {
				$this->CI->cache->memcached->delete(self::MOBILE_CODE_PRE.$mobile);
			}
			return false;
		}
		if($unset_on_true) {
			$this->CI->cache->memcached->delete(self::MOBILE_CODE_PRE.$mobile);
		}
		return true;
	}

	//获取随机字符串
	function gen_rand_str($len, $type = null) {
		switch($type) {
			case 'reduced':
				$chars = array(
					"a", "c", "d", "e", "f", "g", "h", "i", "j", "k",
					"m", "n", "p", "r", "s", "t", "u", "v",
					"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
					"H", "J", "K", "L", "M", "N", "P", "Q", "R",
					"S", "T", "U", "V", "W", "X", "Y", "Z", "2",
					"3", "4", "5", "7", "8",
				);
				break;
			case 'numeric':
				$chars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
				break;
			default:
				$chars = array(
					"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
					"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
					"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
					"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
					"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
					"3", "4", "5", "6", "7", "8", "9",
				);
				break;
		}
		$chars_len = count($chars) - 1;
		shuffle($chars);
		$output = "";
		for($i = 0; $i < $len; $i++) {
			$output .= $chars[mt_rand(0, $chars_len)];
		}
		return $output;
	}

	//发送邮件(svcnode实现)
	function send_email($to, $from, $title, $content) {
		Nodesvc::send_email($title, $content, $from, $to);
		if($this->CI->error->error()) {
			return false;
		}
		return true;
	}

	const MESSAGE_SEPARATOR   = '`';

	//发送用户消息
	function send_msg($uuid, $category, $subcategory, $event) {
		$argv = func_get_args();

		$args = '';
		if(count($argv) > 4) {
			$args = implode(self::MESSAGE_SEPARATOR, array_slice($argv, 4));
		}

		if(!$tpl_id = $this->get_msg_tpl($category, $subcategory, $event)) {
			return false;
		}

		$input = array(
			'uuid'   => $uuid,
			'msg_id' => $tpl_id,
			'args'   => $args,
		);
		$ret = Messagesvc::sendMessage($input);
		if($this->CI->error->error()) {
			return false;
		}
		return true;
	}

	//查询用户当月未阅读消息数
	function get_unread_msg_count($uuid) {
		$start_ts = date("Ym01000000", strtotime(date('Ym').'01'));
		$end_ts   = date("Ymt235959", strtotime(date('Ym').'01'));
		$input = array(
			'uuid'     => $uuid,
			'start_ts' => $start_ts,
			'end_ts'   => $end_ts,
			'types'    => '*',
		);
		$ret = Messagesvc::messageCount($input);
		if($this->CI->error->error()) {
			return false;
		}
		return $ret['data']['unread'];
	}

	//增加用户消息默认配置
	function msg_default_settings($uuid) {
		$input =   array('uuid' => $uuid);
		$ret   =   Messagesvc::addUser($input);
		if($this->CI->error->error()) {
			return false;
		}
		return true;
	}

	function get_msg_category() {
		$message_cfg = $this->CI->passport->get('message_cfg');
		return array_keys($message_cfg);
	}

	function get_msg_types($category = '') {
		$message_cfg = $this->CI->passport->get('message_cfg');

		if($category == 'all') {
			$category = '';
		}
		$types = '';
		if($category != '') {
			$_sub_list = $message_cfg[$category];
			foreach($_sub_list as $_sub => $_conf) {
				$types .= $_conf['type'];
			}
		} else {
			foreach($message_cfg as $_cate => $_sub_list) {
				foreach($_sub_list as $_sub => $_conf) {
					$types .= $_conf['type'];
				}
			}
		}
		return $types;
	}

	//获取用户消息模板id
	function get_msg_tpl($category, $subcategory, $event) {
		$message_cfg = $this->CI->passport->get('message_cfg');
		if(!isset($message_cfg[$category][$subcategory]['id'][$event])) {
			$this->CI->error->set_error('10134');
			return false;
		}
		return $message_cfg[$category][$subcategory]['id'][$event];
	}

	//获取用户消息分类
	function msg_type2category($type = '') {
		$message_cfg = $this->CI->passport->get('message_cfg');

		$_result = array();
		foreach($message_cfg as $category => $_cate_cfg) {
			foreach($_cate_cfg as $subcategory => $_sub_cfg) {
				$_result[$_sub_cfg['type']] = $category;
			}
		}

		if($type != '') {
			if(!isset($_result[$type])) {
				$this->CI->error->set_error('10134');
				return false;
			}
			return $_result[$type];
		}
		return $_result;
	}

	//获取用户消息分类名称
	function msg_category_name($category) {
		$msg_category = $this->CI->passport->get('msg_category');
		if(!isset($msg_category[$category])) {
			$this->CI->error->set_error('10134');
			return false;
		}
		return $msg_category[$category];
	}

	//获取用户消息分类描述
	function msg_category_desc($category) {
		$category_desc = $this->CI->passport->get('msg_category_desc');
		if(!isset($category_desc[$category])) {
			$this->CI->error->set_error('10134');
			return false;
		}
		return $category_desc[$category];
	}

	//通过节点路径返回字符串的某个节点值
	function get_data_for_xml($res_data, $node) {
		$xml = simplexml_load_string($res_data);
		$result = $xml->xpath($node);
		//var_dump($result);

		while(list( , $node) = each($result)) {
			return $node;
		}
	}

	//查询用户曾激活过的游戏
	function get_user_active_game($uuid) {
		$result = array();
		$ret = Activesvc::getUserActiveStatus($this->CI->tuxedo->su(Svc::SVCID_ACTIVE_INFO, $uuid));
		if($this->CI->error->error()) {
			//后台返回用户未激活任何大区时,返回空数组
			if($this->CI->error->get_error() == '5328') {
				return $result;
			}
			return false;
		}

		foreach($ret['data']['partStatus'] as $k => $v) {
			if(preg_match('/[^0]/', $v)) {
				$result[] = $ret['data']['gameList'][$k];
			}
		}
		return $result;
	}

	//获得用户激活过的游戏大区
	function get_user_active_part($uuid, $site_cd) {
		$ret = Activesvc::getUserActiveGameStatus($this->CI->tuxedo->su(Svc::SVCID_ACTIVE_INFO, $uuid), array('site_cd' => $site_cd));
		if($this->CI->error->error()) {
			return array();
		}

		$_part_status = $ret['data']['gameStatusArray'];
		$result = array();
		foreach($_part_status as $part_id => $status) {
			if($this->CI->passport->is_part_status_active($status)) {
				$result[] = $part_id;
			}
		}
		return $result;
	}

	//是否是用户激活过的游戏大区
	function is_user_active_part($uuid, $site_cd, $part_id) {
		if(in_array($part_id, $this->get_user_active_part($uuid, $site_cd))) {
			return true;
		}
		return false;
	}

	//查询用户可充值的游戏
	function get_user_chargeable_game($uuid) {
		$_game = $this->CI->passport->chargeable_game_list();
		$_user_game = $this->get_user_active_game($uuid);
		$result = array();
		if(!$_user_game) {
			return $result;
		}
		foreach($_game as $site_cd) {
			//网页游戏无激活可充值
			if($this->CI->passport->is_web_game($site_cd)) {
				$result[] = $site_cd;
			} else if(in_array($site_cd, $_user_game)) {
				$result[] = $site_cd;
			}
		}
		return $result;
	}

	//查询用户账号下的某款游戏是否可充值
	function is_user_chargeable_game($uuid, $site_cd) {
		if(in_array($site_cd, $this->get_user_chargeable_game($uuid))) {
			return true;
		}
		return false;
	}

	//查询用户可充值的游戏大区
	function get_user_chargeable_part($uuid, $site_cd) {
		if(!$this->is_user_chargeable_game($uuid, $site_cd)) {
			return array();
		}
		$ret = Activesvc::getUserActiveGameStatus($this->CI->tuxedo->su(Svc::SVCID_ACTIVE_INFO, $uuid), array('site_cd' => $site_cd));
		if($this->CI->error->error()) {
			return array();
		}
		$_part_status = $ret['data']['gameStatusArray'];
		$result = array();
		$_pay_part_cfg = $this->CI->passport->get_pay_part_list($site_cd);
		foreach($_part_status as $part_id => $status) {
			if($this->CI->passport->is_part_status_chargeable($status) && in_array($part_id, $_pay_part_cfg)) {
				$result[] = $part_id;
			}
		}
		return $result;
	}

	//查询用户某游戏大区是否可充值
	function is_user_chargeable_part($uuid, $site_cd, $part_id) {
		if(in_array($part_id, $this->get_user_chargeable_part($uuid, $site_cd))) {
			return true;
		}
		return false;
	}

	function pay_status_to_string($status, $def='') {
		switch ($status) {
			case 'INITIAL' :
			case 'INITAL' :
			case 'PAYING' :
				return '未支付';
			case 'PROCESSING' :
				return '处理中';
			case 'SUCCEED' :
				return '成功';
			case 'FAILED' :
				return '失败';
			case 'TIMEOUT_CLOSED':
				return '过期';
			case 'UNKNOWN' :
			case 'UNKOWN' :
			default :
				return empty($status)?$def:$status;
		}
	}

	function order_type_to_string($type,$def='') {
		switch ($type) {
			case 'O' : return '划入';
			case 'I' : return '划出';
			default :
				return  empty($type)?$def:$type;
		}
	}

	function detail_status_to_string($status,$def='') {
		switch ($status) {
			case 'I' :
				return '未支付';
			case 'S' :
				return '成功';
			case 'P' :
				return '处理中';
			case 'T' :
				return '过期';
			case 'F' :
				return '失败';
			default :
				return empty($status)?$def:$status;
		}
	}

	function tradeway_to_string($type, $def = '', $service_id = null) {
		switch ($type) {
			case 'A' : return '支付宝';
			case 'B' : return '九城钱包';
			case 'C' : return '中国移动手机话费';
			case 'D' :
				if($service_id == '2602') {
					return '游戏直充';
				}
				return '直充(游戏退款)';
			case 'E' : return '经销商Esales';
			case 'F' : return '神州付神州行';
			case 'G' : return '手机钱包';

			case 'H' : return '汇付天下银行/网关';
			case 'I' : return 'IPS银行/网关';
			case 'J' : return '骏网';
			case 'K' : return '支付宝手机银行/网关';
			case 'L' : return '支付宝银行/网关';
			case 'M' : return '手机短信';
			case 'N' : return '神州付电信卡';

			case 'O' : return '新浪微博账户';
			case 'P' : return '九城实卡';
			case 'Q' : return '快钱';
			case 'R' : return '神州付联通卡';
			case 'S' : return '快钱神州行';
			case 'T' : return '包月游戏时长变更';

			case 'U' : return '快钱联通卡';
			case 'V' : return '新浪微博银行/网关';
			case 'W' : return '手机WAP支付';
			case 'X' : return '快钱电信卡';
			case 'Y' : return 'V币';
			case 'Z' : return '系统赠点bonus';

			case '1' : return '新宽联网银';
			case '2' : return '新宽联神州行';
			case '3' : return '新宽联电信卡';
			case '4' : return '新宽联联通卡';
			case '5' : return '支付宝代扣费';
			case '6' : return '江苏电信';
			case '7' : return '财付通';
			case '8' : return '财付通网银';
			case '9' : return 'Webgame积分';
			case 'JD': return '骏网直充';
			case 'SDO': return '盛付通账户';
			case 'SDOB': return '盛付通银行/网关';
			default :
				return empty($type) ? $def : $type;
		}
	}

	//使用pass9的密钥获取md5签名
	function get_md5_sign($site_id, $fields) {
		ksort($fields);
		reset($fields);
		$plaintext = '';
		while(list($key, $val) = each($fields)) {
			$plaintext .= $key."=".$val."&";
		}
		$key = $this->CI->passport->get_md5_key($site_id);
		if($key === false) {
			$this->CI->error->set_error('16014');
			return false;
		}
		$plaintext .= 'key='.$key;
		log_scribe('trace', 'sign', $this->CI->input->ip_address()."  url:".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].",  site_id={$site_id},  key={$key},  md5_string: {$plaintext},  pass9_sign=".md5($plaintext));
		return md5($plaintext);
	}

	/**
	 * 传入一个数组,按照第三方接口方式对其进行md5后返回收银台的地址
	 */
	function get_cashier_url($site_id, $fields) {
		$url  =   site_url('payment/cashier');
		$sign =   $this->get_md5_sign($site_id, $fields);
		$qstr =   http_build_query($fields);
		return $url."?{$qstr}&sign={$sign}";
	}

	/**
	 * 周边站点登录时,返回周边站点的加密方法
	 */
	function login_notify_sign($plaintext, $site_id = '0000') {
		$query_str = 'result=1&timestamp='.Nodesvc::get_svcnode_ts().'&'.$plaintext;
		$ret = Nodesvc::get_svcnode_sign($query_str, $site_id);
		if($this->CI->error->error()) {
			return false;
		}
		return $ret['data'];
	}

	/**
	 * 周边站点登录时,需要返回的用户信息
	 */
	function login_notify_info($site_id, $uuid) {
		$result = array();
		$infoes_mapping = array(
				//实际输出键 => 底层数据库键
				'uuid'     => 'uuid',
				'nick'     => 'nickname',
				'email'    => 'email',
				'certtype' => 'certid_status',

				'mobile'   => 'mobile',
				'name'     => 'realname',
				'gender'   => 'gender',
				'birthday' => 'birthday',

				'login'           => 'loginname',
				'active'          => 'gameStatus',
				'first_active_ts' =>'active_ts',
				'cais'            => 'cert_id',

				'phone'   => 'telephone',
				'zip'     => 'zipcode',
				'address' => 'address',
				'nation'  => 'nation',

				'province'   => 'province',
				'occupation' => 'job',
				'business'   => 'industry',
				'income'     => 'income',

				'education' => 'education',
				'netplace'  => 'netplace',
				'interest'  => 'interest',
				'marriage'  => 'married',

				'certid' => 'cert_id',
		);
		$site_cfg = $this->CI->passport->get_member_site_cfg($site_id);
		if($site_cfg === false) {
			return false;
		}
		$site_cd     = $site_cfg['site_cd'];
		$trust_level = $site_cfg['trust_level'];

		$ret = Membersvc::findInfoByUuid($this->CI->tuxedo->su(Svc::SVCID_FIND_INFO_BY_UUID, $uuid));
		if($this->CI->error->error()) {
			return false;
		}
		$user_info = $ret['data'];

		$ret = Membersvc::findStatusByUuid($this->CI->tuxedo->su(Svc::SVCID_FIND_INFO_BY_UUID, $uuid));
		if($this->CI->error->error()) {
			return false;
		}
		$user_status = $ret['data'];

		$user_info = array_merge($user_info, $user_status);

		$trust_level = base_convert($trust_level, 16, 2);
		$arr_trust   = str_split($trust_level);

		$ret = Activesvc::getUserActiveGameStatus($this->CI->tuxedo->su(Svc::SVCID_ACTIVE_INFO, $uuid), array('site_cd' => $site_cd));
		if($this->CI->error->error()) {
			return false;
		}
		$user_info = array_merge($user_info, $ret['data']);

		$rtn = array(); $i = 0;
		foreach($infoes_mapping as $rtn_index => $info_index) {
			if($arr_trust[$i] != 0) {
				$rtn[$rtn_index] = $user_info[$info_index];
			}
			$i++;
		}
		$func = create_function('$v', '$v = trim($v);return ($v === "0") || !empty($v);');
		$rtn  = array_filter($rtn, $func);

		//有填身份证且未满18岁,列入防沉迷(1=防沉迷,0=无)
		if($arr_trust[11] == '1') {
			if(empty($rtn['cais']) || $rtn['certtype'] == 0) {
				$rtn['cais'] = 1;
			} else if($rtn['certtype'] >= 1 && $cert_date = $this->get_cert_id_birth($rtn['cais'])) {
				//检查防沉迷
				if(($cert_date['year'].$cert_date['month'].$cert_date['day']) > date ("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y") - 18))) {
					$rtn['cais'] = 1;
				} else {
					$rtn['cais'] = 0;
				}
			} else {
				$rtn['cais'] = 1;
			}
		} else {
			unset($rtn['cais']);
		}

		$result['data'] = $rtn;

		//是否需要补填nickname
		if($arr_trust[1] && (empty($user_info['nickname']) || ($user_info['nickname'] == '0') || (trim($user_info['nickname']) == ''))) {
			$result['need_nickname'] = true;
		}
		return $result;
	}

	/**
	 * 查询充值各类信息(用于充值成功后的综合信息显示)
	 */
	function charge_id_to_info($charge_id) {
		$input = array(
			'service_id'   => '',
			'from_site_id' => '',
			'bind_id'      => $charge_id,
		);
		$ret = Paysvc::queryConsume($this->CI->tuxedo->su(Svc::SVCID_WALLET_QUERY_CIBSOI), $input);
		if($this->CI->error->error()) {
			return false;
		}
		$site_id        = $ret['data']['from_site_id'];
		$service_id     = $ret['data']['service_id'];
		$sp_order_id    = $ret['data']['sp_order_id'];
		$price          = $ret['data']['price'];
		$site_cd        = $ret['data']['siteCd'];
		$part_id        = $ret['data']['partitionId'];
		$group_id       = $ret['data']['serverId'];
		$from_loginname = $ret['data']['fromLoginname'];
		$to_uuid        = $ret['data']['toUuid'];

		$ret = Membersvc::findInfoByUuid($this->CI->tuxedo->su(Svc::SVCID_FIND_INFO_BY_UUID, $to_uuid));
		if($this->CI->error->error()) {
			return false;
		}
		$to_loginname = $ret['data']['loginname'];

		$ret = Paysvc::getChargeType(array('bind_id' => $charge_id));
		if($this->CI->error->error()) {
			return false;
		}
		$charge_type = $ret['data']['chargetype'];

		$input = array(
			'charge_id'   => $charge_id,
		);
		$ret = Paysvc::VipJfGetByChargeId($this->CI->tuxedo->su(Svc::SVCID_JF_GET, $to_uuid), $input);
		$reward = 0;
		if(!$this->CI->error->error()) {
			$reward = $ret['data']['jf_willget'];
		}

		$ret = Paysvc::VipJfQuery($this->CI->tuxedo->su(Svc::SVCID_JF_INFO, $to_uuid));
		if($this->CI->error->error()) {
			return false;
		}
		$user_total_reward = $ret['data']['avaliable_jf'];

		return array(
			'from_loginname' => $from_loginname,
			'to_loginname'   => $to_loginname,
			'site_cd'  => $site_cd,
			'part_id'  => $part_id,
			'group_id' => $group_id,
			'price'    => $price,
			'reward'   => $reward,
			'user_total_reward' => $user_total_reward,
		);
	}

	/**
	 * 查询用户信息状态
	 */
	function get_user_status($uuid, $field) {
		$ret = Membersvc::findStatusByUuid($this->CI->tuxedo->su(Svc::SVCID_FIND_STATUS_BY_UUID, $uuid));
		if($this->CI->error->error()) {
			return false;
		}

		switch($field) {
			case 'pwd':
				return $ret['data']['pwd_status'];
				break;
			case 'email':
				return $ret['data']['email_status'];
				break;
			case 'mobile':
				return $ret['data']['mobile_status'];
				break;
			case 'email':
				return $ret['data']['email_status'];
				break;
			case 'cert':
				return $ret['data']['certid_status'];
				break;
			case 'nickname':
				return $ret['data']['nickname_status'];
				break;
			case 'question':
				return $ret['data']['safeques_status'];
				break;
			case 'sekey':
				return $ret['data']['sekey_status'];
				break;
			case 'matrix':
				return $ret['data']['matrix_status'];
				break;
			case 'token':
				return $ret['data']['token_status'];
				break;
			case 'paypwd':
				return $ret['data']['paymentpwd_status'];
				break;
			default:
				$this->CI->error->set_error('10144');
				return false;
				break;
		}
	}

	/**
	 * 登录状态下,访问pass9所跳转的首页
	 */
	function main_url($status = false, $redirect = false) {
		if(empty($status)){
			$ret = $this->get_info(array('status'));
			$status = $ret['status'];
		}
		$redirect_url = in_array($status, $this->CI->passport->get_wash_status())?site_url('wash'):site_url('active');
		if($redirect) {
			redirect($redirect_url, 'refresh');
		} else {
			return $redirect_url;
		}
	}

	/**
	 * 检查url是否为pass9可信任地址
	 */
	function is_trust_url($url) {
		$url_list = array(
			'the9.com',
			'17dou.com',
			'freerealms.com.cn',
			'red5studios.com.cn',
			'the9dev.com',
			'17doudou.com',
			'atolchina.com',
			'muxchina.com',
			'red5studios.com.sg',
			'the9edu.com',
			'17qiu.com',
			'ro2china.cn',
			'the9img.com',
			'pass9.com',
			'service.joyxy.com',
			'eafifaonline2.com',
			'huopuyun.com',
			'pass9.net',
			'epass.9ctime.com',
			'wofchina.com',
			'9c.com',
			'joyxy.com',
			'planetside2.com.cn',
			'service.wowchina.com',
			'9ctime.com',
			'firefallcn.com',
			'wowchina.com',
			'9ctime.net',
			'firefall.com.cn',
			'9iplaytv.com',
			'fmonline.net',
			'red5china.com',
			'9zwar.com',
			'red5singapore.com',
			'the9.cn',
			'fohchina.com',
			'mingchina.com.cn',
			'red5studios.cn',
		);
		$domain = parse_url($url, PHP_URL_HOST);
		if(!$domain) {
			return false;
		}

		foreach($url_list as $url_regx) {
			$pattern = '/^.*'.$url_regx.'$/';
			if(preg_match($pattern, $domain)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * wap版手机sdk签名认证
	 */
	function check_wap_sign($app_id, $plaintext, $timestamp, $sign) {
		$app_info = $this->CI->passport->get_app_info($app_id);
		if(!$app_info) {
			$this->CI->error->set_error('10108');
			return false;
		}
		$app_client_key = $app_info['client_key'];

		//echo $plaintext.$app_client_key;exit;
		//echo md5($plaintext.$app_client_key).'|'.$sign;exit;
		if(md5($plaintext.$app_client_key) != $sign
		&& ENVIRONMENT != 'development') {
			$this->CI->error->set_error('10104');
			return false;
		} else {
			$d = (time() - $timestamp);
			if(($d > 300 || $d < 0)
			&& ENVIRONMENT != 'development') {
				$this->CI->error->set_error('10105');
				return false;
			}
			return true;
		}
	}

	/**
	 * wap版手机sdk签名生成
	 */
	function gen_wap_sign($type, $app_id, $plaintext) {
		$app_info = $this->CI->passport->get_app_info($app_id);
		if(!$app_info) {
			$this->CI->error->set_error('10108');
			return false;
		}
		$app_client_key = $app_info['client_key'];

		$vendor_info = $this->CI->passport->get_app_vendor($app_info['vendor_id']);
		if(!$vendor_info) {
			$this->CI->error->set_error('10113');
			return false;
		}
		$app_server_key = $vendor_info['server_key'];

		$type = strtoupper($type);
		switch($type) {
			case 'SERVER':
				//echo $plaintext.$app_server_key;exit;
				return md5($plaintext.$app_server_key);
				break;
			default:
				//echo $plaintext.$app_client_key;exit;
				return md5($plaintext.$app_client_key);
				break;
		}
	}

	/**
	 * 不可逆混淆加密文字,如日志中的密码信息
	 */
	function mosaic($text) {
		$key = $this->CI->config->item('encryption_key');
		return md5($text.$key);
	}

	/**
	 * 检查访问ip是否被禁止访问(白名单优先于黑名单)
	 */
	function is_banned_ip($ip) {
		if(!$this->is_whitelist_ip($ip) && $this->is_blacklist_ip($ip)) {
			return true;
		}
		return false;
	}

	/**
	 * 检查是否是黑名单ip地址
	 */
	function is_blacklist_ip($ip) {
		$prefix = 'login_ip_';
		$this->CI->load->driver('cache');
		$ret = $this->CI->cache->memcached->get($prefix.$ip);
		if(!empty($ret)) {
			return true;
		}
		return false;
	}

	/**
	 * 检查是否是白名单ip地址
	 */
	function is_whitelist_ip($ip) {
		$prefix = 'white_ip_';
		$this->CI->load->driver('cache');
		$ret = $this->CI->cache->memcached->get($prefix.$ip);
		if(!empty($ret)) {
			return true;
		}
		return false;
	}

	/**
	 * 检查是否是可疑ip地址
	 */
	function is_suspicious_ip($ip) {
		$prefix = 'suspicious_ip_';
		$this->CI->load->driver('cache');
		$ret = $this->CI->cache->memcached->get($prefix.$ip);
		if(!empty($ret)) {
			return true;
		}
		return false;
	}

	/**
	 * stringLen
	 *
	 * @param string $str
	 * @param integer $type 1 字母与汉字做为1个字符, 2 汉字做为两个字符
	 * @return integer
	 */
	static public function stringLen($str, $type = 2)
	{
		$len = mb_strlen($str, 'UTF-8');
		if ($type == 2)
		{
			for ($i = 0; $i < $len; $i++)
			{
				$char = mb_substr($str, $i, 1, 'UTF-8');
				if (ord($char) > 128)
				{
					$len++;
				}
			}
		}
		return $len;
	}


	function is_forbid_string( $str = '', $forbid_arr = array('yy.the9', 'sina.the9', 'qq.the9', 'tqq.the9'), $delimiter='@')
	{
		if (empty($str)) return false;

		$arr = explode($delimiter, $str);
		if ( in_array($arr[1], $forbid_arr ) ) return true;

		return false;
	}

	function shorten_realname($realname)
	{
		$res      = '';
		$realname = trim($realname);
		$len      = mb_strlen($realname, 'UTF-8');

		for($i = 0; $i<$len; $i++){
			if($i == 0) {
				$res .= mb_substr($realname, 0, 1, 'UTF-8');
				continue;
			}
			$res .= '*';
		}
		return $res;
	}

    function get_signature_string($params)
    {
        if (!is_array($params)) return  md5($params);
        $string = '';
        foreach ($params as  $param)
        {
            $string .=  $param;
        }
        return  md5($string);
    }

	//获得用户状态
	function get_status($fields = array(), $uuid = null) {
		if($uuid === null) {
			$uuid = element('uuid', $this->CI->session->userdata('user'));
		}
		$ret = Membersvc::findStatusByUuid($this->CI->tuxedo->su(Svc::SVCID_FIND_STATUS_BY_UUID, $uuid));
		if($this->CI->error->error()) {
			return false;
		}

		if(empty($field))return $ret['data'];

		$res = array();
		foreach($fields as $key){
			$res[$key] = $ret['data'][$key];
		}
		return $res;
	}

	//获得用户信息
	function get_info($fields = array(), $uuid = null) {
		if($uuid === null) {
			$uuid = element('uuid', $this->CI->session->userdata('user'));
		}
		$ret = Membersvc::findInfoByUuid($this->CI->tuxedo->su(Svc::SVCID_FIND_INFO_BY_UUID, $uuid));
		if($this->CI->error->error()) {
			return false;
		}

		if(empty($fields))return $ret['data'];

		$res = array();
		foreach($fields as $key){
			$res[$key] = $ret['data'][$key];
		}
		return $res;
	}

	//邮箱认证提交
	function email_verify_submit($uuid, $loginname, $email, $return_url){
		if(!$this->chk_email($email)){
			 return false;
		}
		//认证邮箱先用新邮箱地址进行补填操作
		$input  = array(
			'email' => $email,
			'email_status' => EMAIL_STATUS_FILLED
		);
		$ret = Membersvc::modifyEmailAndStatus($this->CI->tuxedo->su(Svc::SVCID_MOD_EMAIL_DIRECT, $uuid), $input);
		if($this->CI->error->error()){
			return false;
		}

		//修改邮箱状态为认证中,并发送认证邮件
		$input = array(
			'email' => $email,
			'email_status' => EMAIL_STATUS_VERIFYING
		);
		$ret = Membersvc::modifyEmailAndStatus($this->CI->tuxedo->su(Svc::SVCID_MOD_EMAIL_DIRECT, $uuid), $input);
		if($this->CI->error->error()){
			return false;
		}
		$email_code = $this->get_email_code($email);
		if($email_code === false) {
			return false;
		}

		$sign = $this->get_md5_sign('0000', array('loginname' => $loginname, 'email_code' => $email_code));

		$data = array();
		$data['loginname'] = $loginname;
		$data['email']= $email;
		$data['link'] = site_url($return_url.'?loginname='.urlencode($loginname).'&email_code='.$email_code.'&sign='.$sign);
		$content      = $this->CI->load->view('subscribe/email_entry', $data, true);
		$this->send_email($email, $this->CI->passport->get('email'), '邮箱认证', $content);
		if($this->CI->error->error()) {
			return false;
		}

		return true;
	}

	//邮箱认证
	function email_verify_process($loginname, $email_code, $redirect_url = 'email') {
		if(!$this->chk_loginname($loginname)) {
			$this->CI->error->show_error();
		}
		$input = array(
			'loginname' => $loginname,
		);
		$ret = Membersvc::getUuidByName($this->CI->tuxedo->su(Svc::SVCID_NAME2UUID), $input);
		if($this->CI->error->error()){
			$this->CI->error->show_error();
		}
		$uuid = $ret['data']['uuid'];

		$ret  = Membersvc::findInfoByUuid($this->CI->tuxedo->su(Svc::SVCID_FIND_INFO_BY_UUID, $uuid));
		if($this->CI->error->error()) {
			$this->CI->error->show_error();
		}
		$email = $ret['data']['email'];

		if(!$this->verify_email_code($email, $email_code, true)) {
			redirect(site_url($redirect_url), 'refresh');
		}
		$input = array(
			'email'        => $email,
			'email_status' => EMAIL_STATUS_VERIFIED,
		);
		$ssoilu = $this->CI->tuxedo->su(Svc::SVCID_MOD_EMAIL_DIRECT, $uuid);
		$ret    = Membersvc::modifyEmailAndStatus($ssoilu, $input);
		if($this->CI->error->error()){
			$this->CI->error->show_error();
		}

		//发送邮箱认证成功消息
		$this->send_msg($uuid, 'secure', 'tip', 'email_verify', date('Y'), date('m'), date('d'));
		return true;
	}

	/**
	 * 检查是否是可疑账号
	 */
	function is_suspend_account() {
		$res = $this->get_info(array('status'));
		if($res['status'] == ACCOUNT_STATUS_SUSPENDED){
			return true;
		}
		return false;
	}

	/**
	 * 检查是否是需要洗白的账号
	 */
	function is_wash_account($uuid = null, $status = null) {
		if(empty($status)){
			$res = $this->get_info(array('status'), $uuid);
			$status = $res['status'];
		}
		$wash_array = $this->CI->passport->get_wash_status();
		if(in_array($status, $wash_array)){
			return true;
		}
		return $status;
	}

	/**
	 * 消耗邀请码
	 */
	function consume_invite_code($site_cd, $params) {
		switch($site_cd){
			case 'PS2':
				$url = $this->CI->passport->get('ws_ps2_active');
				$ret = $this->webservice($url, $params, 'ConsumeCode');

				// 调用webservice失败
				if(empty($ret)) {
					return array(
						//'error_code' => '10195',
						'error_msg'  => $this->CI->error->error_msg('10195'),
					);
				}

				// 消耗成功
				if($ret['errCode'] == '0'){
					return true;
				}

				// 消耗失败
				$current_url = current_url();
				$server_ip = $_SERVER["SERVER_ADDR"];
				log_scribe('trace', 'webservice', "[{$current_url}] consume_invite_code: pass9ip:{$server_ip} param = ".var_export($params, true)."   return = ".var_export($ret, true));
				return array(
					//'error_code' => $ret['errCode'],
					'error_msg'  => $ret['errMsg'],
				);
				break;
			default:
				return false;
		}
	}

	/**
	 * 生成倒计时数字
	 */
	function generate_count_down($base, $start_ts, $end_ts, $special, $current_time = ''){
		empty($current_time) && $current_time = date('YmdHis');

		$special_speed = $this->get_count_down_speed($base, $start_ts, $end_ts, $special);
		$special_hours = $this->get_count_down_hours($base, $start_ts, $current_time, $special);
        //var_dump($special_speed);var_dump($special_hours);

		if($current_time <= $start_ts){
			$count_down_number = $base;
		}else if($current_time >= $end_ts){
			$count_down_number = 0;
		}else{
			$count_down_number = $base;
			foreach($special_speed as $rate => $speed){
				$count_down_number -= $speed * $special_hours[$rate];
			}
		}

		return array('date' => $current_time, 'number' => $count_down_number);
	}

	/**
	 * 获取倒计时速率
	 */
	function get_count_down_speed($base, $start_ts, $end_ts, $special,  $current_speed = false){
		$special_day   = $this->get_count_down_hours($base, $start_ts, $end_ts, $special);
		$special_speed = array();

		foreach($special_day as $rate => $hours){
			$total_hours += $hours * $rate; //计算总时间(一倍速率)
		}
		$speed = $base/$total_hours;

		foreach($special_day as $rate => $hours){
			$special_speed[$rate] = $speed * $rate;
		}
		//var_dump($special_speed);
		if(!empty($current_speed)){
			$current_hour = date('H');
			foreach($special as $val){
				if($current_hour >= $val['start'] && $current_hour < $val['end']){
					return $special_speed[$val['rate']];
				}
			}
		}

		return $special_speed;
	}

	/**
	 * 获取倒计时时间段
	 */
	private function get_count_down_hours($base, $start_ts, $end_ts, $special_cfg){
		$firstday_hour = substr($start_ts, -6, 2)+ substr($start_ts, -4, 2)/60 + substr($start_ts, -2, 2)/3600;
		$lastday_hour  = substr($end_ts, -6, 2)+ substr($end_ts, -4, 2)/60 + substr($end_ts, -2, 2)/3600;
		//var_dump($end_ts);
		foreach($special_cfg as $special){
			$start = $special['start'];
			$end   = $special['end'];
			$rate  = $special['rate'];

			$day = substr($end_ts, 0, 8) - substr($start_ts, 0, 8);
			//var_dump(substr($end_ts, 0, 8));var_dump(substr($start_ts, 0, 8));

			if($start > $end){
				if($day > 0){
					// 检查第一天
					if($firstday_hour <= $end){
						$special_day[$rate] += abs(($end - $firstday_hour) + (24 - $start));
					}else if($end < $firstday_hour && $firstday_hour < $start){
						$special_day[$rate] += abs((24 - $start));
					}else{
						$special_day[$rate] += abs(24 - $firstday_hour);
					}

					$day >= 1 && $day--;
					$special_day[$rate] += abs($day * ((24 - $start) + ($end - 0)));
					$special_day[$rate] += abs($day * ($start - $end));

					// 检查最后一天
					if($lastday_hour <= $end){
						$special_day[$rate] += abs($lastday_hour - 0);
					}else if($end < $lastday_hour && $h2 < $start){
						$special_day[$rate] += abs($end - 0);
					}else{
						$special_day[$rate] += abs(($end - 0) + ($h2 - $start));
					}
				}else{
					// 开始和结束时间是同一天
					if($firstday_hour <= $end){
						if($lastday_hour <= $end){
							$special_day[$rate] += abs($lastday_hour - $firstday_hour);
						}else if($lastday_hour > $end && $lastday_hour < $start){
							$special_day[$rate] += abs($end - $firstday_hour);
						}else{
							$special_day[$rate] += abs(($end - $firstday_hour) + ($lastday_hour - $start));
						}
					}else if($end < $firstday_hour && $firstday_hour < $start){
						if($lastday_hour > $end && $lastday_hour < $start){
							$special_day[$rate] += 0;
						}else if($lastday_hour > $start){
							$special_day[$rate] += abs($lastday_hour - $start);
						}
					}else{
						$special_day[$rate] += abs($lastday_hour - $firstday_hour);
					}
				}
			}else{
				if($day > 0){
					// 检查第一天
					if($firstday_hour <= $start){
						$special_day[$rate] += abs($end - $start);
					}else if($start < $firstday_hour && $firstday_hour < $end){
						$special_day[$rate] += abs($end - $firstday_hour);
					}else{
						$special_day[$rate] += 0;
					}
					//var_dump($special_day[$rate]);
					$day >= 1 && $day--;
					$special_day[$rate] += abs($day * ($end -$start));

					// 检查最后一天
					if($lastday_hour <= $start){
						$special_day[$rate] += 0;
					}else if($start < $lastday_hour && $lastday_hour < $end){
						$special_day[$rate] += abs($lastday_hour - $start);
					}else{
						$special_day[$rate] += abs($start - $end);
					}
					//var_dump($end_ts); var_dump($firstday_hour);		var_dump($lastday_hour);var_dump($start);	var_dump($end);
				}else{
					// 开始和结束时间是同一天
					if($firstday_hour <= $start){
						if($lastday_hour <= $start){
							$special_day[$rate] += 0;
						}else if($lastday_hour > $start && $lastday_hour < $end){
							$special_day[$rate] += abs($lastday_hour - $start);
						}else{
							$special_day[$rate] += abs($start - $end);
						}
					}else if($start < $firstday_hour && $firstday_hour < $end){
						if($lastday_hour > $start && $lastday_hour < $end){
							$special_day[$rate] += abs($lastday_hour - $firstday_hour);
						}else if($lastday_hour > $start){
							$special_day[$rate] += abs($end - $firstday_hour);
						}
					}else{
						$special_day[$rate] += 0;
					}
					//var_dump($end_ts);  var_dump($firstday_hour);		var_dump($lastday_hour);var_dump($start);	var_dump($end);var_dump($special_day);
				}
			}
		}

		foreach($special_day as $rate => $time){
			$special_day[$rate] = $time * 3600;
		}

		return $special_day;
	}

	/**
	 * 生成游戏入口地址
	 */
	function gen_game_url($site_cd, $input) {
		switch($site_cd){
			case 'MURT':
				$fields = array(
								'userid'  => $input['uuid'],
								'time'    => time(),
								'cflag'   => (empty($input['cert_id']) || $this->is_cert_underage($input['cert_id']))?0:1,
								'groupid' => $input['group_id'],
								'seqid'   => $this->gen_rand_str('5', 'numeric'),
							);

				$sign           = $this->get_md5_sign($this->CI->passport->site_cd2id($site_cd), $fields);
				$fields['cdk']  = $input['cdkey'];

				while(list($key, $val) = each($fields)) {
					$qstr .= $key."=".$val."&";
				}
				$qstr .= "sig={$sign}";
				$url   = $this->CI->passport->get_server_address($site_cd, $input['part_id'], $input['group_id']);

				return $url."?{$qstr}";
				break;
			default:
				return false;
		}
	}
}
