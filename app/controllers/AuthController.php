<?php

use Illuminate\Support\MessageBag;

class AuthController extends BaseController {

	/**
	 * Allowed db columns used to login with
	 */
	protected $login_columns = array('username', 'email');

	/**
	 * Handles login interactions
	 *
	 * @return object|string
	 */
	public function login()
	{
		if (Sentry::check())
		{
			if (Sentry::getUser()->hasAccess('admin'))
			{
				return Redirect::route('admin.index');
			}
			else
			{
				return Redirect::url('');
			}
		}

		$errors = new MessageBag;

		$input = Input::all();

		if (Request::isMethod('POST'))
		{
			$username    = Input::has('username') ? Input::get('username') : NULL;
			$password    = Input::has('password') ? Input::get('password') : NULL;
			$auth_method = Input::has('remember_me') ? 'authenticateAndRemember' : 'authenticate';

			try
			{
				$credentials = array(
					'username' => $username,
					'password' => $password
				);

				// log the user in
				$user = Sentry::$auth_method($credentials);

				if ($user->hasAccess('admin'))
				{
					return Redirect::route('admin.index');
				}
				elseif ($user->hasAccess('universal'))
				{
					return Redirect::route('congregation.index');
				}
			}
			catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
			{
				$errors->add('message', 'Please enter an email address.');
				return Redirect::route('auth.login')->withInput($input)->withErrors($errors);
			}
			catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
			{
				$errors->add('message', 'Please enter a password.');
				return Redirect::route('auth.login')->withInput($input)->withErrors($errors);
			}
			catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
			{
				$errors->add('message', 'You aren\'t an active user yet. Please check your email for your activation link');
				return Redirect::route('auth.login')->withInput($input)->withErrors($errors);
				//$user = Sentry::getUserProvider()->findByLogin(Input::get('email'));
				//Email::queue($user, 'site.users.emailActivation', 'Ativação da sua conta na Vevey');
			}
			catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
			{
				$errors->add('message', 'Username and/or password are incorrect.');
				return Redirect::route('auth.login')->withInput($input)->withErrors($errors);
			}
			catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
			{
				$errors->add('message', 'A user with that email address or username couldn\'t be found');
				return Redirect::route('auth.login')->withInput($input)->withErrors($errors);
			}
			catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
			{
				$throttle = Sentry::findThrottlerByUserId(1);
				$time     = $throttle->getSuspensionTime();

				$errors->add('message', 'You entered incorrect credentials too many times. You may try again in ' . $time . ' minute(s).');
				return Redirect::route('auth.login')->withInput($input)->withErrors($errors);
			}
			catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
			{
				$errors->add('message', 'Looks like your account doesn\'t have access any longer.');
				return Redirect::route('auth.login')->withInput($input)->withErrors($errors);
			}
		}

		return View::make('auth.login', $input);
	}

	/**
	 * Handles logout action
	 *
	 * @return object
	 */
	public function logout()
	{
		Sentry::logout();
		return Redirect::route('auth.login');
	}

	/**
	 * Handles Forgot Password Procedures
	 *
	 * @return mixed
	 */
	public function forgotPassword()
	{
		$errors = new MessageBag;
		$input  = Input::all();

		if (Request::isMethod('POST'))
		{
			$validator = Validator::make($input, array(
				'login' => 'required'
			));

			if ($validator->passes())
			{
				try
				{
					// find user by email address or username
					$empty_user = Sentry::getUserProvider()->getEmptyUser();

					$user = $empty_user->where('email', '=', $input['login'])
						->orWhere('username', '=', $input['login'])
						->first();

					// get reset code for user
					$reset_code = $user->getResetPasswordCode();

					// get the activation code
					$data['reset_password_link'] = url('') . '/auth/reset-password?code=' . $reset_code;

					$data['user'] = $user;

					// email reset code to user
					try
					{
						$response = Email::forgotPassword($data);

						return Redirect::back()->with('success', 'An email was sent to <strong>' . $user->email . '</strong> with a link to reset your password.');
					}
					catch (Exception $e)
					{
						$errors->add('message', 'Couldn\'t send a password reset request email at this time. Try again later.');
						return Redirect::back()->withErrors($errors);
					}
				}
				catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
				{
					$errors->add('message', 'We weren\'t able to find a user with those credentials');
					return Redirect::back()->withInput($input)->withErrors($errors);
				}
			}
		}

		return View::make('frontend.auth.forgot-password');
	}

}