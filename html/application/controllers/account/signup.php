<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Signup extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();

			// $this->load->library('validator');
			
			$this->load->model('api');
			$this->load->model('account');
		}
		
		
		/**
		 * 사용자등록 API
		 * 
		 * @URL. /account/signup
		 * @Auth. Consumer Authentication
		 * @Method. POST
		 * @Return. JSON Encoded Plain Text
		 */
		function index()
		{
			$this->api->validate('consumer');
			
			$username = trim($this->input->post('username')); // 사용자이름 (영문+숫자만 허용)
			$password = $this->input->post('password'); // 비밀번호
			
			// 개발테스트용
			if (isset($_REQUEST['_dev']))
			{
				$username = $this->input->get_post('username');
				$password = $this->input->get_post('password');
			}
			
			// 비밀번호 입력 확인
			
			// 사용자이름 입력 확인
			if (!$username)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'username', 'The username field has not specified');
			}
			// 사용자이름 등록 확인
			else if ($username && $this->account->isExistsUsername($username) == true)
			{
				$this->api->failed(CODE_VALUE_DUPLICATED, 'username', 'The specified username has registered already.');
			}
			// 사용자이름 길이 확인
			else if ($username && strlen($username) > 10)
			{
				$this->api->failed(CODE_VALUE_INVALID, 'username', 'The specified username is invalid.');
			}
			else if (!$password)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'password', 'The password field has not specified.');
			}
			else if (strlen($password) < 4)
			{
				$this->api->failed(CODE_VALUE_INVALID, 'password', 'The specified password is too short.');
			}
			// 유효성 확인 성공
			else
			{
				
				// 등록
				$result = $this->account->register($username, $password);
				
				// 등록성공
				if (isset($result['_id']) == true)
				{
					
					$user = array();
					
					$user['_id_user'] = $result['_id']->{'$id'};
					$user['username'] = $result['username'];
					
					// 토큰 획득
					$this->load->library('auth_user');
					$this->load->library('validate');
					$token = $this->auth_user->grantToken($result['_id'], $this->validate->authorized_key);
					
					if (isset($token['ticket']) == true)
					{
						$user['token'] = array();
						$user['token']['ticket'] = $token['ticket'];
						$user['token']['signdate'] = $token['signdate'];
						
						$this->account->update($result['_id'] ,'last_login' , time());
					}
					
					$output = array(
						'user' => $user
					);
					
					$this->api->success($output);
				}
				else
				{
					$this->api->failed(CODE_ERROR_DATABASE, null, 'Failed to register a user.');
				}
			}
		}
	}
?>