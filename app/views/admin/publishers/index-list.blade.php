@if ( ! $publishers->isEmpty())

{{ $publishers->links() }}

<div class="clearfix"></div>

<button id="publishers-bulk-delete" class="btn btn-default" disabled><i class="fa fa-trash-o"></i> Delete Selected</button>

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

				<th>Name</th>

				<th>Date Added</th>

				<th width="1%"></th>

			</tr>

		</thead>

		<tbody>

			@foreach ($publishers AS $publisher)

			<tr data-id="{{ $publisher->id }}" data-name="{{ $publisher->first_name . ' ' . $publisher->last_name }}">

				<td>
					<label>
						<input type="checkbox" class="input-special">
						<span></span>
					</label>
				</td>

				<td>
					<a href="{{ route('admin.publishers.show', $publisher->id) }}">
						{{ $publisher->last_name . ', '. $publisher->first_name }}
					</a>
					{{ (bool) $publisher->activated ? '' : ' <em>(Inactive)</em>' }}
				</td>

				<td>{{ Utility::formatDate($publisher->created_at) }}</td>

				<td>
					<a class="delete" href="#"><i class="fa fa-trash-o fa-lg"></i>
					</a>
				</td>

			</tr>

			@endforeach

		</tbody>

	</table>

</div>

{{ $publishers->links() }}

@else

<h3>@lang('publishers.messages.list_empty')</h3>

@endif