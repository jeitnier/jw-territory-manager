		<header>

			<h1 class="page-header">Circuit Overseer Report (S-13)</h1>

		</header>

		<section>

			<?php $territories = $show ? Territories::paginate() : Territories::all(); ?>

			@if ( ! $territories->isEmpty())

			@foreach ($territories AS $territory)

			<table class="table table-bordered table-hover">

				<thead>

					<tr class="bg-info th-span">

						<th class="col-xs-4">Territory: <span>{{ $territory->label }}</span></th>

						<th class="col-xs-4">Type: <span>{{ $territory->territoryType->label }}</span></th>

						<th class="col-xs-4">Area Type: <span>{{ $territory->territoryAreaType->label }}</span></th>

					</tr>

					@if ( ! $territory->history->isEmpty())

					<tr>

						<th>Publisher</th>

						<th>Sign Out Date</th>

						<th>Sign In Date</th>

					</tr>

					@endif

				</thead>

				<tbody>

					@if ( ! $territory->history->isEmpty())

					@foreach ($territory->history AS $history)

					<tr>

						<td>
							@if (NULL !== $history->publisher)
							{{ $history->publisher->first_name . ' ' . $history->publisher->last_name }}
							@else
							@lang('publishers.messages.removed')
							@endif
						</td>

						<td>{{ Utility::formatDate($history->signed_out) }}</td>

						<td>{{ NULL !== $history->signed_in ? Utility::formatDate($history->signed_in) : '<strong><em>In Progress</em></strong>' }}</td>

					</tr>

					@endforeach

					@else

					<tr>

						<td colspan="3" class="text-center text-danger">
							<i class="fa fa-warning fa-lg"></i>
							@lang('territories.messages.never_worked')
						</td>

					</tr>

					@endif

				</tbody>

			</table>

			@endforeach

			{{ $show ? $territories->links() : '' }}

			@else

			<h3>@lang('reports.messages.list_empty')</h3>

			@endif

		</section>