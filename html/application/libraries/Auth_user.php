<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Auth_user
	{
		public function __construct()
		{
			$this->CI =& get_instance();
			$this->CI->load->library('mongo_db');
		}
		
		
		function install()
		{
			$this->CI->mongo_db->add_index('auth_token', array('token' => 1), array('name' => 'Indexes for User Authentication', 'unique' => TRUE));
			$this->CI->mongo_db->add_index('auth_token', array('_id_user' => 1, 'key' => 1), array('name' => 'Indexes for Find a  Exists Token', 'unique' => TRUE));
			$this->CI->mongo_db->add_index('auth_token', array('key' => 1), array('name' => 'Indexes for Consumer Key'));
		}
		
		
		function getTokenByToken($token)
		{
			return $this->CI->mongo_db->findOne('auth_token', array('token' => $token));
		}
		
		
		function getTokenByIdWithKey($_id_user, $key)
		{
			return $this->CI->mongo_db->findOne('auth_token', array('_id_user' => $this->CI->mongo_db->mongoId($_id_user), 'key' => $key));
		}
		
		
		function isExistsTokenByIdWithKey($_id_user, $key)
		{
			$token = $this->getTokenByIdWithKey($_id_user, $key);
			
			return isset($token['_id']);
		}
		
		
		function grantToken($_id_user, $key)
		{
			$this->CI->load->library('auth_consumer');
			
			$consumer = $this->CI->auth_consumer->getConsumerByKey($key);
			$existsToken = $this->getTokenByIdWithKey($_id_user, $key);
			
			if (isset($consumer['_id']) == true)
			{
				if (isset($existsToken['_id']) == false)
				{
					
					$secret = $consumer['secret'];
					$signdate = time();
					$ticket = $signdate . '_' . $this->_encrypted($signdate . '_' . rand(10000, 99999) . '_' . $_id_user, 'USER_TICKET_VV2wGa4S4absF4gBbX7');
					
					return @$this->CI->mongo_db->insert(
						'auth_token',
						array(
							'_id_user' => $this->CI->mongo_db->mongoId($_id_user),
							'key' => $key,
							'secret' => $secret,
							'ticket' => $ticket,
							'token' => $signdate . '_' . $this->_encrypted($ticket, $secret),
							'signdate' => $signdate
						)
					);
				}
				else
				{
					return $existsToken;
				}
			}
			else
			{
				return false;
			}
		}

		function _encrypted($value, $key)
		{
			return hash_hmac('SHA1', $value, $key);
		}
	}
?>