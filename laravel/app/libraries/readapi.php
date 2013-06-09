<?php

//define consumer key,secret
define('c_key','1347455670_d9c5d71a7234c1ae73ddab296dd12d88e77fc68b');
define('c_secret','1347455670_7f6ce95165d4ddd4cc1455b5c6c4f9b21d161f2f');
// define('api_host','http://172.27.158.11/');
define('api_domain','http://54.251.110.67/');
define('api_host','http://54.251.110.67/');

class Api
{
	function __construct()
	{

	}

	function get_ckey(){
		return c_key;
	}

	function api_get($api, $parm = '', $isasset = FALSE, $auth = TRUE)
	{
		if(is_array($parm) || is_object($parm)) $parm = ($auth?'&':'?') . http_build_query($parm);

		$response = $this->api_call($api, $parm, FALSE, $isasset, $auth, $this->_generate_token());
		return $response;
	}

	function api_post($api, $parm = '', $isasset = FALSE, $auth = TRUE)
	{

		$response = $this->api_call($api, $parm, TRUE, $isasset, $auth, $this->_generate_token());
		return $response;
	}
	/**
	 * Api 호출
	 * 
	 * Param(api주소,파라미터,메써드,서버종류,인증유무,토큰)
	 */
	function api_call($api, $parm = '', $ispost, $isasset, $auth, $token = FALSE)
	{
		
		$timestamp = time();
		$nonce = uniqid(time() . '_');
		$signature = hash_hmac('SHA1', $nonce . '&' . $timestamp, c_secret);

		$ch = curl_init();

		//Choose Server(api,asset)
		$url = $isasset ? asset_host : api_host;
		$url .= $this->_c_url($api);

		if($auth){
			$url .= '?_timestamp=' . $timestamp . '&_nonce=' . $nonce . '&_signature=' . $signature . '&_token=' . $token . '&_key=' . c_key;
			// if($token){
			// 	$url .= '&_token=' . $token;
			// }else{
			// 	$url .= '&_key=' . c_key;
			// }
			if($ispost){
				//post

				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $parm);

			} else {	
				//get
				$url .= $parm;
			}
		}else{
			$url .= $parm;
		}

		// $url = http_build_query($url);
		
		//$response = file_get_contents($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);

		$response = curl_exec($ch);
		$errno = curl_errno($ch);
		$errmsg = curl_error($ch);
		curl_close($ch);
		
		if($errno > 0) die('curl Error -NO : ' . $errno . ' . -MSG . ' . $errmsg);

		$res = @json_decode($response);
		
		return empty($res) ? $this->response_err($response) : $res;
	}

	function redirect($api,$parm)
	{
		$url = api_domain;
		$timestamp = time();
		$nonce = uniqid(time() . '_');
		$signature = hash_hmac('SHA1', $nonce . '&' . $timestamp, c_secret);
		$url .= $api . '?_timestamp=' . $timestamp . '&_nonce=' . $nonce . '&_signature=' . $signature . '&_key=' . c_key . '&' . http_build_query($parm);

		redirect($url);
	}

	function response_err($response)
	{
		return (object)array('result'=>FALSE,'error'=>(object)array('code'=>'0009','field'=>'','message'=>$response));
	}

	function response_access_denied()
	{
		return (object)array('result'=>FALSE,'error'=>(object)array('code'=>'0009','field'=>'','message'=>'Access Denied.'));
	}

	private function _c_url($url)
	{
		if(substr($url, 0,1)=="/"){
			$url = substr($url,1);
		};
		if(substr($url,strlen($url)-1,1)!="/"){
			$url = $url . "/";
		}
		return $url;
	}

	private function _generate_token(){
		// $this->ci =& get_instance();
		// $this->ci->load->library('session');

		// $signdate = $this->ci->session->userdata('signdate');
		// $ticket = $this->ci->session->userdata('ticket');

		// if($signdate and $ticket){
		// 	return $signdate . '_' . hash_hmac('SHA1', $ticket, c_secret);	
		// }	

		return FALSE;
	}
}

/* End of file api.php */
/* Location: ./application/library/api.php */