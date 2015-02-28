<?php

use Illuminate\Support\MessageBag;
use Carbon\Carbon;

class TerritoriesSignInController extends BaseController {

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return mixed
	 */
	public function create()
	{
		// get all publishers that currently have signed out territory
		$publishers = User::join('territories_sign_in_out', 'users.id', '=', 'territories_sign_in_out.publisher_id')
							->select('users.first_name', 'users.last_name', 'territories_sign_in_out.publisher_id')
							->whereNotNull('territories_sign_in_out.signed_out')
							->whereNull('territories_sign_in_out.signed_in')
							->orderBy('users.last_name', 'ASC')
							->orderBy('users.first_name', 'ASC')
							->get();

		$data = [];

		if ($publishers->count())
		{
			$x     = 0;
			$names = [];
			foreach ($publishers AS $publisher)
			{
				$names[$publisher->publisher_id] = $publisher->last_name . ', ' . $publisher->first_name;
				$x++;
			}

			$data['publishers'] = $names;
		}
		else
		{
			$data['publishers'] = $publishers;
		}

		return View::make('admin.territories.sign-in.create', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return mixed
	 */
	public function store()
	{
		if (Request::isMethod('POST'))
		{
			$validator = Validator::make(Input::all(), [
				'publisher_id'   => 'required',
			    'sign_in_date'   => 'required',
				'campaign_other' => 'required_if:campaign,other'
			]);

			if ($validator->passes())
			{
				try
				{
					$sign_in_out = TerritoriesSignInOut::where('territory_id', '=', Input::get('territory_id'))
														->where('publisher_id', '=', Input::get('publisher_id'))
														->whereNotNull('signed_out')
														->whereNull('signed_in')
														->first();
					$sign_in_out->signed_in = Carbon::createFromTimestamp(strtotime(Input::get('sign_in_date')))->toDateTimeString();
					$sign_in_out->campaign  = Input::has('campaign') ? (Input::has('campaign_other') ? Input::get('campaign_other') : Input::get('campaign')) : NULL;
					$sign_in_out->notes     = Input::has('notes') ? Input::get('notes') : NULL;
					$sign_in_out->save();

					// email notification to admin
					if (NULL !== Options::get('admin_email'))
					{
						$data = ['territory' => Territories::find(Input::get('territory_id')), 'publisher' => User::find(Input::get('publisher_id'))];

						Mail::send('emails.territory-signed-in', $data, function ($message) use ($data)
						{
							$message->to(Options::get('admin_email'));
							$message->subject('Territory Signed In: ' . $data['territory']['label']);
						});
					}

					return Redirect::back()->with('success', Lang::get('territories.messages.sign_in_store'));
				}
				catch(Exception $e)
				{
					return Redirect::back()->withInput(Input::all())->withErrors(Lang::get('territories.messages.sign_in_store_error'));
				}
			}

			return Redirect::back()->withInput(Input::all())->withErrors($validator);
		}

		return Redirect::back();
	}

	/**
	 * Show Territories by Publisher.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function showPublisherTerritories($id)
	{
		try
		{
			$user = User::find($id);

			$territories_signed_out = $user->territoriesSignedOut;
			$territories            = [];

			foreach ($territories_signed_out AS $territory_signed_out)
			{
				$t         = $territory_signed_out->territory;
				$territory = [
					'territory_id'  => $t->id,
					'label'         => $t->label,
				    'sign_out_date' => Utility::formatDate(
					                    TerritoriesSignInOut::where('territory_id', '=', $t->id)
				                                            ->whereNotNull('signed_out')
				                                            ->whereNull('signed_in')
				                                            ->pluck('signed_out')
					                   ),
				    'due_date'      => Utility::dueDate(
									    TerritoriesSignInOut::where('territory_id', '=', $t->id)
														    ->whereNotNull('signed_out')
														    ->whereNull('signed_in')
														    ->pluck('signed_out')
								       ),
				    'type'          => $t->territoryType->label,
				    'area_type'     => $t->territoryAreaType->label,
				    'map_embed_id'  => $t->map_embed_id
				];

				$territories[] = $territory;
			}

			return Response::json($territories);
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			//
		}
	}

}