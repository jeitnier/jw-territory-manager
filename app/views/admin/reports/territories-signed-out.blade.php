		<header>
			
			<h1 class="page-header">Territories Signed Out</h1>
			
		</header>

		<section>

			@if ( ! TerritoriesSignInOut::currentlySignedOut()->isEmpty())

			<table class="table table-bordered table-hover">

				<thead>

					<tr>

						<th>Territory</th>

						<th>Publisher</th>

						<th>Signed Out</th>

						<th>Due Date</th>

					</tr>

				</thead>

				<tbody>

					@foreach (TerritoriesSignInOut::currentlySignedOut() AS $signed_out)

					<tr>

						<td>
							<a href="{{ route('admin.territories.show', $signed_out->territory->id) }}">
								{{ $signed_out->territory->label }}
							</a>
						</td>

						<td>
							@if (NULL !== $signed_out->publisher)
							<a href="{{ route('admin.publishers.show', $signed_out->publisher->id) }}">
								{{ $signed_out->publisher->first_name . ' ' . $signed_out->publisher->last_name }}
							</a>
							@else
							Publisher removed.
							@endif
						</td>

						<td>{{ Utility::formatDate($signed_out->signed_out) }}</td>

						<td {{ Utility::isOverdue($signed_out->signed_out) ? ' class="text-danger"' : '' }}>
							{{ Utility::dueDate($signed_out->signed_out) }}
						</td>

					</tr>

					@endforeach

				</tbody>

			</table>

			@else

			<h3>@lang('reports.messages.list_empty')</h3>

			@endif

		</section>