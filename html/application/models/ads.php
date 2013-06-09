<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Ads extends CI_Model
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
			// $this->install();
		}
		/**
		 * DB 초기화 작업
		 * 
		 * @Params none
		 * @Return void
		 */
		function install()
		{
			$this->mongo_db->add_index('ads', array('name' => 1), array('name' => 'Indexes for name', 'background' => true));
			$this->mongo_db->add_index('ads', array('category' => 1), array('name' => 'Indexes for category', 'background' => true));
			$this->mongo_db->add_index('ads', array('sx' => 1), array('name' => 'Indexes for sx', 'background' => true));
			$this->mongo_db->add_index('ads', array('sy' => 1), array('name' => 'Indexes for sy', 'background' => true));
			$this->mongo_db->add_index('ads', array('ex' => 1), array('name' => 'Indexes for ex', 'background' => true));
			$this->mongo_db->add_index('ads', array('ey' => 1), array('name' => 'Indexes for ey', 'background' => true));
			$this->mongo_db->add_index('passing', array('_id_user' => 1), array('name' => 'Indexes for _id_user', 'background' => true));
			$this->mongo_db->add_index('passing', array('_id_ad' => 1), array('name' => 'Indexes for _id_ad', 'background' => true));
		}

		/**
		 * 존재하는 광고주 Object 인지 여부 확인
		 * 
		 * @Params (String) 광고주 Object Id
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
		 * 광고주 Object 검색
		 * 
		 * @Params (String) 광고주 Object Id
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
			
			$output = $this->mongo_db->findOne('ads', $params);
			
			// return $output;
			return ($outputized ? $this->outputized($output, $minimized) : $output);
		}

		/**
		 * 광고주 Object 검색
		 *
		 * @Params (MongoId) 유저아이디 
		 * @Params (String) 키테고리
		 * @Params (integer) 시작x좌표
		 * @Params (integer) 시작y좌표
		 * @Params (integer) 끝x좌표
		 * @Params (integer) 끝y좌표
		 * @Params (Boolean) 지나칠지 여부
		 * @Params (mongoId) 시작할부분 id
		 * @Params (integer) 갯수
		 * @Params (Boolean) 결과값 JSON Response 형태로 반환 여부
		 * @Params (Boolean) 결과값 간소화 여부
		 * @Params (Boolean) 삭제된 항목 제외 여부
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function find($_id_user = false, $categories_raw = false, $sx, $sy, $ex, $ey, $name = false, $passing = true, $start_at = false, $count = 30, $outputized = false, $minimized = false, $exclude_destroyed = false)
		{
			$params = array();

			$count = intval($count);
			$count = $count > 0 ? $count : 30;

			// 카테고리 필터
			if ($categories_raw && is_array($categories_raw))
			{
				$categories = array();
				
				foreach ($categories_raw as $row)
				{
					$categories[] = $row;
				}
				
				$params['category'] = array('$in' => $categories);
			}

			if($sx && $sy && $ex && $ey)
			{
				$params['x'] = array('$gt' => floatval(min($sx, $ex)), '$lt' => floatval(max($sx, $ex)));
				$params['y'] = array('$gt' => floatval(min($sy, $ey)), '$lt' => floatval(max($sy, $ey)));	
			}
			

			// $params['x'] = array('$gt' => floatval($sx));
			// $params['y'] = array('$gt' => floatval($sy));

			if($name)
			{
				$params['name'] = $name;
			}

			if($start_at)
			{
				$params['_id'] = array('$lt' => $this->mongo_db->mongoId($start_at));
			}

			if ($exclude_destroyed)
			{
				$params['is_destroyed'] = null;
			}

			$output = $this->mongo_db->find('ads', $params)->limit($count);

			if($_id_user && $passing)
			{
				foreach ($output as $ad) {
					$params = array(
						'_id_ad' => $this->mongo_db->mongoId($ad['_id']),
						'_id_user' => $_id_user,
						'signdate' => time()
						);

					$this->mongo_db->insert('passing', $params);
				}	
			}

			// return ($outputized ? $this->outputized($output, $minimized) : $output);
			return $output;
		}

		/**
		 * 광고주 Object 등록
		 * 
		 * @Params (String) 키테고리
		 * @Params (integer) x좌표
		 * @Params (integer) y좌표
		 * @Params (String) 업체이름
		 * @Params (String) 업체설명
		 * @Params (String) 광고파일 주소
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function register($category, $x, $y, $name, $description, $file_url, $address, $phone, $img)
		{
			$params = array(
				'category' => $category,
				'x' => floatval($x),
				'y' => floatval($y),
				'name' => $name,
				'description' => $description,
				'file_url' => $file_url,
				'count_view' => 0,
				'address' => $address,
				'phone' => $phone,
				'img' => $img,
				'signdate' => time(),
				);

			$result = $this->mongo_db->insert('ads', $params);

			return $result;
		}

		/**
		 * 광고들은후
		 * 
		 * @Params (MongoId) 업체 id
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function listen($_id, $_id_user, $point)
		{
			$result1 = $this->mongo_db
			->where(array('_id' => $this->mongo_db->mongoId($_id)))
			->inc(array('count_view' => 1))
			->update('ads');

			$result2 = $this->mongo_db
			->where(array('_id' => $this->mongo_db->mongoId($_id_user)))
			->inc(array('point' => $point))
			->update('user');

			$result3 = $this->mongo_db
			->insert('listen_log', array(
				'_id_ad' => $this->mongo_db->mongoId($_id),
				'_id_user' => $this->mongo_db->mongoId($_id_user),
				'point' => $point,
				'signdate' => time()
				));

			return $result1 && $result2 && $result3;
		}

		/**
		 * 광고들은내역조회
		 * 
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function listenhistory($_id_user, $start_at = false, $count)
		{
			$count = intval($count);
			$count = $count > 0 ? $count : 30;

			$params = array();
			$params['_id_user'] = $this->mongo_db->mongoId($_id_user);

			if($start_at)
			{
				$params['start_at'] = array('$lt' => $start_at);
			}

			$result = $this->mongo_db->find('listen_log', $params)->limit($count);

			return $result;
		}

		function findoftenpassing($_id_user)
		{
			$count = intval($count);
			$count = $count > 0 ? $count : 30;

			$params = array();
			$params['_id_user'] = $this->mongo_db->mongoId($_id_user);

			if($start_at)
			{
				$params['start_at'] = array('$lt' => $start_at);
			}

			$result = $this->mongo_db->find('passing', array('_id_user' => $_id_user))->sort('signdate')->limit($count);
		}

		function findrecentpassing($_id_user)
		{
			$count = intval($count);
			$count = $count > 0 ? $count : 30;

			$params = array();
			$params['_id_user'] = $this->mongo_db->mongoId($_id_user);

			if($start_at)
			{
				$params['start_at'] = array('$lt' => $start_at);
			}
			$result = $this->mongo_db->find('passing', array('_id_user' => $_id_user))->sort('signdate')->limit($count);	
		}



		/**
		 * 여러 필드 변경
		 * 
		 * @Params (String) 사용자 광고주 Object Id
		 * @Params (Array) 변경할 필드와 값 쌍
		 * 
		 * @Return (Array) 가공된 광고주 정보
		 */
		function update_set($_id, $_field)
		{
			if ($_id and $_field)
			{
				$params = array(
					'_id' => $this->mongo_db->mongoId($_id)
				);

				return $this->mongo_db->update_set('ads', $params, $_field);
			}
		}

		/**
		 * 가공된 광고주 정보 반환
		 * 
		 * @Params (Array) 가공전 광고주 정보
		 * @Return (Array) 가공된 광고주 정보
		 */
		function outputized($object, $minimized = false)
		{

			if (isset($object['_id']) == true)
			{
				$output = array();
				
				$output['_id_ad'] = $object['_id']->{'$id'}; // 회원 
				$output['category'] = $object['category'];
				$output['x'] = $object['x'];
				$output['y'] = $object['y'];
				$output['name'] = $object['name']; // 
				$output['description'] = $object['description'];
				$output['count_view'] = $object['count_view'];

				$ci =& get_instance();
				$ci->load->model('api');

				$passing = $this->mongo_db->find('passing', array('_id_ad' => $object['_id'], '_id_user' => $ci->api->authorized_user_id()));					

				$output['count_passing'] = $passing->count();
				$output['file_url'] = $object['file_url'];
				$output['signdate'] = $object['signdate'];

				if(isset($object['phone']))$output['phone'] = $object['phone'];
				if(isset($object['address']))$output['address'] = $object['address'];
				if(isset($object['img']))$output['img'] = $object['img'];

				return $output;
			}
			else
			{
				return null;
			}
		}


	}

