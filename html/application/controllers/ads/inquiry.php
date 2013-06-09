<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Inquiry extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();

			// $this->load->library('validator');
			
			$this->load->model('api');
			$this->load->model('ads');
		}

		function index()
		{
			$this->api->validate('any');

			$categories = $this->input->get('category');
			$coordinate = $this->input->get('coords');
			$coords = explode('|', $coordinate);

			if(count($coords) == 4)
			{
				$sx = floatval($coords[0]);
				$sy = floatval($coords[1]);
				$ex = floatval($coords[2]);
				$ey = floatval($coords[3]);	
			}
			else{
				$sx = false;
				$sy = false;
				$ex = false;
				$ey = false;
			}

			$name = $this->input->get('name');
			$start_at = $this->input->get('start_at');
			$count = $this->input->get('count');

			// if(!isset($sx) or !isset($sy) or !isset($ex) or !isset($ey))
			// {
			// 	$this->api->failed(CODE_VALUE_MISSING, 'coords', 'The four coordinate has not specified.');
			// }
			// else 
			// if($sx == $ex or $sy == $ey)
			// {
			// 	$this->api->failed(CODE_VALUE_INVALID, 'coords', 'The some of coordinate field was wrong.');
			// }
			// else{

				// 카테고리들 가공
				$categories_raw = explode(',', $categories);
				$categories = array();
				
				foreach ($categories_raw as $row)
				{
					if (trim($row) != '')
					{
						$categories[] = trim($row);
					}
				}
				
				$categories = array_unique($categories);
				
				$found = array();
				$found_raw = $this->ads->find(
					$this->api->authorized_user_id(),
					$categories, 
					$sx, 
					$sy, 
					$ex, 
					$ey, 
					$name,
					$start_at, 
					$count
					);

				foreach ($found_raw as $row){
					$found[] = $this->ads->outputized($row);
				}

				$output = array();

				$output['ads'] = $found;

				$this->api->success($output);
			// }

			
		}

		function get()
		{
			$this->api->validate('any');

			$_id_ad = $this->input->get('_id_ad');

			if(!$_id_ad)
			{
				$this->api->failed(CODE_VALUE_MISSING, '_id_ad', 'The _id_ad field has not specified.');
			}
			else if($this->ads->isExistId($_id_ad) == false)
			{
				$this->api->failed(CODE_RESOURCE_NOTFOUND, '_id_ad', 'The specified _id_ad not found.');
			}
			else
			{
				$output = array(
					'ads' => $this->ads->findOneById($_id_ad, true)
					);

				$this->api->success($output);
			}
		}

		function recent()
		{
			
		}

		function often()
		{
			
		}
	}
		