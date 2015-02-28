@extends('admin.reports._templates.master')

@section('content')

			<div style="margin: 20px;">

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

								@include('_helpers.legend')

								<iframe width="100%" height="580px" frameBorder="0" src="https://a.tiles.mapbox.com/v4/{{ $territory->map_embed_id }}/attribution,zoompan.html?access_token={{ Options::get('mapbox_api_key') }}"></iframe>

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
											@if (isset($dnc->city)) {{ $dnc->city }} @endif @if (isset($dnc->zip)) {{ $dnc->zip }} @endif
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

						<div class="col-xs-5">

							<dl>
								<dt><h4>Notes / Comments</h4></dt>
								<dd class="notes">
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
								</dd>
							</dl>

						</div>

						<div class="col-xs-5 col-xs-offset-1">

							<dl>
								<dt><h4>Not at Home</h4></dt>
								<dd class="notes">
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
									<hr>
								</dd>
							</dl>

						</div>

					</div>

				</section>
				<!-- end back page -->

			</div>

@section('js')
@parent
<script>
	$(function() {
		var grabber = 'http://ss.api.eitnier.com/grabber?url=https://a.tiles.mapbox.com/v4/{{ $territory->map_embed_id }}/attribution.html?access_token={{ Options::get('mapbox_api_key') }}&width=1440&height=1080';

		/**
		 * Hit the Screenshot API and get the response.
		 */
		$.getJSON(grabber, function(response) {
			if (response.status) {
				checkProcessStatus(response);
			} else {
				console.log(response.message);
			}
		});

		/**
		 * Poll for the status of the image capture.
		 */
		function checkProcessStatus(params) {
			var checkStatus = function() {
				if (params.pid) {
					$.getJSON('http://ss.api.eitnier.com/status/' + params.pid, function(response) {
						if (false == response.status) {
							displayImage(params.url);
						} else {
							setTimeout(checkStatus, 0); // check every millisecond for results
						}
					});
				} else {
					displayImage(params.url);
				}
			};

			checkStatus();
		}

		/**
		 * Insert the image URL into the element's src attribute.
		 *
		 * @param url
		 */
		function displayImage(url) {
			$('#loader').remove();
			$('#static-map-source').attr('src', url);
			$('#static-map-container').css('display', 'block');
		}

	});
</script>
@stop

@stop