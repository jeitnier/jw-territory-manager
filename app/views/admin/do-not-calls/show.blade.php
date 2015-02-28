@extends('_templates.master')

@section('title')
{{ $do_not_call->first_name . ' ' . $do_not_call->last_name }} ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">
                            Do Not Call: <em>{{ $do_not_call->address }}</em>
                        </h1>

					</header>

					<section id="do-not-calls">

						@include('_helpers.back-button')

						<a href="{{ route('admin.do-not-calls.edit', $do_not_call->id) }}" class="btn btn-primary btn-lg mobile-button">
							<i class="fa fa-edit"></i> Edit Do Not Call
						</a>

						<a href="{{ route('admin.do-not-calls.print', $do_not_call->id) }}" class="btn btn-primary btn-lg mobile-button">
							<i class="fa fa-print"></i> Print Do Not Call
						</a>

						<hr>

						<div class="row">

							<div class="col-xs-12 col-sm-6 col-md-4">

								<div class="row">

									<div class="col-xs-6">

										<dl>
											@if ($do_not_call->address)
											<dt><h4>Address</h4></dt>
											<dd>{{ $do_not_call->address }}</dd>
											@endif

											@if ($do_not_call->city)
											<dt><h4>City</h4></dt>
											<dd>{{ $do_not_call->city }}</dd>
											@endif

											@if ($do_not_call->zip)
											<dt><h4>Zip</h4></dt>
											<dd>{{ $do_not_call->zip }}</dd>
											@endif
										</dl>

									</div>

									<div class="col-xs-6">

										<dl>
											<dt><h4>Territory</h4></dt>
											<dd>{{ $do_not_call->territory->label }}</dd>

											@if ($do_not_call->first_name)
											<dt><h4>Name</h4></dt>
											<dd>{{ $do_not_call->first_name . ' '. $do_not_call->last_name }}</dd>
											@endif

											@if ($do_not_call->notes)
											<dt><h4>Notes</h4></dt>
											<dd>{{ $do_not_call->notes }}</dd>
											@endif

											<dt><h4>Created On</h4></dt>
											<dd>{{ Utility::formatDate($do_not_call->created_at) }}</dd>
										</dl>

									</div>

								</div>

							</div>

							<div class="col-xs-12 col-sm-6 col-md-8">

								{{ HTML::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyBPD4_BbDi-aVOCS7A7AD4_nXeZ1xHRCWs') }}
								<script>
									var geocoder;
									var map;

									function initialize() {
										geocoder = new google.maps.Geocoder();
										var latlng = new google.maps.LatLng(-34.397, 150.644);
										var mapOptions = {
											zoom: 17,
											center: latlng
										}
										map = new google.maps.Map(document.getElementById('google-map'), mapOptions);

										codeAddress();
									}

									function codeAddress() {
										var address = '{{ $do_not_call->address . ' ' . $do_not_call->city . ' PA ' . $do_not_call->zip }}';

										geocoder.geocode( { 'address': address}, function(results, status) {
											if (status == google.maps.GeocoderStatus.OK) {
												console.log(map);
												map.setCenter(results[0].geometry.location);
												var marker = new google.maps.Marker({
													map: map,
													position: results[0].geometry.location
												});
											} else {
												console.log('Geocode was not successful for the following reason: ' + status);
											}
										});
									}

									google.maps.event.addDomListener(window, 'load', initialize);
								</script>

								<div id="google-map"></div>

							</div>

						</div>

					</section>

@stop