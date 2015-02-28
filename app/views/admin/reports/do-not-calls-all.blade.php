		<header>

			<h1 class="page-header">All Do Not Calls</h1>

		</header>

		<section>

			@if ( ! DoNotCalls::all()->isEmpty())

			<table class="table table-bordered table-hover">

				<thead>

					<tr>

						<th>Territory</th>

						<th>Address</th>

						<th>Name</th>

						<th>Date Added</th>

					</tr>

				</thead>

				<tbody>

					@foreach (DoNotCalls::all() AS $do_not_call)

					<tr>

						<td>{{ $do_not_call->territory->label }}</td>

						<td>
							{{ $do_not_call->address }}

							@if ($do_not_call->city)
							{{ ', ' . $do_not_call->city }}
							@endif

							@if ($do_not_call->zip)
							{{ ', ' . $do_not_call->zip }}
							@endif
						</td>

						<td>{{ $do_not_call->first_name . ' ' . $do_not_call->last_name }}</td>

						<td>{{ Utility::formatDate($do_not_call->created_at) }}</td>

					</tr>

					@endforeach

				</tbody>

			</table>

			@else

			<h3>@lang('donotcalls.messages.list_empty')</h3>

			@endif

		</section>