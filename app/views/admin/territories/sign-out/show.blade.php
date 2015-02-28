@extends('_templates.master')

@section('title')
{{ $territory->label }} ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Territory: <em>{{ $territory->label }}</em></h1>

					</header>

					<section id="territories">

						<a href="{{ route('admin.territories.sign-out.index') }}" class="btn btn-default btn-lg"><i class="fa fa-arrow-circle-left"></i> Back to Territories Sign-Out List</a>

						<a href="{{ route('admin.territories.sign-out.print', $territory->id) }}" class="btn btn-primary btn-lg"><i class="fa fa-print"></i> Print Territory Card</a>

						<button id="email-button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#email-modal" data-redirect="/admin/territories/sign-out/{{ $territory->id }}">
							<i class="fa fa-share"></i> Email Territory Card
						</button>

						<hr>

						<div class="row">

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
    <script>
        var map = L.mapbox.map('map', '{{ $territory->map_embed_id }}', {
            accessToken: '{{ Options::get('mapbox_api_key') }}'
        });
        map.scrollWheelZoom.disable();
    </script>
@stop