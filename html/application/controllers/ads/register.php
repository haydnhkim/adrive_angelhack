<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Register extends CI_Controller
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
			// $this->api->validate('any');

			$category = $this->input->post('category');
			$x = $this->input->post('x');
			$y = $this->input->post('y');
			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$file_url = $this->input->post('file_url');
			$address = $this->input->post('address');
			$phone = $this->input->post('phone');
			$img = $this->input->post('img');

			if(!$category)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'category', 'The category field has not specified.');
			}
			else if (!$x)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'x coord', 'The x coord field has not specified.');
			}
			else if ($x !== null && floatval($x) <= 0)
			{
				$this->api->failed(CODE_VALUE_INVALID, 'x coord', 'The specified x is invalid.');
			}
			else if (!$y)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'y coord', 'The y coord field has not specified.');
			}
			else if ($y !== null && floatval($y) <= 0)
			{
				$this->api->failed(CODE_VALUE_INVALID, 'y coord', 'The specified y is invalid.');
			}
			else if (!$name)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'name', 'The name field has not specified.');
			}
			else if (!$description)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'description', 'The description field has not specified.');
			}
			else if (!$file_url)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'file_url', 'The file_url field has not specified.');
			}
			else if (!$phone)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'phone', 'The phone field has not specified.');
			}
			else if (!$address)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'address', 'The address field has not specified.');
			}
			else if (!$img)
			{
				$this->api->failed(CODE_VALUE_MISSING, 'img', 'The img field has not specified.');
			}
			else
			{
				$res = $this->ads->register($category, $x, $y, $name, $description, $file_url, $address, $phone, $img);
				
				$this->api->success($res);
				
			}
		}
	}