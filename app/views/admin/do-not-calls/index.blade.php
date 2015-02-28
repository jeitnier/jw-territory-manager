@extends('_templates.master')

@section('title')
Manage Do Not Calls ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Manage Do Not Calls</h1>

					</header>

					<section id="do-not-calls">

						<a href="{{ route('admin.do-not-calls.create') }}" class="btn btn-primary btn-lg mobile-button">
							<i class="fa fa-plus-circle"></i> Add Do Not Call
						</a>

						<hr>

						@if ( ! DoNotCalls::all()->isEmpty())

						<button id="do-not-calls-bulk-delete" class="btn btn-default" disabled>
							<i class="fa fa-trash-o"></i> Delete Selected
						</button>

						<div class="table-responsive">

							<table class="table table-bordered table-hover">

								<thead>

									<tr>

										<th width="1%">
											<label id="select-all-label">
												<input type="checkbox" id="select-all" class="input-special">
												<span></span>
											</label>
										</th>

										<th>Territory</th>

										<th>Address</th>

										<th>Name</th>

										<th>Date Added</th>

										<th width="1%"></th>

									</tr>

								</thead>

								<tbody>

									@foreach (DoNotCalls::territoryAscending()->get() AS $do_not_call)

									<tr data-id="{{ $do_not_call->id }}" data-name="{{ $do_not_call->address }}">

										<td>
											<label>
												<input type="checkbox" class="input-special">
												<span></span>
											</label>
										</td>

										<td>
											<a href="{{ route('admin.territories.show', $do_not_call->territory->label) }}">
												{{ $do_not_call->territory->label }}
											</a>
										</td>

										<td><a href="{{ route('admin.do-not-calls.show', $do_not_call->id) }}">{{ $do_not_call->address }}</a></td>

										<td>{{ $do_not_call->first_name . ' ' . $do_not_call->last_name }}</td>

										<td>{{ Utility::formatDate($do_not_call->created_at) }}</td>

										<td>
											<a class="delete" href="#"><i class="fa fa-trash-o fa-lg"></i>
											</a>
										</td>

									</tr>

									@endforeach

								</tbody>

							</table>

						</div>

						@else

						<h3>@lang('donotcalls.messages.list_empty')</h3>

						@endif

					</section>

@stop