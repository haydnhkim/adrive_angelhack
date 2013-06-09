<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Account extends CI_Model
	{
		/**
		 * 클래스 생성 시 호출
		 * 
		 * @Params none
		 * @Return void
		 */
		function __construct()
		{
			$this->load->library('Mongo_db');
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
			$this->mongo_db->add_index('user', array('username' => 1), array('name' => 'Indexes for Username', 'background' => true));
			$this->mongo_db->add_index('user', array('username' => 1, 'is_destroyed' => 1), array('name' => 'Indexes for Valid Email', 'background' => true));
			$this->mongo_db->add_index('user', array('point' => 1, 'is_destoyed' => 1), array('name' => 'Indexes for point', 'background' => true));
		}
		
		
		/**
		 * 존재하는 사용자 Object 인지 여부 확인
		 * 
		 * @Params (String) 사용자 Object Id
		 * @Return (Boolean) 등록여부 (true:성공, object_id : 검색실패, empty : 입력오류)
		 */
		function isExistsIds($_ids, $exclude_destroyed = false)
		{
			$output = false;
			$_ids = trim($_ids);
			
			if ($_ids == '')
			{
				return false;
			}
			
			if (is_array($_ids) == false)
			{
				$_ids = explode(',', $_ids);
			}
			
			foreach ($_ids as $_id)
			{
				if (trim($_id) == '') continue;
				
				if ($this->isExistsId($_id, $exclude_destroyed) == false)
				{
					return $_id;
				}
				else
				{
					$output = true;
				}
			}
			
			return $output;
		}
		
		
		/**
		 * 존재하는 계정 Object 인지 여부 확인
		 * 
		 * @Params (String) 계정 Object Id
		 * @Params (Boolean) 삭제된 항목 제외 여부
		 * 
		 * @Return (Boolean) 등록여부
		 */
		function isExistsId($_id, $exclude_destroyed = false)
		{
			$object = $this->findOneById($_id);
			
			return isset($object['_id']);
		}
		
		/**
		 * 계정 Object 검색
		 * 
		 * @Params (String) 계정 Object Id
		 * @Params (Boolean) 결과값 JSON Response 형태로 반환 여부
		 * @Params (Boolean) 결과값 간소화 여부
		 * @Params (Boolean) 삭제된 항목 제외 여부
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function findOneById($id, $outputized = false, $minimized = false, $exclude_destroyed = false)
		{
			$params = array(
				'_id' => $this->mongo_db->mongoId($id)
			);
			
			if ($exclude_destroyed)
			{
				$params['is_destroyed'] = 1;
			}
			
			$output = $this->mongo_db->findOne('user', $params);
			
			return ($outputized ? $this->outputized($output, $minimized) : $output);
			// return $output;
		}
		
		/**
		 * 유저네임과 비밀번호로 계정 검색
		 * 
		 * @Params (String) 유저네임
		 * @Params (String) 비밀번호
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function findOneByUsernameAndPassword($username, $password)
		{
			$params = array(
				'username' => $username,
				'password' => $this->encryptedPassword($password),
				'is_destroyed' => false
			);
			
			return $this->mongo_db->findOne('user', $params);
		}
		
		
		/**
		 * 등록된 사용자이름인지 여부 확인
		 * 
		 * @Params (String) 사용자이름
		 * @Params (Boolean) 삭제된 항목 포함 여부
		 * 
		 * @Return (Boolean) 등록여부
		 */
		function isExistsUsername($username, $include_destroyed = false)
		{
			$object = $this->findOneByUsername($username, $include_destroyed);
			
			return isset($object['_id']);
		}
		
		
		/**
		 * 사용자이름으로 계정 검색
		 * 
		 * @Params (String) 사용자이름
		 * @Params (Boolean) 삭제된 항목 포함 여부
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function findOneByUsername($username, $include_destroyed = false)
		{
			if ($username === null)
			{
				return null;
			}
			
			$params = array(
				'username' => $username
			);
			
			if ($include_destroyed == false)
			{
				$params['is_destroyed'] = false;
			}
			
			return $this->mongo_db->findOne('user', $params);
		}
		
		
		/**
		 * 비밀번호 암호화
		 * 
		 * @Params (String) 비밀번호
		 * @Return (String) 암호화된 비밀번호
		 */
		function encryptedPassword($password)
		{
			return $this->_encrypted($password);
		}
		
		
		/**
		 * 계정 등록
		 * 
		 * @Params (String) 비밀번호
		 * @Params (String) 사용자이름
		 * @Return (Array) Inserted Mongo Object
		 */
		function register($username, $password, $access_token = null)
		{
			$params = array(
				'username' => $username,
				'access_token' => $access_token,
				'password' => $password ? $this->encryptedPassword($password) : null,
				'account_type' => 'user',
				'is_destroyed' => false,
				'point' => 0,
				'signdate' => time(),
				'last_login' => time()
			);
			
			if (!$access_token) unset($params['access_token']);
			
			return @$this->mongo_db->insert('user', $params);
		}

		/**
		 * 특정 필드 변경
		 * 
		 * @Params (String) 사용자 계정 Object Id
		 * @Params (String) 변경할 필드
		 * @Params (*) 변경할 값
		 * 
		 * @Return (Array) 가공된 계정 정보
		 */
		function update($_id, $field, $value, $is_password = false)
		{
			$params_find = array('_id' => $this->mongo_db->mongoId($_id));
			$params_update = array($field => !$is_password ? $value : $this->encryptedPassword($value));
			
			return @$this->mongo_db->update_set('user', $params_find, $params_update);
		}

		/**
		 * 여러 필드 변경
		 * 
		 * @Params (String) 사용자 계정 Object Id
		 * @Params (Array) 변경할 필드와 값 쌍
		 * 
		 * @Return (Array) 가공된 계정 정보
		 */
		function update_set($_id, $_field)
		{
			if ($_id and $_field)
			{
				$params = array(
					'_id' => $this->mongo_db->mongoId($_id)
				);

				return $this->mongo_db->update_set('user', $params, $_field);
			}
		}

		/**
		 * 가공된 계정 정보 반환
		 * 
		 * @Params (Array) 가공전 계정 정보
		 * @Return (Array) 가공된 계정 정보
		 */
		function outputized($user, $minimized = false)
		{
			if (isset($user['_id']) == true)
			{
				$output = array();
				
				$output['_id_user'] = $user['_id']->{'$id'}; // 회원 Object Id
				$output['username'] = $user['username']; // 사용자이름
				$output['point'] = $user['point'];
				
				return $output;
			}
			else
			{
				return null;
			}
		}
		
		function _encrypted($value)
		{
			return sha1($value);
		}
	}
?>