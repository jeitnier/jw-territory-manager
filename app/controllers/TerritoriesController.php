<?php

use Illuminate\Support\MessageBag;

class TerritoriesController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$territories = Territories::labelAscending();

		$paginator = Paginator::make($territories->toArray(), $territories->count(), 50);

		return View::make('admin.territories.index')->withTerritories($paginator);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.territories.create');
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
				'label'        => 'required|unique:territories',
				'type_id'      => 'required',
				'area_type_id' => 'required',
				'map_embed_id' => 'required|unique:territories'
			));

			if ($validator->passes())
			{
				try
				{
					$territory               = new Territories();
					$territory->label        = Input::get('label');
					$territory->type_id      = Input::get('type_id');
					$territory->area_type_id = Input::get('area_type_id');
					$territory->map_embed_id = Input::get('map_embed_id');
					$territory->notes        = Input::has('notes') ? Input::get('notes') : NULL;
					$territory->save();

					return Redirect::route('admin.territories.create')->with('success', Lang::get('territories.messages.store'));
				}
				catch (Exception $e)
				{
					$errors->add('message', Lang::get('territories.messages.store_error'));
					return Redirect::back()->withInput(Input::all())->withErrors($errors);
				}
			}

			return Redirect::back()->withInput(Input::all())->withErrors($validator);
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
		$data = array(
			'territory'   => Territories::find($id),
			'last_worked' => TerritoriesSignInOut::lastWorked($id),
			'status'      => TerritoriesSignInOut::status($id),
			'signed_out'  => TerritoriesSignInOut::where('territory_id', '=', $id)
		                                        ->whereNull('signed_in')
		                                        ->first()
		);

		return View::make('admin.territories.show', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('admin.territories.edit')->withTerritory(Territories::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function update($id)
	{
		$errors = new MessageBag;

		if ('PUT' == Input::get('_method'))
		{
			$territory = Territories::find($id);

			$validator_rules = array(
				'label'        => 'required',
				'type_id'      => 'required',
				'area_type_id' => 'required',
				'map_embed_id' => 'required'
			);

			if ($territory->label != Input::get('label') AND $territory->map_embed_id != Input::get('map_embed_id'))
			{
				$validator_rules = array(
					'label'        => 'required|unique:territories',
					'type_id'      => 'required',
					'area_type_id' => 'required',
					'map_embed_id' => 'required|unique:territories'
				);
			}

			$validator = Validator::make(Input::all(), $validator_rules);

			if ($validator->passes())
			{
				try
				{
					$territory->label        = Input::get('label');
					$territory->type_id      = Input::get('type_id');
					$territory->area_type_id = Input::get('area_type_id');
					$territory->map_embed_id = Input::get('map_embed_id');
					$territory->notes        = Input::has('notes') ? Input::get('notes') : NULL;
					$territory->save();

					return Redirect::route('admin.territories.index')->with('success', Lang::get('territories.messages.update'));
				}
				catch (Exception $e)
				{
					$errors->add('message', Lang::get('territories.messages.update_error') . ': ' . $e->getMessage());
					return Redirect::back()->withInput(Input::all())->withErrors($errors);
				}
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
						Territories::find($id)->delete();
					}
				}

				$message = Lang::get('territories.messages.destroy_bulk');
			}
			else
			{
				Territories::find($id)->delete();
				$message = Lang::get('territories.messages.destroy');
			}

			return Response::json(array('status' => TRUE, 'message' => $message));
		}
		catch (Exception $e)
		{
			return Response::json(array('status' => FALSE, 'message' => Lang::get('territories.messages.destroy_error')));
		}
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
			'type'        => TerritoriesTypes::find($territory->type_id)->label,
			'area_type'   => TerritoriesAreaTypes::find($territory->area_type_id)->label,
		    'last_worked' => TerritoriesSignInOut::find($territory->id)->isEmpty() ? 'Never' : Utility::formatDate(TerritoriesSignInOut::find($territory->id)->signed_in),
		    'status'      => TerritoriesSignInOut::status($territory->id)
		);

		return Response::json($response);
	}

	/**
	 * Get all territory maps stored in db.
	 *
	 * @return mixed
	 */
	public function mapsList()
	{
		return Territories::select('id', 'label', 'map_embed_id')
						->where('map_embed_id', '!=', '')
						->get();
	}

}
