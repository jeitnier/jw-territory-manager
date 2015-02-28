<?php

use Illuminate\Support\MessageBag;
use Carbon\Carbon;

class TerritoriesSignOutController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return mixed
	 */
	public function index()
	{
		return View::make('admin.territories.sign-out.index');
	}

	/**
	 * Display House-To-House territory listings.
	 *
	 * @return \Illuminate\View\View
	 */
	public function houseToHouse()
	{
		$data = array(
			'territories' => Territories::availableHouseToHouse(),
		    'type'        => 'House-To-House'
		);
		return View::make('admin.territories.sign-out.sign-out-type', $data);
	}

	/**
	 * Display Letter Writing / PHone territory listings.
	 *
	 * @return \Illuminate\View\View
	 */
	public function letterWritingPhone()
	{
		$data = array(
			'territories' => Territories::availableLetterWritingPhone(),
			'type'        => 'Letter Writing / Phone'
		);
		return View::make('admin.territories.sign-out.sign-out-type', $data);
	}

	/**
	 * Display Business territory listings.
	 *
	 * @return \Illuminate\View\View
	 */
	public function business()
	{
		$data = array(
			'territories' => Territories::availableBusiness(),
			'type'        => 'Business'
		);
		return View::make('admin.territories.sign-out.sign-out-type', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return mixed
	 */
	public function create($id)
	{
		$publishers = Sentry::getUserProvider()
			->createModel()
			->select('users.id', 'users.first_name', 'users.last_name', 'tsio.territory_count')
			->join('users_groups', 'users.id', '=', 'users_groups.user_id')
			->leftJoin(DB::raw('(
				SELECT publisher_id, COUNT(*) territory_count
				FROM territories_sign_in_out
				WHERE signed_in IS NULL
				GROUP BY publisher_id) tsio'), function($join)
			{
				$join->on('users.id', '=', 'tsio.publisher_id');
			})
			->where('users_groups.group_id', '=', 2)
			->orderBy('users.last_name', 'ASC')
			->orderBy('users.first_name', 'ASC')
			->get();

		$data = array(
			'publishers' => $publishers,
			'territory'  => Territories::find($id)
		);

		return View::make('admin.territories.sign-out.create', $data);
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
			try
			{
				$territories_sign_in_out               = new TerritoriesSignInOut;
				$territories_sign_in_out->territory_id = Input::get('territory_id');
				$territories_sign_in_out->publisher_id = Input::get('publisher_id');
				$territories_sign_in_out->signed_out   = Carbon::now()->toDateTimeString();
				$territories_sign_in_out->save();
			}
			catch(Exception $e)
			{
				$territories_sign_in_out->delete();

				return Redirect::back()->withInput(Input::all())->withErrors(Lang::get('territories.messages.sign_out_store_error'));
			}

			try
			{
				// generate unique key for email viewer
				$territories_sign_out_keys               = new TerritoriesSignOutKeys;
				$territories_sign_out_keys->territory_id = Input::get('territory_id');
				$territories_sign_out_keys->key          = str_random(20);
				$territories_sign_out_keys->save();
			}
			catch(Exception $e)
			{
				$territories_sign_in_out->delete();
				$territories_sign_out_keys->delete();

				return Redirect::back()->withInput(Input::all())->withErrors(Lang::get('territories.messages.sign_out_store_error'));
			}

			try
			{
				// email notification to admin
				if (NULL !== Options::get('admin_email'))
				{
					$data = [
						'territory' => Territories::find(Input::get('territory_id')),
						'publisher' => User::find(Input::get('publisher_id'))
					];

					Mail::send('emails.territory-signed-out', $data, function($message) use ($data)
					{
						$message->to(Options::get('admin_email'));
						$message->subject('Territory Signed Out: ' . $data['territory']['label']);
					});
				}

				return Redirect::route('admin.territories.sign-out.show', Input::get('territory_id'))
					->with('success', Lang::get('territories.messages.sign_out_store'));
			}
			catch(Exception $e)
			{
				$territories_sign_in_out->delete();
				$territories_sign_out_keys->delete();

				return Redirect::back()->withInput(Input::all())->withErrors(Lang::get('territories.messages.sign_out_store_error'));
			}
		}

		return Redirect::back();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function show($id)
	{
		return View::make('admin.territories.sign-out.show')->withTerritory(Territories::find($id));
	}

	/**
	 * Get territory info and return it as JSON.
	 *
	 * @param $territories
	 * @return mixed
	 */
	public function showTerritoryInfo($territories)
	{
		$territory = Territories::find($territories);

		$response = array(
			'type'         => TerritoriesTypes::find($territory->type_id)->label,
			'area_type'    => TerritoriesAreaTypes::find($territory->area_type_id)->label,
			'last_worked'  => NULL !== TerritoriesSignInOut::lastWorked($territory->id) ? Utility::formatDate(TerritoriesSignInOut::lastWorked($territory->id)->signed_in) : 'Never',
			'status'       => TerritoriesSignInOut::status($territory->id),
		    'map_embed_id' => $territory->map_embed_id
		);

		return Response::json($response);
	}

	/**
	 * Prepare the territory card view for printing.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function printTerritory($id)
	{
		return View::make('admin.territories.sign-out.territory-card-print')->withTerritory(Territories::find($id))->withPrint(TRUE);
	}

	/**
	 * Prepare the territory card view for emailing.
	 *
	 * @param $territory_id
	 * @return mixed
	 */
	public function emailTerritory($territory_id)
	{
		if ( ! $territory_id)
			return Response::json(array('status' => FALSE, 'message' => 'No territory_id provided.'));

		if ( ! Input::has('email'))
			return Response::json(array('status' => FALSE, 'message' => 'No email provided.'));

		$territory = Territories::find($territory_id);

		$email_key = $territory->emailKey;

		if (NULL === $email_key)
			return Response::json(array('status' => FALSE, 'message' => 'There was a problem getting the sign out key.'));

		$data = array(
			'title'           => 'Territory: ' . $territory->label,
			'link'            => link_to_route('territories.email-viewer', NULL, array($territory->id, $email_key->key)),
			'to'              => Input::get('email'),
		    'territory_label' => $territory->label
		);

		Mail::send('admin.territories.sign-out.territory-card-email-linker', $data, function($message) use ($data)
		{
			$message->to($data['to']);
			$message->subject('Territory Card: ' . $data['territory_label']);
		});

		return Response::json(array('status' => TRUE, 'message' => 'Territory sent to ' . Input::get('email')));
	}

	/**
	 * Display the email view.
	 *
	 * @param $territory_id
	 * @param $key
	 * @return mixed
	 */
	public function emailTerritoryViewer($territory_id, $key)
	{
		if ( ! $territory_id)
			exit('No territory_id provided.');

		if ( ! $key)
			exit('No key provided.');

		// verify the territory id & access key being passed
		if (NULL === TerritoriesSignInOut::beingWorked($territory_id))
			exit('This territory is not currently signed out.');

		$territory = Territories::find($territory_id);

		if ($territory->email_key->key !== $key)
			exit('Invalid key provided.');

		// all the checks passed, proceed
		return View::make('admin.territories.sign-out.territory-card-email-viewer')->withTerritory($territory);
	}

}
