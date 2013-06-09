<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Auth_consumer
	{
		public function __construct()
		{
			$this->CI =& get_instance();
			$this->CI->load->library('Mongo_db');

			// $this->CI-load->library('mongo_db');
		}
		
		
		function install()
		{
			$this->CI->mongo_db->add_index('auth_consumer', array('name' => 1), array('name' => 'Indexes for Consumer Name', 'unique' => TRUE));
			$this->CI->mongo_db->add_index('auth_consumer', array('key' => 1), array('name' => 'Indexes for Consumer Key', 'unique' => TRUE));
		}
		
		
		function getConsumerByName($name)
		{
			return $this->CI->mongo_db->findOne('auth_consumer', array('name' => $name));
		}
		
		
		function isExistsConsumerByName($name)
		{
			$consumer = $this->getConsumerByName($name);
			
			return isset($consumer['_id']);
		}
		
		
		function getConsumerByKey($key)
		{
			return $this->CI->mongo_db->findOne('auth_consumer', array('key' => $key));
		}
		
		
		function isExistsConsumerByKey($key)
		{
			$consumer = $this->getConsumerByKey($key);
			
			return isset($consumer['_id']);
		}
		
		
		function register($consumer_name)
		{
			$signdate = time();
			
			return @$this->CI->mongo_db->insert(
				'auth_consumer',
				array(
					'name' => $consumer_name,
					'key' => $signdate . '_' . $this->_encrypted($consumer_name, 'CONSUMER_KEY_SJqJ1AIaAH8AHhfafh2F'),
					'secret' => $signdate . '_' . $this->_encrypted($consumer_name, 'CONSUMER_SECRET_AJjaIAJ8sjS*ajAaQ'),
					'signdate' => $signdate
				)
			);
		}

		function _encrypted($value, $key)
		{
			return hash_hmac('SHA1', $value, $key);
		}
	}
?>