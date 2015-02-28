<?php

class AjaxController extends BaseController {

	/**
	 * Prevent non-AJAX requests from continuing.
	 */
	public function __construct()
	{
		if ( ! Request::ajax())
			exit('Get \'outta here!');
	}

	/**
	 * Delete Procedures.
	 *
	 * @return mixed
	 */
	public function delete()
	{
		$data = Input::all();

		// convert html entities back to their characters
		$data['body'] = html_entity_decode($data['body']);

		return View::make('_helpers.delete-modal', $data)->render();
	}

	/**
	 * Return publishers that match search input.
	 *
	 * @return mixed
	 */
	public function publishersSearch()
	{
		$input = Input::get('input');

		$publishers = Sentry::getUserProvider()
							->createModel()
							->join('users_groups', 'users_groups.user_id', '=', 'users.id')
							->where('users_groups.group_id', '=', 2)
							->where(function($query) use ($input)
							{
								$query->where('first_name', 'LIKE', "%$input%")
									->orWhere('last_name', 'LIKE', "%$input%");
							})
							->orderBy('last_name', 'ASC')
							->paginate();

		return View::make('admin.publishers.index-list')->withPublishers($publishers)->render();
	}

	/**
	 * Return territories that match search input.
	 *
	 * @return mixed
	 */
	public function territoriesSearch()
	{
		$input = Input::get('input');

		$territories = Territories::where('label', 'LIKE', "%$input%")
									->orderBy('label', 'ASC')
									->paginate();

		return View::make('admin.territories.index-list')->withTerritories($territories)->render();
	}

	/**
	 * Geocode an address using the Google Geocode API.
	 *
	 * @return string
	 */
	public function geocodeAddress()
	{
		$base_url = 'https://maps.googleapis.com/maps/api/geocode/json?key=' . Config::get('custom.google_api_key') . '&address=';
		$url      = $base_url . str_replace(' ', '+', Input::get('address'));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$response = curl_exec($ch);

		curl_close($ch);

		$geocode = json_decode($response);

		if ('OK' == $geocode->status)
			return json_encode(array('status' => $geocode->status, 'location' => $geocode->results[0]->geometry->location));

		return json_encode(array('status' => $geocode->status));
	}

	/**
	 * Return territory map embed id.
	 *
	 * @return mixed
	 */
	public function mapEmbedId()
	{
		if (Input::has('territory_id'))
		{
			$label = Territories::select('map_embed_id')
								->where('id', '=', Input::get('territory_id'))
								->first();

			return Response::json($label);
		}

		return Response::json(NULL);
	}

	public function getMapboxApiKey()
	{
		return Options::get('mapbox_api_key');
	}

}