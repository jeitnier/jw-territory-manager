@extends('_templates.master')

@section('title')
Admin Dashboard ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Dashboard</h1>

					</header>

					<section id="dashboard">

						<div class="row">

							<div class="col-xs-12">

								<h3>
									Territories Due Soon or Overdue
									<span class="label label-danger">{{ count(TerritoriesSignInOut::dueSoon()) }}</span>
								</h3>

								@if ( ! TerritoriesSignInOut::dueSoon()->isEmpty())

								<div class="table-responsive">

									<table class="table table-bordered table-hover">

										<thead>

											<tr>

												<th>Territory #</th>

												<th>Due Date</th>

												<th>Status</th>

												<th>Publisher</th>

											</tr>

										</thead>

										<tbody>

											@foreach (TerritoriesSignInOut::dueSoon() AS $territory)

											<tr>

												<td>
													<a href="{{ route('admin.territories.show', $territory->id) }}">
														{{ $territory->label }}
													</a>
												</td>

												<td>{{ Utility::dueDate($territory->signed_out) }}</td>

												<td>
													{{ $territory->isOverdue($territory->signed_out)
														? '<span class="text-danger"><strong>OVERDUE</strong></span>'
														: '<span class="text-warning"><strong>DUE SOON</strong></span>'
													}}
												</td>

												<td>{{ TerritoriesSignInOut::whoIsWorking($territory->id, '') }}</td>

											</tr>

											@endforeach

										</tbody>

									</table>

								</div>

								@else

                                <h4>@lang('territories.messages.none_due_soon_or_overdue')</h4>

                                @endif

							</div>

						</div>

						<div class="row">

							<div class="col-xs-12">

								<h3>
									Territories Being Worked
									<span class="label label-danger">{{ count(TerritoriesSignInOut::currentlySignedOut()) }}</span>
								</h3>

								@if (count(TerritoriesSignInOut::currentlySignedOut()))

								<div class="table-responsive">

									<table class="table table-bordered table-hover">

										<thead>

											<tr>

												<th>Territory #</th>

												<th>Due Date</th>

												<th>Publisher</th>

											</tr>

										</thead>

										<tbody>

											@foreach (TerritoriesSignInOut::currentlySignedOut() AS $territory)

											<tr>

												<td>
													<a href="{{ route('admin.territories.show', $territory->id) }}">
                                                        {{ $territory->label }}
                                                    </a>
                                                </td>

												<td>{{ Utility::formatDate($territory->signed_out) }}</td>

												<td>
													@if (NULL !== $territory->publisher)
													<a href="{{ route('admin.publishers.show', $territory->publisher_id) }}">
														{{ $territory->publisher->first_name . ' ' . $territory->publisher->last_name }}
													</a>
													@else
													Removed
													@endif
												</td>

											</tr>

											@endforeach

										</tbody>

									</table>

								</div>

								@else

								<h4>@lang('territories.messages.none_signed_out')</h4>

								@endif

							</div>

						</div>

						<div class="row">

							<div class="col-xs-12 col-sm-12 col-md-6">

								<h3>
									Territories Completed This Week
									<span class="label label-danger">{{ count(TerritoriesSignInOut::completedWithin('week')) }}</span>
								</h3>

								@if (count(TerritoriesSignInOut::completedWithin('week')))

								<div class="table-responsive">

                                    <table class="table table-bordered table-hover">

                                        <thead>

                                            <tr>

                                                <th>Territory #</th>

                                                <th>Signed In</th>

                                                <th>Publisher</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach (TerritoriesSignInOut::completedWithin('week') AS $completed)

                                            <tr>

                                                <td>
                                                    <a href="{{ route('admin.territories.show', $completed->id) }}">
                                                        {{ $completed->label }}
                                                    </a>
                                                </td>

                                                <td>{{ Utility::formatDate($completed->signed_in) }}</td>

                                                <td>
                                                    <a href="{{ route('admin.publishers.show', $completed->publisher_id) }}">
                                                        {{ $completed->publisher->first_name . ' ' . $completed->publisher->last_name }}
                                                    </a>
                                                </td>

                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

								@else

								<h4>@lang('territories.messages.none_completed_week')</h4>

								@endif

							</div>

							<div class="col-xs-12 col-sm-12 col-md-6">

								<h3>
									Territories Completed This Month
									<span class="label label-danger">{{ count(TerritoriesSignInOut::completedWithin('month')) }}</span>
								</h3>

								@if (count(TerritoriesSignInOut::completedWithin('month')))

								<div class="table-responsive">

                                    <table class="table table-bordered table-hover">

                                        <thead>

                                            <tr>

                                                <th>Territory #</th>

                                                <th>Signed In</th>

                                                <th>Publisher</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach (TerritoriesSignInOut::completedWithin('month') AS $completed)

                                            <tr>

                                                <td>
                                                    <a href="{{ route('admin.territories.show', $completed->id) }}">
                                                		{{ $completed->label }}
                                                	</a>
                                                </td>

                                                <td>{{ Utility::formatDate($completed->signed_in) }}</td>

                                                <td>
                                                    <a href="{{ route('admin.publishers.show', $completed->publisher_id) }}">
                                                        {{ $completed->publisher->first_name . ' ' . $completed->publisher->last_name }}
                                                    </a>
                                                </td>

											</tr>

											@endforeach

                                        </tbody>

                                    </table>

                                </div>

                                @else

                                <h4>@lang('territories.messages.none_completed_month')</h4>

                                @endif

							</div>

						</div>

					</section>

@stop