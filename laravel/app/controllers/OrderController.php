<?php

class OrderController extends BaseController {

	protected $layout = 'layouts.default';

	public function getIndex()
	{
		$this->layout->name = 'order';
		$this->layout->content = View::make('pages.order')->with('name', $this->layout->name);
	}

}