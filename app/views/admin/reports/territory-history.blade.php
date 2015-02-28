		<?php $territory = Territories::find($id); ?>

		<header>

			<h1 class="page-header">Territory History - {{ $territory->label }}</h1>

		</header>

		<section>

			@if ( ! $territory->history->isEmpty())

			<table class="table table-bordered table-hover">

				<thead>

					<tr>

						<th>Signed Out</th>

						<th>Signed In</th>

						<th>Publisher</th>

						<th>Campaign</th>

					</tr>

				</thead>

				<tbody>

					@foreach ($territory->history AS $territory)

					<tr>

						<td>{{ Utility::formatDate($territory->signed_out) }}</td>

						<td>{{ Utility::formatDate($territory->signed_in) }}</td>

						<td>
							@if (NULL !== $territory->publisher)
							{{ $territory->publisher->first_name . ' ' . $territory->publisher->last_name }}
							@else
							@lang('publishers.messages.removed')
							@endif
						</td>

						<td>{{ ucfirst($territory->campaign) }}</td>

					</tr>

					@endforeach

				</tbody>

			</table>

			@else

			<h3>@lang('territories.messages.never_worked')</h3>

			@endif

		</section>