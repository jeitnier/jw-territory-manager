@extends('admin.reports._templates.master')

@section('content')

		<div id="content-frame" class="container">

			@include('_helpers.back-button')

			<a href="#" id="refresh" class="btn btn-primary btn-lg"><i class="fa fa-refresh"></i> Refresh Page</a>

			<div id="print-frame">

				<header>

					<div class="page-header">

						<div class="row">

							<div class="col-xs-12">
								<h1 style="margin: 0;">Territory Card: {{ $territory->label }}</h1>
							</div>

						</div>

						<div class="row">

							<div class="info col-xs-4">
								<h5 style="margin: 0;">Type: {{ $territory->territoryType->label }}</h5>
							</div>

							<div class="info col-xs-4">
								<h5 style="margin: 0;">Printed: {{ Carbon\Carbon::now()->format('m/d/Y') }}</h5>
							</div>

							<div class="info col-xs-4">
								<h5 style="margin: 0;">
									Due: {{ Utility::dueDate(
										TerritoriesSignInOut::where('territory_id', '=', $territory->id)
															->whereNotNull('signed_out')
															->whereNull('signed_in')
															->pluck('signed_out')
									) }}
								</h5>
							</div>

						</div>

					</div>

				</header>

				<!-- begin front page -->
				<section id="congregation-territory-card">

					<div class="row">

						<div class="col-xs-12">

                            <div id="static-map-container">

                                <img id="static-map" width="100%" src="">

                                @include('_helpers.legend')

                            </div>

						</div>

					</div>

				</section>
				<!-- end front page -->

				<!-- begin back page -->
				<section>

					<div class="row">

						<div class="col-xs-4">

							<dl>

								<dt><h3>Do Not Calls</h3></dt>
								<dd>
									@if ( ! $territory->doNotCalls->isEmpty())
									<ul>
										@foreach ($territory->doNotCalls AS $dnc)
										<li>
											@if (isset($dnc->address))
											<strong>{{ $dnc->address }}</strong><br>
											@endif
											@if (isset($dnc->city))
                                            {{ $dnc->city }}
                                            @endif
                                            @if (isset($dnc->zip))
                                            {{ $dnc->zip }}
                                            @endif
										</li>
										@endforeach
									</ul>
									@else
									@lang('donotcalls.messages.list_empty_print')
									@endif
								</dd>
							</dl>

						</div>

					</div>

					<div class="row">

						<div class="col-xs-12">

							<dl>
								<dt><h4>Notes / Comments / Not @ Homes / Do Not Calls</h4></dt>
								<dd class="notes">
									<hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr>
                                    <hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr>
								</dd>
							</dl>

						</div>

					</div>

				</section>
				<!-- end back page -->

			</div>

		</div>

@stop

@section('js')
@parent
{{ HTML::script('assets/js/printThis.js') }}
{{ HTML::script('https://api.tiles.mapbox.com/mapbox.js/plugins/geo-viewport/v0.1.1/geo-viewport.js') }}
{{ HTML::script('https://api.tiles.mapbox.com/mapbox.js/plugins/geojson-extent/v0.0.1/geojson-extent.js') }}
{{ HTML::script('https://api.tiles.mapbox.com/mapbox.js/v2.1.5/mapbox.js') }}
<script>
	$(function()
	{
        var access_token = 'pk.eyJ1IjoiZW5nbGVzaWRldGVycml0b3JpZXMiLCJhIjoiekFIU0NlayJ9.rE9XdicgXc9aIiXJ9yn68w';

        $.getJSON('http://api.tiles.mapbox.com/v4/{{ $territory->map_embed_id }}/features.json?access_token=' + access_token, function(geojson)
        {
            // set the map size
            var map_size = [1140, 1140];

            // get the outer boundaries of the given coordinates sets
            var bounds = geojsonExtent(geojson);

            // calculate the center coordinates & max zoom level to fit the features
            var viewport = geoViewport.viewport(bounds, map_size);

            // build the image
            var img  = 'https://api.tiles.mapbox.com/v4/';
                img += '{{ $territory->map_embed_id }}/';
                img += viewport.center[0].toFixed(4) + ',' + viewport.center[1].toFixed(4) + ',' + viewport.zoom + '/'; // longitude, latitude, zoom level
                img += '1280x1280';
                img += '@2x.png';
                img += '?access_token=' + access_token;

            // load the image source in the map container
            var static_map = $('#static-map');

            static_map.load(function() {
                try
                {
                    $("#print-frame").printThis({
                        debug: true // forces iFrame to show (fixes blank pages on iOS)
                    });
                }
                catch (e)
                {
                    console.log(e);
                }
            });
            static_map.attr('src', img);
        });

		$('#refresh').on('click', function(e)
		{
			e.preventDefault();

			window.location.reload();
		});
	});
</script>
@stop