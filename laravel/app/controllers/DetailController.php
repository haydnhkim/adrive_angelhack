<?php

class DetailController extends BaseController {

	protected $layout = 'layouts.default';

	public function getIndex()
	{
		$this->layout->name = 'detail';
		$this->layout->content = View::make('pages.detail')->with('name', $this->layout->name);
	}

}