		<header>

			<h1 class="page-header">Territories Over One Year Old</h1>

		</header>

		<section>

			@if ( ! TerritoriesSignInOut::overOneYear()->isEmpty())

			<table class="table table-bordered table-hover">

				<thead>

					<tr>

						<th>Territory</th>

						<th>Last Completed</th>

						<th>Being Worked Now?</th>

						<th>By Publisher</th>

					</tr>

				</thead>

				<tbody>

					@foreach (TerritoriesSignInOut::overOneYear() AS $overdue)

					<tr>

						<td>
							<a href="{{ route('admin.territories.show', $overdue->id) }}">
								{{ $overdue->label }}
							</a>
						</td>

						<td>
							@if (NULL !== TerritoriesSignInOut::lastWorked($overdue->id))
							{{ Utility::formatDate(TerritoriesSignInOut::lastWorked($overdue->id)->signed_in) }}
							@else
							<strong>Never Worked</strong>
							@endif
						</td>

						<td>
							@if (NULL !== TerritoriesSignInOut::beingWorked($overdue->id))
								@if (NULL === TerritoriesSignInOut::beingWorked($overdue->id)->signed_in)
								<i class="fa fa-check-circle fa-lg text-success"></i>
								@endif
							@else
							<i class="fa fa-times-circle fa-lg text-danger"></i>
							@endif
						</td>

						<td>
							@if (NULL !== TerritoriesSignInOut::whoIsWorking($overdue->id, ''))
							{{ TerritoriesSignInOut::whoIsWorking($overdue->id, '') }}
							@else
							N/A
							@endif
						</td>

					</tr>

					@endforeach

				</tbody>

			</table>

			@else

			<h3>@lang('reports.messages.list_empty')</h3>

			@endif

		</section>