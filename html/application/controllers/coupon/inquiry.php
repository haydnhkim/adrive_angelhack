<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Inquiry extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();

			// $this->load->library('validator');
			
			$this->load->model('api');
			$this->load->model('coupon');
		}

		function index()
		{
			$this->api->validate('user');

			$sort = $this->input->get('sort');
			$start_at = $this->input->get('start_at');
			$count = $this->input->get('count');
			$except_used = $this->input->get('except_used');

			if($sort && ($sort != 'signdate' || $sort != 'enddate'))
			{
				$this->api->failed(CODE_VALUE_INVALID, 'sort', 'Specified sort value is wrong.');
			}
			else
			{
				if(!$sort || $sort == null)
				{
					$sort = 'signdate';
				}

				if($except_used)
				{
					$except_used = (strtoupper($except_used) == 'T')?true:false;
				}

				$found_raw = $this->coupon->findticket($this->api->authorized_user_id(), $sort, $start_at, $count, $except_used);

				$found = array();

				foreach ($found_raw as $row) {

					$coupon = $this->coupon->findOneById($row['_id_coupon'], true);

					$ticket = array();
					$ticket['coupon'] = $coupon;
					$ticket['signdate'] = $row['signdate'];
					$ticket['enddate'] = $row['enddate'];
					$ticket['is_used'] = $row['is_used'];

					$found[] = $ticket;
				}

				$output = array(
					'coupon' => $found
					);

				$this->api->success($output);
			}
		}
	}
