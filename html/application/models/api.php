<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Api extends CI_Model
	{
		/**
		 * 클래스 생성 시 호출
		 * 
		 * @Params none
		 * @Return void
		 */
		public function __construct()
		{
			$this->load->library('mongo_db');
			
			$this->load->library('Validate');
			$this->load->library('Errorcode');
			// $this->load->model('Constants');
			
			$this->connect();
		}
		
		
		/**
		 * DB 접속
		 * 
		 * @Params none
		 * @Return void
		 */
		function connect()
		{
			$this->install();
		}
		
		
		/**
		 * DB 초기화 작업
		 * 
		 * @Params none
		 * @Return void
		 */
		function install()
		{
			$this->validate->install();
		}
		
		
		/**
		 * 현재 API 인증된 사용자 Object Id 반환
		 * 
		 * @Params None
		 * @Return (String) 사용자 Object Id 반환
		 */
		function authorized_user_id()
		{
			return $this->validate->authorized_user_id;
		}
		
		
		/**
		 * 현재 API 인증된 Consumer Key 반환
		 * 
		 * @Params None
		 * @Return (String) Consumer Key 반환
		 */
		function authorized_key()
		{
			return $this->validate->authorized_key;
		}
		
		
		/**
		 * 현재 API 인증된 Consumer Secret 반환
		 * 
		 * @Params None
		 * @Return (String) Consumer Secret 반환
		 */
		function authorized_secret()
		{
			return $this->validate->authorized_secret;
		}
		
		
		/**
		 * Consumer 등록
		 * 
		 * @Params (String) Conusmer 이름
		 * @Return void
		 */
		function register_consumer($consumer_name)
		{
			$this->consumer->register($consumer_name);
		}
		
		
		/**
		 * API 인증
		 * 
		 * @Params (string) 인증형태 (any : 무관, consumer : Consumer, user : User)
		 * @Params (Boolean) 오류메세지 출력 여부
		 * 
		 * @Return void
		 */
		function validate($type = null, $silence = false)
		{
			if ($type == 'any')
			{
				if ($this->input->get_post('_token'))
				{
					$type = 'user';
				}
				else
				{
					$type = 'consumer';
				}
			}
			
			if ($type == 'consumer')
			{
				$key = $this->input->get_post('_key');
				$secret = null;
				$nonce = $this->input->get_post('_nonce');
				$timestamp = $this->input->get_post('_timestamp');
				$signature = $this->input->get_post('_signature');
				
				if (isset($_REQUEST['_dev']) == true)
				{
					$key = '1347455670_d9c5d71a7234c1ae73ddab296dd12d88e77fc68b';
					$secret = '1347455670_7f6ce95165d4ddd4cc1455b5c6c4f9b21d161f2f';
					$nonce = uniqid();
					$timestamp = time();
					$signature = _encrypted($nonce . '&' . $timestamp, $secret);
				}
				
				if (!$key || !$nonce || !$timestamp || !$signature)
				{
					$this->failed(CODE_REQUIRED_AUTHENTICATION, 'Required authentication.', $silence);
				}
				else if (($result = $this->validate->consumer($key, $nonce, $timestamp, $signature)) !== true)
				{
					if ($result == 'timestamp')
					{
						$this->failed($timestamp ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_timestamp', $result . ' Error.', $silence);
					}
					else if ($result == 'key')
					{
						$this->failed($key ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_key', $result . ' Error.', $silence);
					}
					else if ($result == 'nonce')
					{
						$this->failed($nonce ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_nonce', $result . ' Error.', $silence);
					}
					else if ($result == 'signature')
					{
						$this->failed($signature ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_signature', $result . ' Error.', $silence);
					}
					else
					{
						$this->failed(CODE_AUTH_FAILED, null, $result . ' Error.', $silence);
					}
				}
			}
			else if ($type == 'user')
			{
				$token = $this->input->get_post('_token');
				$nonce = $this->input->get_post('_nonce');
				$timestamp = $this->input->get_post('_timestamp');
				$signature = $this->input->get_post('_signature');
				
				if (isset($_REQUEST['_dev']) == true)
				{
					$token = '1351745669_67a0f9313dda4942952bdd7afff721dcb8ff952a'; // FB YYP
					$secret = '1347455670_7f6ce95165d4ddd4cc1455b5c6c4f9b21d161f2f';
					$nonce = uniqid();
					$timestamp = time();
					$signature = _encrypted($nonce . '&' . $timestamp, $secret);
				}
				
				if (!$token || !$nonce || !$timestamp || !$signature)
				{
					$this->failed(CODE_REQUIRED_AUTHENTICATION, 'Required authentication.', $silence);
				}
				else if (($result = $this->validate->user($token, $nonce, $timestamp, $signature)) !== true)
				{
					if ($result == 'timestamp')
					{
						$this->failed($timestamp ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_timestamp', $result . ' Error.', $silence);
					}
					else if ($result == 'token')
					{
						$this->failed($token ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_token', $result . ' Error.', $silence);
					}
					else if ($result == 'nonce')
					{
						$this->failed($nonce ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_nonce', $result . ' Error.', $silence);
					}
					else if ($result == 'signature')
					{
						$this->failed($signature ? CODE_VALUE_INVALID : CODE_VALUE_MISSING, '_signature', $result . ' Error.', $silence);
					}
					else
					{
						$this->failed(CODE_AUTH_FAILED, null, $result . ' Error.', $silence);
					}
				}
			}
			else
			{
				$this->failed(CODE_AUTH_FAILED, null, 'Invalid Type.', $silence);
			}
		}
		
		
		/**
		 * API 처리요청 성공
		 * 
		 * @Params (Array) 반환할 Dataset
		 * @Params (Boolean) HTML Print 여부 (false : Print, true : Exit)
		 * 
		 * @Return void, HTML Print and Exit
		 */
		function success($data = null, $silence = false, $redirect_uri = null)
		{
			$output = array('result' => true);
			
			if ($data) $output['data'] = $data;
			
			if (!$silence)
			{
				if ($redirect_uri)
				{
					header('Location:' . $redirect_uri . (strpos($redirect_uri, '?') === false ? '?' : '&') . 'response=' . json_encode($output));
				}
				else
				{
					echo json_encode($output);
				}
			}
			
			exit;
		}
		
		
		/**
		 * API 처리요청 실패
		 * 
		 * @Params (String) 오류코드
		 * @Params (String) 오류가 발생한 필드
		 * @Params (String) 오류메세지
		 * @Params (Boolean) HTML Print 여부 (false : Print, true : Exit)
		 * 
		 * @Return void, HTML Print and Exit
		 */
		function failed($code, $field, $message, $silence = false, $redirect_uri = null)
		{
			$output = array('result' => false);
			$error = array();
			
			if ($code) $error['code'] = $code;
			if ($field) $error['field'] = $field;
			if ($message) $error['message'] = $message;
			
			$output['error'] = $error;
			
			if (!$silence)
			{
				if ($redirect_uri)
				{
					header('Location:' . $redirect_uri . (strpos($redirect_uri, '?') === false ? '?' : '&') . 'response=' . json_encode($output));
				}
				else
				{
					echo json_encode($output);
				}
			}
			
			exit;
		}
	}
?>