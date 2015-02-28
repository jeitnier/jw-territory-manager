<?php

use Illuminate\Support\MessageBag;

class SettingsController extends \BaseController {

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		$settings = Settings::all();
		$settings = array_column($settings->toArray(), 'option_value', 'option_name');

		return View::make('admin.settings.edit')->withSettings($settings);
	}

	/**
	 * Update the specified resource in storage.
	 * Assumes db has already been seeded with option_name values.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$errors = new MessageBag;

		if ('PUT' == Input::get('_method'))
		{
			$input = Input::all();
			unset($input['_method'], $input['_token']);

			try
			{
				foreach ($input AS $name => $value)
				{
					$settings               = Settings::where('option_name', '=', $name)->first();
					$settings->option_value = $value;
					$settings->save();
				}

				return Redirect::back()->withSuccess(Lang::get('settings.messages.update'));
			}
			catch (Exception $e)
			{
				$errors->add('message', Lang::get('settings.messages.update_error'));
				return Redirect::back()->withErrors($errors);
			}
		}

		return Redirect::back();
	}

}
