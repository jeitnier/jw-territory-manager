@extends('_templates.master')

@section('title')
Territory {{ $territory->label }} ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Territory: <em>{{ $territory->label }}</em></h1>

					</header>

					<section id="territories">

						@include('_helpers.back-button', ['route' => 'admin.territories.index'])

						<a href="{{ route('admin.territories.edit', $territory->id) }}" class="btn btn-primary btn-lg mobile-button">
							<i class="fa fa-edit"></i> Edit Territory
						</a>

						<a href="{{ route('admin.territories.sign-out.print', $territory->id) }}" class="btn btn-primary btn-lg mobile-button">
							<i class="fa fa-print"></i> Print Territory
						</a>

						<button class="btn btn-primary btn-lg mobile-button" data-toggle="modal"
							data-target="#email-modal" data-redirect="admin/territories/{{ $territory->id }}">
							<i class="fa fa-envelope"></i> Email Territory
						</button>

						<hr>

						<div class="row">

							<div class="col-xs-12 col-sm-6">

								<dl>
									<dt><h4>Date of Last Sign-In</h4></dt>
									<dd>{{ NULL !== $last_worked ? Utility::formatDate($last_worked->signed_in) : 'N/A' }}</dd>
								</dl>

								<dl>
									<dt><h4>Status</h4></dt>
									<dd>{{ $status }}</dd>
								</dl>

								@if ('In Progress' == $status)
								<dl>
									<dt><h4>Due Date</h4></dt>
									<dd>
										{{ Utility::dueDate($signed_out->signed_out) }}
									</dd>
								</dl>
								@endif

							</div>

							<div class="col-xs-12 col-sm-6">

								<dl>
									<dt><h4>Type</h4></dt>
									<dd>{{ $territory->territoryType->label }}</dd>
								</dl>

								<dl>
									<dt><h4>Area Type</h4></dt>
									<dd>{{ $territory->territoryAreaType->label }}</dd>
								</dl>

							</div>

							<div class="clearfix"></div>

							<div class="col-xs-12">

								<div id="map" class="map-container"></div>

							</div>

							@if (NULL !== $territory->notes)
							<div class="col-xs-12">

								<dl>
									<dt><h4>Notes</h4></dt>
									<p>{{ $territory->notes }}</p>
								</dl>

							</div>
							@endif

							@if ( ! $territory->territoryNotes->isEmpty())
							<div class="col-xs-12">

								<dl>
									<dt><h4>Comments from Publishers</h4></dt>
									@foreach ($territory->territoryNotes AS $note)
									<dd>{{ $note->note . ' - ' . Utility::formatDate($note->signed_in) }}</dd>
									@endforeach
								</dl>

							</div>
							@endif

						</div>

					</section>

					@include('_helpers.email-territory-modal')

@stop

@section('js')
    @parent
    {{ HTML::script('https://api.tiles.mapbox.com/mapbox.js/plugins/geo-viewport/v0.1.1/geo-viewport.js') }}
    {{ HTML::script('https://api.tiles.mapbox.com/mapbox.js/plugins/geojson-extent/v0.0.1/geojson-extent.js') }}
    <script>
        createMapboxMap('map', '{{ $territory->map_embed_id }}', [1039, 500], '{{ Options::get('mapbox_api_key') }}');
    </script>
@stop