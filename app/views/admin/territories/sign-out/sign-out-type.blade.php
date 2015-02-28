@extends('_templates.master')

@section('title')
Sign Out {{ $type }} Territory ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Sign Out {{ $type }} Territory</h1>

					</header>

					<section id="territories-sign-out">

						@include('_helpers.back-button')

						<hr>

						@if ( ! $territories->isEmpty())

							@foreach ($territories AS $territory)

							<div class="sign-out-container">

								<label for="map-{{ $territory->label }}">
									<a href="{{ route('admin.territories.sign-out.create', $territory->id) }}">
										<h3>Sign Out {{ $territory->label }} <i class="fa fa-arrow-circle-right"></i></h3>
									</a>
								</label>

								<br>

								<div id="map-{{ $territory->label }}" class="map-container"></div>

                                <script>
                                    var map = L.mapbox.map('map-{{ $territory->label }}', '{{ $territory->map_embed_id }}', {
                                        accessToken: '{{ Options::get('mapbox_api_key') }}'
                                    });
                                    map.setZoom(16);
                                    map.scrollWheelZoom.disable();
                                </script>

								<div class="info">

									<div class="row">

										<div class="col-xs-4">
											<h3>Signed In Last</h3>
											<p>{{ Utility::formatDate($territory->signed_in) }}</p>
										</div>

										<div class="col-xs-4">
											<h3>Type</h3>
											<p>{{ TerritoriesTypes::where('id', '=', $territory->type_id)->first()->label }}</p>
										</div>

										<div class="col-xs-4">
											<h3>Area Type</h3>
											<p>{{ TerritoriesAreaTypes::where('id', '=', $territory->area_type_id)->first()->label }}</p>
										</div>

									</div>

								</div>

							</div>

							@endforeach

						@else

						<h3>@lang('territories.messages.sign_out_list_empty')</h3>

						@endif

					</section>

@stop