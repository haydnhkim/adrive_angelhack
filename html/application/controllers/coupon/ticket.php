<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Ticket extends CI_Controller
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

			$_id_coupon = $this->input->post('_id_coupon');

			if(!$_id_coupon)
			{
				$this->api->failed(CODE_VALUE_MISSING, '_id_coupon', 'The _id_coupon field has not specified.');
			}
			else if($_id_coupon && !$this->coupon->isExistsId($_id_coupon))
			{
				$this->api->failed(CODE_VALUE_INVALID, 'sort', 'Specified _id_coupon value is wrong.');
			}
			else
			{
				$result = $this->coupon->ticketcoupon($_id_coupon, $this->api->authorized_user_id());

				$this->api->success($result);
			}
		}
	}