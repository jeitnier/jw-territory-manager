<?php

use Illuminate\Support\MessageBag;

class UserController extends BaseController {

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return mixed
	 */
	public function edit()
	{
		return View::make('user.edit')->withUser(User::find(Sentry::getUser()->id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @return mixed
	 */
	public function update()
	{
		$errors = new MessageBag;

		if (Request::isMethod('POST'))
		{
			try
			{
				$user = Sentry::getUser();
			}
			catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
			{
				$errors->add('message', 'That user wasn\'t found.');
				return Redirect::back()->withErrors($errors);
			}

			$messages = array(
				'same' => 'New Password & New Password Confirmation must match.'
			);
			
			$validator = Validator::make(Input::all(), array(
				'old-password'         => 'required',
			    'new-password'         => 'required',
			    'new-password-confirm' => 'required|same:new-password'
			), $messages);

			if ($validator->passes())
			{
				if ($user->checkPassword(Input::get('old-password')))
				{
					// get the password reset code
					$reset_code = $user->getResetPasswordCode();

					// check if the reset password code is valid
					if ($user->checkResetPasswordCode($reset_code))
					{
						// attempt to reset the user password
						if ($user->attemptResetPassword($reset_code, Input::get('new-password')))
						{
							return Redirect::back()->with('success', 'Settings updated successfully!');
						}
						else
						{
							$errors->add('message', 'Password reset failed.');
							return Redirect::back()->withErrors($errors);
						}
					}
					else
					{
						// The provided password reset code is Invalid
					}
				}

				$errors->add('message', 'The old password given does not match your current password.');
				return Redirect::back()->withErrors($errors);
			}

			return Redirect::back()->withErrors($validator);
		}
		
		return Redirect::back();
	}

}