<?php

class HomeController extends BaseController {

	protected $layout = 'layouts.default';

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		Auth::attempt(array('username' => 'admin', 'password' => 'admin'));

		$api = new Api;
		$request = $api->api_get('/ads/inquiry', array());

		$result = array();
		if($request->result){
			$result = $request->data->ads;
			$processed_map_data = array();
			foreach ($result as $row) {
				array_push($processed_map_data, array((float)$row->y, (float)$row->x));
			}
		}

		$this->layout->name = 'main';
		$this->layout->content = View::make('pages.main')->with('name', $this->layout->name)
			->with('map_data', json_encode($processed_map_data, true));
	}

}