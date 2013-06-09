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
		 * 존재하는 제품 Object 인지 여부 확인
		 * 
		 * @Params (String) 제품 Object Id
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
		 * 제품 Object 검색
		 * 
		 * @Params (String) 제품 Object Id
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

		/**
		 * 제품 Object 등록
		 * 
		 * @Params (MongoId) 업체 id
		 * @Params (String) 제품이름
		 * @Params (String) 제품내용
		 * @Params (unixtimestamp) 사용기간
		 * @Params (unixtimestamp) 유효기간
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function register($category, $_id_ads, $name, $price)
		{
			$params = array(
				'category' => $category,
				'_id_ads' => $this->mongo_db->mongoId($_id_ads),
				'name' => $name,
				'price' => intval($price),
				'count_order' => 0,
				'signdate' => time()
				);

			$result = $this->mongo_db->insert('product', $params);

			return $result;
		}

		/**
		 * 제품 Object 검색
		 *
		 * @Params (String) 광고id
		 * @Params (MongoId) 광고id
		 * 
		 * @Return (Array) Found Mongo Object
		 */
		function find($categories_raw, $_id_ads, $name, $price, $start_at = false, $count = 30, $outputized = false, $minimized = false, $exclude_destroyed = false)
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

			if($_id_ads)
			{
				$params['_id_ads'] = $this->mongo_db->mongoId($_id_ads);
			}

			if($price)
			{
				$params['price'] = array('$lt' => $price);
			}

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

			$output = $this->mongo_db->find('product', $params)->limit($count);

			// return ($outputized ? $this->outputized($output, $minimized) : $output);
			return $output;
		}

		/**
		 * 여러 필드 변경
		 * 
		 * @Params (String) 사용자 제품 Object Id
		 * @Params (Array) 변경할 필드와 값 쌍
		 * 
		 * @Return (Array) 가공된 제품 정보
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
		 * 가공된 제품 정보 반환
		 * 
		 * @Params (Array) 가공전 제품 정보
		 * @Return (Array) 가공된 제품 정보
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
	}