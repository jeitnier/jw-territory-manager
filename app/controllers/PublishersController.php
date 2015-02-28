<?php

use Illuminate\Support\MessageBag;

class PublishersController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$publishers = Sentry::getUserProvider()
							->createModel()
							->join('users_groups', 'users_groups.user_id', '=', 'users.id')
							->where('users_groups.group_id', '=', 2)
							->orderBy('users.last_name', 'ASC')
							->orderBy('users.first_name', 'ASC')
							->paginate();

		return View::make('admin.publishers.index')->withPublishers($publishers);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.publishers.create');
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
			$validator = Validator::make(Input::all(), array(
				'first_name' => 'required',
			    'last_name'  => 'required'
			));

			if ($validator->passes())
			{
				try
				{
					$username = Utility::generateUsername(array(Input::get('first_name'), Input::get('last_name')));

					// create the user
					$user = Sentry::createUser(array(
						'first_name' => Input::get('first_name'),
						'last_name'  => Input::get('last_name'),
						'username'   => $username,
						'password'   => Utility::generatePassword(),
						'email'      => $username . '@territorymanager.org',
						'activated'  => TRUE
					));

					// find the group using the group id
					$group = Sentry::findGroupById(2);

					// assign the group to the user
					$user->addGroup($group);

					return Redirect::back()->with('success', Lang::get('publishers.messages.store'));
				}
				catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
				{
					$errors->add('message', 'Login field is required.');
					return Redirect::back()->withInput(Input::all())->withErrors($errors);
				}
				catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
				{
					$errors->add('message', 'Password is required.');
					return Redirect::back()->withInput(Input::all())->withErrors($errors);
				}
				catch (Cartalyst\Sentry\Users\UserExistsException $e)
				{
					$errors->add('message', 'User with this login already exists.');
					return Redirect::back()->withInput(Input::all())->withErrors($errors);
				}
				catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
				{
					$errors->add('message', 'Group was not found.');
					return Redirect::back()->withInput(Input::all())->withErrors($errors);
				}
			}

			return Redirect::back()->withInput(Input::all())->withErrors($validator);
		}

		return View::make('admin.publishers.create');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($id)
	{
		$territories = Territories::select('territories.id', 'territories.label', 'territories.map_embed_id',
		                                   'tt.label AS type', 'tat.label AS area_type',
		                                   'tsio.signed_out')
								->join('territories_types AS tt', 'territories.type_id', '=', 'tt.id')
								->join('territories_area_types AS tat', 'territories.area_type_id', '=', 'tat.id')
								->join('territories_sign_in_out AS tsio', function($join) use ($id)
								{
									$join->on('territories.id', '=', 'tsio.territory_id')
										->where('tsio.publisher_id', '=', $id);
								})
								->whereNotNull('tsio.signed_out')
								->whereNull('tsio.signed_in')
								->orderBy('territories.label', 'ASC')
								->get();

		return View::make('admin.publishers.show')->withPublisher(User::find($id))->withTerritories($territories);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('admin.publishers.edit')->withPublisher(User::find($id));
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
			$validator = Validator::make(Input::all(), array(
				'first_name' => 'required',
				'last_name'  => 'required'
			));

			if ($validator->passes())
			{
				$user             = User::find($id);
				$user->first_name = Input::get('first_name');
				$user->last_name  = Input::get('last_name');
				$user->activated  = Input::get('activated');
				$user->save();

				return Redirect::route('admin.publishers.index')->with('success', Lang::get('publishers.messages.update'));
			}

			return Redirect::back()->withInput(Input::all())->withErrors($validator);
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
						User::find($id)->delete();
					}
				}

				$message = Lang::get('publishers.messages.destroy_bulk');
			}
			else
			{
				User::find($id)->delete();
				$message = Lang::get('publishers.messages.destroy');
			}

			return Response::json(array('status' => TRUE, 'message' => $message));
		}
		catch (Exception $e)
		{
			return Response::json(array('status' => FALSE, 'message' => Lang::get('publishers.messages.destroy_error')));
		}
	}

}
