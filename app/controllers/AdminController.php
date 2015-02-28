<?php

class AdminController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return mixed
	 */
	public function index()
	{
		return View::make('admin.index');
	}

}
