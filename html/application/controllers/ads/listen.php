<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Listen extends CI_Controller
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
			$this->api->validate('user');

			$_id_ad = $this->input->post('_id_ad');
			$point = $this->input->post('point');

			if(!$_id_ad)
			{
				$this->api->failed(CODE_VALUE_MISSING, '_id_ad', 'The _id_ad field has not specified.');
			}
			else if($this->ads->isExistsId($_id_ad) == false)
			{
				$this->api->failed(CODE_RESOURCE_NOTFOUND, '_id_ad', 'The specified _id_ad not found.');
			}
			else if($point !== null && intval($point) <= 0)
			{
				$this->api->failed(CODE_VALUE_INVALID, 'point', 'The specified point is invalid.');
			}
			else
			{
				$point = intval($point);
				$result = $this->ads->listen($_id_ad, $this->api->authorized_user_id(), $point);
				$this->api->success($result);
			}
		}

		function history()
		{
			$this->api->validate('user');

			$count = $this->input->get('count');
			$start_at = $this->input->get('start_at');

			$found_raw = $this->ads->listenhistory(
				$this->api->authorized_user_id(), 
				$start_at, 
				$count
				);

			$found = array();
			foreach ($found_raw as $row) {
				$ad = $this->ads->findOneById($row['_id_ad']);
				$ad = $this->ads->outputized($ad);
				$ad['point'] = $row['point'];
				$found[] = $ad;
			}

			$result['ads'] = $found;

			$this->api->success($result);
		}

	}
		