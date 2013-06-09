<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Validate
	{
		public $authorized_key = null;
		public $authorized_secret = null;
		public $authorized_user_id = null;
		
		function __construct()
		{

			$this->CI =& get_instance();

			$this->CI->load->library('Mongo_db');
			
			$this->CI->load->library('Auth_consumer');
			$this->CI->load->library('Auth_user');
		}
		
		
		function install()
		{
			$this->CI->mongo_db->add_index('auth_nonce', array('key' => 1, 'nonce' => 1), array('name' => 'Indexes for NONCE Validate', 'unique' => TRUE));
			
			$this->CI->auth_consumer->install();
			$this->CI->auth_user->install();
		}
		
		
		function getNonceByKeyWithNonce($key, $nonce)
		{
			return $this->CI->mongo_db->findOne('auth_nonce', array('key' => $key, 'nonce' => $nonce));
		}
		
		
		function isExistsNonceByKeyWithNonce($key, $nonce)
		{
			$nonce = $this->getNonceByKeyWithNonce($key, $nonce);
			
			return isset($nonce['_id']);
		}
		
		
		function nonce($key, $nonce)
		{
			if ($this->isExistsNonceByKeyWithNonce($key, $nonce) == TRUE)
			{
				return FALSE;
			}
			else
			{
				$result = @$this->CI->mongo_db->insert(
					'auth_nonce',
					array(
						'key' => $key,
						'nonce' => $nonce,
						'remote_addr' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null),
						'http_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
						'request_uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
						'params_post' => isset($_POST) ? $_POST : null,
						'params_get' => isset($_GET) ? $_GET : null,
						'signdate' => time()
					)
				);
				
				if (isset($result['_id']) == TRUE)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
		
		
		function consumer($key, $nonce, $timestamp, $signature)
		{
			/* Validating timestamp */
			if ($timestamp > time() + 300 || $timestamp < time() - 300)
			{
				return 'timestamp';
			}
			
			$consumer = $this->CI->auth_consumer->getConsumerByKey($key);
			// print_r($consumer);
			/* Validating consumer key */
			if (isset($consumer['_id']) == FALSE)
			{
				return 'key';
			}
			/* Validating nonce */
			else if ($this->nonce($key, $nonce) == FALSE)
			{
				return 'nonce';
			}
			
			// $this->library->load('variable');
			$signature_valid = $this->_encrypted($nonce . '&' . $timestamp, $consumer['secret']);
			
			/* Validating signature */
			if ($signature_valid != $signature)
			{
				return 'signature';
			}
			
			$this->authorized_key = $key;
			$this->authorized_secret = $consumer['secret'];
			$this->authorized_user_id = false;
			
			return true;
		}
		
		
		function user($token, $nonce, $timestamp, $signature)
		{
			/* Validating timestamp */
			if ($timestamp > time() + 300 || $timestamp < time() - 300)
			{
				return 'timestamp';
			}
			
			// $this->model->load('dp/auth/user');
			$user = $this->CI->auth_user->getTokenByToken($token);
			
			/* Validating consumer key */
			if (isset($user['_id']) == false || isset($user['_id_user']) == false)
			{
				return 'token';
			}
			/* Validating nonce */
			else if ($this->nonce($user['key'], $nonce) == false)
			{
				return 'nonce';
			}
			
			// $this->library->load('variable');
			$signature_valid = $this->_encrypted($nonce . '&' . $timestamp, $user['secret']);
			
			/* Validating signature */
			if ($signature_valid != $signature)
			{
				return 'signature';
			}
			
			$this->authorized_user_id = $user['_id_user']->{'$id'};
			
			return true;
		}

		function _encrypted($value, $key)
		{
			return hash_hmac('SHA1', $value, $key);
		}
	}
?>