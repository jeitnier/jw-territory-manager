<?php

use Illuminate\Support\MessageBag;

class DoNotCallController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('admin.do-not-calls.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.do-not-calls.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$errors = new MessageBag;

		if (Request::isMethod('POST'))
		{
			try
			{
				$do_not_calls               = new DoNotCalls();
				$do_not_calls->territory_id = Input::get('territory_id');
				$do_not_calls->first_name   = Input::has('first_name') ? Input::get('first_name') : NULL;
				$do_not_calls->last_name    = Input::has('last_name') ? Input::get('last_name') : NULL;
				$do_not_calls->address      = Input::has('address') ? Input::get('address') : NULL;
				$do_not_calls->city         = Input::has('city') ? Input::get('city') : NULL;
				$do_not_calls->zip          = Input::has('zip') ? Input::get('zip') : NULL;
				$do_not_calls->notes        = Input::has('notes') ? Input::get('notes') : NULL;
				$do_not_calls->save();

				return Redirect::back()->with('success', Lang::get('donotcalls.messages.store'));
			}
			catch (Exception $e)
			{
				$errors->add('message', Lang::get('donotcalls.messages.store_error'));
				return Redirect::back()->withInput(Input::all())->withErrors($errors);
			}
		}

		return Redirect::back();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($id)
	{
		return View::make('admin.do-not-calls.show')->withDoNotCall(DoNotCalls::find($id));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('admin.do-not-calls.edit')->withDoNotCall(DoNotCalls::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function update($id)
	{
		if ('PUT' == Input::get('_method'))
		{
			try
			{
				$do_not_calls               = DoNotCalls::find($id);
				$do_not_calls->territory_id = Input::get('territory_id');
				$do_not_calls->first_name   = Input::has('first_name') ? Input::get('first_name') : NULL;
				$do_not_calls->last_name    = Input::has('last_name') ? Input::get('last_name') : NULL;
				$do_not_calls->address      = Input::has('address') ? Input::get('address') : NULL;
				$do_not_calls->city         = Input::has('city') ? Input::get('city') : NULL;
				$do_not_calls->zip          = Input::has('zip') ? Input::get('zip') : NULL;
				$do_not_calls->notes        = Input::has('notes') ? Input::get('notes') : NULL;
				$do_not_calls->save();

				return Redirect::back()->with('success', Lang::get('donotcalls.messages.update'));
			}
			catch (Exception $e)
			{
				$errors->add('message', Lang::get('donotcalls.messages.update_error'));
				return Redirect::back()->withInput(Input::all())->withErrors($errors);
			}
		}

		return Redirect::back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if (FALSE !== strpos($id, '|'))
		{
			$ids = explode('|', $id);
		}

		try
		{
			if (isset($ids))
			{
				if (is_array($ids))
				{
					foreach ($ids AS $id)
					{
						DoNotCalls::find($id)->delete();
					}
				}

				$message = Lang::get('donotcalls.messages.destroy_bulk');
			}
			else
			{
				DoNotCalls::find($id)->delete();
				$message = Lang::get('donotcalls.messages.destroy');
			}

			return Response::json(array('status' => TRUE, 'message' => $message));
		}
		catch (Exception $e)
		{
			return Response::json(array('status' => FALSE, 'message' => Lang::get('donotcalls.messages.destroy_error')));
		}
	}

	/**
	 * Download a PDF of the Do Not Call
	 *
	 * @param $do_not_calls
	 * @return mixed
	 */
	public function printDoNotCall($do_not_calls)
	{
		$do_not_call = DoNotCalls::find($do_not_calls);
		$html        = View::make('admin.reports.do-not-call')->withDoNotCall($do_not_call)->render();

		return PDF::loadHTML($html)->download('do_not_call_' . $do_not_call->address . '_' . $do_not_call->city . '_' . $do_not_call->zip);
	}

}
