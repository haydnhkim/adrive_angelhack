<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Product extends CI_Model
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
		 * 존재하는 주문 Object 인지 여부 확인
		 * 
		 * @Params (String) 주문 Object Id
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
		 * 주문 Object 검색
		 * 
		 * @Params (String) 주문 Object Id
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
			
			$output = $this->mongo_db->findOne('product', $params);
			
			// return $output;
			return ($outputized ? $this->outputized($output, $minimized) : $output);
		}

		function register($_id_product, $_id_user)
		{
			$result1 = $this->mongo_db
			->where(array('_id' => $this->mongo_db->mongoId($_id_product)))
			->inc(array('count_order' => 1))
			->update('product');

			$result2 = $this->mongo_db->insert('order', array(
				'_id_user' => $this->mongo_db->mongoId($_id_user),
				'_id_product' => $this->mongo_db->mongoId($_id_product),
				'is_used' => false,
				'signdate' => time()
				));

			return $result1 && $result2;
		}

		function find($_id_product, $_id_user, $start_at, $count, $exclude_used = false)
		{
			
		}



		/**
		 * 가공된 주문 정보 반환
		 * 
		 * @Params (Array) 가공전 주문 정보
		 * @Return (Array) 가공된 주문 정보
		 */
		function outputized($object, $minimized = false)
		{

			if (isset($object['_id']) == true)
			{
				$output = array();
				
				$output['_id_product'] = $object['_id']->{'$id'};
				$output['category'] = $object['category'];
				$output['name'] = $object['name'];
				$output['price'] = $object['price'];
				$output['count_order'] = $object['count_order'];
				$output['signdate'] = $object['signdate'];

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