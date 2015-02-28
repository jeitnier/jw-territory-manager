@extends('_templates.master')

@section('title')
Show All Maps ::
@parent
@stop

{{ HTML::style('https://api.tiles.mapbox.com/mapbox.js/v1.6.4/mapbox.css') }}

@section('content')

					<header>

						<h1 class="page-header">Show All Maps</h1>

						<h4><em>Lists all maps created in MapBox</em></h4>

					</header>

					<section id="utilities" class="territory-resolver">

						<div class="row"></div>

					</section>

@section('js')
@parent
<script>
	$(function() {
		getAllMaps().done(function(maps) {
			$.each(maps, function(i, v) {
				$('#utilities .row').append(
					'<div class="col-xs-12 col-sm-6">' +
					'<label for="map-' + i + '">' + v.name + '</label>' +
					'<div id="map-' + i + '" class="map-container"></div>' +
					'</div>'
				);

				// create mapbox object
				var map = L.mapbox.map('map-' + i, v.id, {
					scrollWheelZoom: false
				});

				// create polygon layer and add to map from map's geojson
				var polygonLayer = L.mapbox.featureLayer().loadURL('https://a.tiles.mapbox.com/v4/' + v.id + '/features.json?access_token=pk.eyJ1IjoiZW5nbGVzaWRldGVycml0b3JpZXMiLCJhIjoiekFIU0NlayJ9.rE9XdicgXc9aIiXJ9yn68w').addTo(map);

				// after polygon layer has been added to map
				polygonLayer.on('ready', function() {

					// featureLayer.getBounds() returns the corners of the furthest-out markers,
					// and map.fitBounds() makes sure that the map contains these.
					map.fitBounds(polygonLayer.getBounds());
				});
			});
		});
	});
</script>
@stop

@stop