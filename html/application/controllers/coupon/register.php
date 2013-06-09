<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Register extends CI_Controller
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
			$this->api->validate('any');

			$_id_ad = $this->input->post('_id_ad');
			$title = $this->input->post('title');
			$content = $this->input->post('content');
			$period = $this->input->post('period');
			$enddate = $this->input->post('enddate');

			if(!$_id_ad)
			{
				$this->api->failed(CODE_VALUE_MISSING, '_id_ad', 'The _id_ad field has not specified.');
			}
			else if (!$title)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'title', 'The title field has not specified.');
			}
			else if (!$content)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'content', 'The content field has not specified.');
			}
			else if (!$period)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'period', 'The period field has not specified.');
			}
			else if ($period != null && intval($period) <= 0)
			{
				$this->api->failed(CODE_VALUE_INVALID, 'period', 'Specifed period is invlid.');
			}
			else
			{
				$res = $this->coupon->register($_id_ad, $title, $content, $period, $enddate);

				$this->load->model('ads');
				$this->ads->update_set($_id_ad, array('has_coupon' => true));
				
				$this->api->success($res);
				
			}
		}
	}