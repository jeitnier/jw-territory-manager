@extends('_templates.master')

@section('title')
Add Territory ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Add Territory</h1>

					</header>

					<section id="territories">

						<div class="row">

							{{ Form::open(array(
								'route' => 'admin.territories.store',
								'role'  => 'form',
								'class' => 'col-xs-12 col-sm-8'
							)) }}

								<div class="form-group">
									<label for="label">Territory Label <em>*</em></label>
									<input type="text" class="form-control input-lg" id="label" name="label" required autofocus>
								</div>

								<div class="form-group input-group-lg">
									<label for="territory-type">Territory Type <em>*</em></label>
									<select class="form-control" name="type_id">
										@foreach (TerritoriesTypes::all() AS $territory_type)
										<option value="{{ $territory_type->id }}">{{ $territory_type->label }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group input-group-lg">
									<label for="territory-area-type">Area Type <em>*</em></label>
									<select class="form-control" name="area_type_id">
										@foreach (TerritoriesAreaTypes::all() AS $territory_area_type)
										<option value="{{ $territory_area_type->id }}">{{ $territory_area_type->label }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group">
									<label for="map-embed-id">Map Embed ID <em>*</em></label>
									<input type="text" class="form-control input-lg" id="map-embed-id" name="map_embed_id" required>
								</div>

								<div class="form-group">
									<label for="notes">Notes</label>
									<textarea id="notes" class="form-control input-lg" name="notes" rows="10"></textarea>
								</div>

								<div class="form-group">

									<a href="{{ route('admin.territories.index') }}" class="btn btn-default btn-lg"><i class="fa fa-arrow-circle-left"></i> Back</a>
									<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-plus-circle"></i> Add
									                                                                                  Territory</button>
								</div>

							{{ Form::close() }}

						</div>

					</section>

@stop