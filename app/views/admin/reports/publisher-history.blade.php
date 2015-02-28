		<?php $publisher = User::find($id); ?>

		<header>

			<h1 class="page-header">Publisher History - {{ $publisher->first_name . ' ' . $publisher->last_name }}</h1>

		</header>

		<section>

			@if ( ! $publisher->history->isEmpty())

			<table class="table table-bordered table-hover">

				<thead>

					<tr>

						<th>Territory</th>

						<th>Signed Out</th>

						<th>Signed In</th>

						<th>Due Date</th>

						<th>Campaign</th>

					</tr>

				</thead>

				<tbody>

					@foreach ($publisher->history AS $history)

					<tr>

						<td>{{ $history->territory->label }}</td>

						<td>{{ Utility::formatDate($history->signed_out) }}</td>

						<td>{{ Utility::formatDate($history->signed_in) }}</td>

						<td>{{ Utility::dueDate($history->signed_out) }}</td>

						<td>{{ ucfirst($history->campaign) }}</td>

					</tr>

					@endforeach

				</tbody>

			</table>

			@else

			<h3>@lang('publishers.messages.no_history')</h3>

			@endif

		</section>