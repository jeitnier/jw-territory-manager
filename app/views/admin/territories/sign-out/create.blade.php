@extends('_templates.master')

@section('title')
Sign Out Territory: {{ $territory->label }} ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Sign Out Territory: {{ $territory->label }}</h1>

					</header>

					<section id="territories">

						<div class="row">

							{{ Form::open(array(
								'route' => array('admin.territories.sign-out.store', $territory->id),
								'role'  => 'form'
							)) }}

								<input type="hidden" name="territory_id" value="{{ $territory->id }}">

								<div class="col-xs-12 col-sm-6">

									<div class="form-group">
										<label for="publisher-id">Publisher Name</label>
										<select id="publisher-id" class="form-control input-lg" name="publisher_id" autofocus>
											@foreach ($publishers AS $publisher)
											<option value="{{ $publisher->id }}" {{ $publisher->territory_count >= Options::get('number_territories_allowed') ? 'disabled' : '' }}>{{ $publisher->last_name . ', ' . $publisher->first_name }}</option>
											@endforeach
										</select>
										<br>
										<em><strong>Note:</strong> If a name is not selectable, he/she has the maximum amount of simultaneous territories <strong>({{ Options::get('number_territories_allowed') }})</strong> signed out currently.</em>
									</div>

									<div class="form-group">
										<label for="sign-out-date">Sign Out Date</label>
										<input type="datetime" id="sign-out-date" class="form-control input-lg" name="sign_out_date"
										       value="{{ Carbon\Carbon::now()->format('m/d/Y') }}" disabled>
									</div>

									<div class="form-group">
										<label for="due-date">Due Date</label>
										<input type="datetime" id="due-date" class="form-control input-lg" name="due_date"
										       value="{{ Carbon\Carbon::now()->addMonths(4)->format('m/d/Y') }}" disabled>
									</div>

								</div>

								<div class="col-xs-12 col-sm-6">

									<div class="well">

										<div class="form-group">
											<label for="type">Type</label>
											<input type="text" id="type" class="form-control input-lg" value="{{ TerritoriesTypes::where('id', '=', $territory->type_id)->first()->label }}" disabled>
										</div>

										<div class="form-group">
											<label for="area-type">Area Type</label>
											<input type="text" id="area-type" class="form-control input-lg" value="{{ TerritoriesAreaTypes::where('id', '=', $territory->area_type_id)->first()->label }}" disabled>
										</div>

									</div>

								</div>

								<div class="col-xs-12">

									<h4>Map</h4>
									<div id="map" class="map-container"></div>

								</div>

								<div class="col-xs-12">

									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-lg">
											<i class="fa fa-chevron-circle-left"></i> Sign Out
										</button>
									</div>

								</div>

							{{ Form::close() }}

						</div>

					</section>

@stop

@section('js')
    @parent
    <script>
        var map = L.mapbox.map('map', '{{ $territory->map_embed_id }}', {
            accessToken: '{{ Options::get('mapbox_api_key') }}'
        });
        map.setZoom(16);
        map.scrollWheelZoom.disable();
    </script>
@stop