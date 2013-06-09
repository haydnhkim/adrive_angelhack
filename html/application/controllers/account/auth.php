<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Auth extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();

			// $this->load->library('validator');
			
			$this->load->model('api');
			$this->load->model('account');
		}
		


		
		/**
		 * 사용자인증 API
		 * 
		 * @URL. /account/auth
		 * @Auth. Consumer Authentication
		 * @Method. POST
		 * @Return. JSON Encoded Plain Text
		 */
		function index()
		{
			$this->api->validate('consumer');
			
			$username = $this->input->post('username'); // 이메일주소
			$password = $this->input->post('password'); // 비밀번호
			
			// 개발 테스트용
			if (isset($_REQUEST['_dev']) == true)
			{
				$username = $this->input->get_post('username');
				$password = $this->input->get_post('password');
			}
			
			// 유저네임 주소 입력 확인
			if (!$username)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'username', 'The username field has not specified.');
			}
			// 비밀번호 입력 확인
			else if (!$password)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'password', 'The password field has not specified.');
			}
			// 유저네임 주소 등록 확인
			else if ($this->account->isExistsUsername($username) == false)
			{
				$this->api->failed(CODE_RESOURCE_NOTFOUND, 'username', 'The specified username not found.');
			}
			else
			{
				// 유저네임과 비밀번호로 계정 확인
				$found = $this->account->findOneByUsernameAndPassword($username, $password);

				if (isset($found['_id']) == true)
				{
					$this->load->library('auth_user');
					// 토큰 획득
					$token = $this->auth_user->grantToken($found['_id'], $this->api->authorized_key());

					if (isset($token['ticket']) == true)
					{
						$output = array(
							'user' => $this->account->outputized($found)
						);
						// $output = $found;
						$output['user']['ticket'] = $token['ticket'];
						$output['user']['signdate'] = $token['signdate'];
						$this->account->update($found['_id'] ,'last_login' , time());

						$this->api->success($output);
					}
					else
					{
						$this->api->failed(CODE_ERROR_DATABASE, null, 'Failed to issue a ticket.');
					}
				}
				else
				{
					$found = $this->account->findOneByEmail($username);
					
					if (isset($found['_id']) == true)
					{
						if(isset($found['password']) == false)
						{
							$this->api->failed(CODE_DENIED, $found['via'], 'The specified username is not an account of lookntag.');
						}
					}
					
					$this->api->failed(CODE_FAILED, null, 'Failed to authorization.');
				}
			}
		}
	}
?>