<?php

class ManageController extends BaseController {

	protected $layout = 'layouts.default';

	public function getIndex()
	{
		return $this->getList();
	}

	public function getList()
	{

		$api = new Api;
		$request = $api->api_get('/ads/inquiry', array());

		$result = array();
		if($request->result){
			$result = $request->data->ads;
		}

		$this->layout->name = 'manage_list';
		$this->layout->content = View::make('pages.manage_list')->with('name', $this->layout->name)
			->with('list', $result);
	}

	public function getRegist()
	{
		$this->layout->name = 'manage_regist';
		$this->layout->content = View::make('pages.manage_regist')->with('name', $this->layout->name);
	}

	public function postRegist()
	{
		$destinationPath = public_path().'/upload';
		$data = Input::get();
		$file_img = Input::file('img');
		if($file_img){
			$file_img_name = 'img_'.date('ymdhis').'_'.substr(md5(rand(0, 5)), 0, 5).'.'.$file_img->getClientOriginalExtension();
			$file_img->move($destinationPath, $file_img_name);
		}
		$file_mp3 = Input::file('file_url');
		if($file_mp3){
			$file_mp3_name = 'mp3_'.date('ymdhis').'_'.substr(md5(rand(0, 5)), 0, 5).'.'.$file_mp3->getClientOriginalExtension();
			$file_mp3->move($destinationPath, $file_mp3_name);
		}

		if($file_img && $file_mp3){
			$files_paths = array(
				'img' => "http://{$_SERVER['HTTP_HOST']}/upload/{$file_img_name}",
				'file_url' => "http://{$_SERVER['HTTP_HOST']}/upload/{$file_mp3_name}"
			);
			$data = array_merge($data, $files_paths);
		}
		$api = new Api;
		$request = $api->api_post('/ads/register', $data);

		if($request->result){
			Session::flash('ok', 'ok');
		}elseif($request->error){
			Session::flash('error', $request->error->message);
		}

		return Redirect::to('manage/regist');
	}

}