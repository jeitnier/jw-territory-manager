		<header>
			
			<h1 class="page-header">Territories Overdue</h1>
			
		</header>

		<section>

			@if ( ! TerritoriesSignInOut::overdue()->isEmpty())

			<table class="table table-bordered table-hover">

				<thead>

					<tr>

						<th>Territory</th>

						<th>Publisher</th>

						<th>Signed Out</th>

						<th>Amount Overdue</th>

					</tr>

				</thead>

				<tbody>

					@foreach (TerritoriesSignInOut::overdue() AS $overdue)

					<tr>

						<td>
							<a href="{{ route('admin.territories.show', $overdue->territory_id) }}">
								{{ $overdue->label }}
							</a>
						</td>

						<td>
							@if (NULL !== $overdue->publisher)
							<a href="{{ route('admin.publishers.show', $overdue->publisher->id) }}">
								{{ $overdue->publisher->first_name . ' ' . $overdue->publisher->last_name }}
							</a>
							@else
							Publisher removed.
							@endif
						</td>

						<td>{{ Utility::formatDate($overdue->signed_out) }}</td>

						<td class="text-danger">{{ Utility::overdueAmount($overdue->signed_out, 'days') }}</td>

					</tr>

					@endforeach

				</tbody>

			</table>

			@else

			<h3>@lang('reports.messages.list_empty')</h3>

			@endif

		</section>