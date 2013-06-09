<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Confirm extends CI_Controller
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
			else
			{
				$result = $this->coupon->confirmcoupon($_id_coupon);

				$this->api->success();
			}
		}

	}