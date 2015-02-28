@extends('_templates.master')

@section('title')
{{ $publisher->first_name . ' ' . $publisher->last_name }} ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Publisher: <em>{{ $publisher->first_name . ' ' . $publisher->last_name }}</em></h1>

					</header>

					<section id="publishers">

						<a href="{{ route('admin.publishers.index') }}" class="btn btn-default btn-lg">
							<i class="fa fa-arrow-circle-left"></i> Back
						</a>

						<a href="{{ route('admin.publishers.edit', $publisher->id) }}" class="btn btn-primary btn-lg">
							<i class="fa fa-edit"></i> Edit Publisher
						</a>

						<hr>

						<div class="row">

							<div class="col-xs-12">

								<div class="well">

									<h4>Territories Signed Out</h4>

									@if ($territories->count())
									<ul class="nav nav-pills nav-stacked">
										@foreach ($territories AS $key => $territory)
										<li>
											<a href="#signed-out-{{ $key }}" data-toggle="tab" data-map-embed-id="{{ $territory->map_embed_id }}" data-key="{{ $key }}">
												{{ $territory->label }}
											</a>
										</li>
										@endforeach
									</ul>

									<div class="tab-content">
										@foreach ($territories AS $key => $territory)
										<div class="tab-pane" id="signed-out-{{ $key }}">
											<hr>
											<div class="row">
												<div class="col-xs-6 col-sm-6">
													<h5>Signed Out</h5>
													{{ Utility::formatDate($territory->signed_out) }}
												</div>
												<div class="col-xs-6 col-sm-6">
													<h5>Due Date</h5>
													{{ Utility::dueDate($territory->signed_out) }}
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6 col-sm-6">
													<h5>Type</h5>
													{{ $territory->type }}
												</div>
												<div class="col-xs-6 col-sm-6">
													<h5>Area Type</h5>
													{{ $territory->area_type }}
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<h5>Map</h5>
													<div id="map-{{ $key }}" class="map-container"></div>
                                                    @if (0 == $key)
                                                    <script>
                                                        var map = L.mapbox.map('map-{{ $key }}', '{{ $territory->map_embed_id }}', {
                                                            accessToken: '{{ Options::get('mapbox_api_key') }}'
                                                        });
                                                        map.scrollWheelZoom.disable();
                                                    </script>
                                                    @endif
												</div>
											</div>
										</div>
										@endforeach
									</div>
									@else
									<h5>@lang('territories.messages.sign_out_publisher_empty')</h5>
									@endif

								</div>

							</div>

							<div class="col-xs-12">

								<div class="well">

									<h4>Territory History</h4>

									@if ( ! $publisher->history->isEmpty())
									<div class="row">

										@foreach ($publisher->history AS $history)

										<div class="col-xs-12 col-sm-6">

											<a href="{{ route('admin.territories.show', $history->territory->id) }}">
												{{ $history->territory->label }}
											</a>
											- Signed In:
											{{ ! empty(Utility::formatDate($history->signed_in))
												? Utility::formatDate($history->signed_in)
												: '<strong><em>In Progress</em></strong>'
											}}

										</div>

										@endforeach

									</div>
									@else
									<p>No History Available</p>
									@endif

								</div>

							</div>

						</div>

					</section>

@stop

@section('js')
    @parent
    <script>
        // trigger first tab on load
        $('a[data-toggle="tab"]:first').tab('show');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // don't try to create the same map instance
            if ($('#map-' + $(e.target).data('key')).html().length)
                return false;

            var map = L.mapbox.map('map-' + $(e.target).data('key'), $(e.target).data('map-embed-id'), {
                accessToken: '{{ Options::get('mapbox_api_key') }}'
            });
            map.setZoom(16);
            map.scrollWheelZoom.disable();
        })
    </script>
@stop