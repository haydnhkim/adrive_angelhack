<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Coupon extends CI_Model
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
			$this->mongo_db->add_index('coupon', array('_id_ad' => 1), array('name' => 'Indexes for _id_ad', 'background' => true));
			$this->mongo_db->add_index('coupon', array('enddate' => 1), array('name' => 'Indexes for enddate', 'background' => true));
			$this->mongo_db->add_index('ticketedcoupon', array('_id_coupon' => 1), array('name' => 'Indexes for _id_coupon', 'background' => true));
			$this->mongo_db->add_index('ticketedcoupon', array('_id_user' => 1), array('name' => 'Indexes for _id_user', 'background' => true));
			$this->mongo_db->add_index('ticketedcoupon', array('is_used' => 1), array('name' => 'Indexes for is_used', 'background' => true));
			$this->mongo_db->add_index('ticketedcoupon', array('signdate' => -1), array('name' => 'Indexes for signdate', 'background' => true));
			$this->mongo_db->add_index('ticketedcoupon', array('enddate' => -1), array('name' => 'Indexes for enddate', 'background' => true));

		}

		/**
		 * 존재하는 쿠폰 Object 인지 여부 확인
		 * 
		 * @Params (String) 쿠폰 Object Id
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
		 * 쿠폰 Object 검색
		 * 
		 * @Params (String) 쿠폰 Object Id
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
			
			$output = $this->mongo_db->findOne('coupon', $params);
			
			// return $output;
			return ($outputized ? $this->outputized($output, $minimized) : $output);
		}

		/**
		 * 쿠폰 Object 등록
		 * 
		 * @Params (MongoId) 업체 id
		 * @Params (String) 쿠폰이름
		 * @Params (String) 쿠폰내용
		 * @Params (unixtimestamp) 사용기간
		 * @Params (unixtimestamp) 유효기간
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function register($_id_ad, $title, $content, $period, $enddate)
		{
			$params = array(
				'_id_ad' => $this->mongo_db->mongoId($_id_ad),
				'title' => $title,
				'content' => $content,
				'period' => intval($period),
				'enddate' => intval($enddate),
				'signdate' => time(),
				);

			$result = $this->mongo_db->insert('coupon', $params);

			$this->mongo_db->update_set('ads', 
				array('_id' => $this->mongo_db->mongoId($_id_ad)), 
				array('has_coupon' => true));


			return $result;
		}

		/**
		 * 쿠폰발급
		 * 
		 * @Params (MongoId) 쿠폰 id
		 * @Params (MongoId) 유저 id
		 * @Params (Interger) 기간
		 * @Params (MongoId) 쿠폰 id
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function ticketcoupon($_id_coupon, $_id_user)
		{
			$coupon = $this->findOneById($_id_coupon, true);
			$period = $coupon['period'];
			$enddate = $coupon['enddate'];

			$params = array(
				'_id_coupon' => $this->mongo_db->mongoId($_id_coupon),
				'_id_user' => $this->mongo_db->mongoId($_id_user),
				'signdate' => time(),
				'enddate' => min(time() + $period, $enddate),
				'is_used' => false
				);

			$result = $this->mongo_db->insert('ticketedcoupon', $params);

			return $result;
		}

		/**
		 * 발급된 쿠폰 조회
		 * 
		 * @Params (MongoId) 유저 id
		 * @Params (String) 정렬방식
		 * @Params (MongoId) 어디부터조회
		 * @Params (Interger) 갯수
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function findticket($_id_user, $sort, $start_at, $count, $except_used = false)
		{
			$count = intval($count);
			$count = $count > 0 ? $count : 10;

			$params = array(
				'_id_user' => $this->mongo_db->mongoId($_id_user)
				);

			if($start_at)
			{
				$params['_id'] = array('$lt' => $this->mongo_db->mongoId($start_at));
			}

			// if(!$except_used)
			// {
			// 	$params['is_used'] = null;
			// }

			return $this->mongo_db->find('ticketedcoupon', $params)->sort(array($sort => -1))->limit($count);
		}

		/**
		 * 쿠폰사용
		 * 
		 * @Params (MongoId) 쿠폰 id
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function confirmcoupon($_id_coupon)
		{
			$result = $this->mongo_db
			->where(array('_id' => $this->mongo_db->mongoId($_id_coupon)))
			->set(array('is_used' => true))
			->update('ticketedcoupon');

			return $result;
		}

		/**
		 * 여러 필드 변경
		 * 
		 * @Params (String) 사용자 쿠폰 Object Id
		 * @Params (Array) 변경할 필드와 값 쌍
		 * 
		 * @Return (Array) 가공된 쿠폰 정보
		 */
		function update_set($_id, $_field)
		{
			if ($_id and $_field)
			{
				$params = array(
					'_id' => $this->mongo_db->mongoId($_id)
				);

				return $this->mongo_db->update_set('coupon', $params, $_field);
			}
		}

		/**
		 * 가공된 쿠폰 정보 반환
		 * 
		 * @Params (Array) 가공전 쿠폰 정보
		 * @Return (Array) 가공된 쿠폰 정보
		 */
		function outputized($object, $minimized = false)
		{

			if (isset($object['_id']) == true)
			{
				$output = array();
				
				$output['_id_coupon'] = $object['_id']->{'$id'};
				$output['title'] = $object['title'];
				$output['content'] = $object['content'];
				$output['period'] = $object['period'];
				$output['enddate'] = $object['enddate'];
				$output['signdate'] = $object['signdate'];

				if(isset($object['_id_user']))$output['_id_user'] = $object['_id_user'];

				$ci =& get_instance();
				$ci->load->model('ads');

				$output['ad'] = $ci->ads->findOneById($object['_id_ad']->{'$id'}, true);

				return $output;
			}
			else
			{
				return null;
			}
		}


	}

