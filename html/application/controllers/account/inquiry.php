<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Me extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();

			// $this->load->library('validator');
			
			$this->load->model('api');
			$this->load->model('account');
		}
		


		
		/**
		 * 내정보조회
		 * 
		 * @URL. /account/me
		 * @Auth. Consumer Authentication
		 * @Method. POST
		 * @Return. JSON Encoded Plain Text
		 */
		function index()
		{
			$this->api->validate('user');
			

		}
	}
?>