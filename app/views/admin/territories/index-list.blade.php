@if ( ! $territories->isEmpty())

{{ $territories->links() }}

<div class="clearfix"></div>

<button id="territories-bulk-delete" class="btn btn-default" disabled><i class="fa fa-trash-o"></i> Delete Selected</button>

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

				<th>Territory #</th>

				<th>Type</th>

				<th>Area Type</th>

				<th>Date Added</th>

				<th width="1%"></th>

			</tr>

		</thead>

		<tbody>

			@foreach ($territories AS $territory)

			<tr data-id="{{ $territory['id'] }}" data-name="{{ $territory['label'] }}">

				<td>
					<label>
						<input type="checkbox" class="input-special">
						<span></span>
					</label>
				</td>

				<td><a href="{{ route('admin.territories.show', $territory['id']) }}">{{ $territory['label'] }}</a></td>

				<td>{{ TerritoriesTypes::where('id', '=', $territory['type_id'])->first()->label }}</td>

				<td>{{ TerritoriesAreaTypes::where('id', '=', $territory['area_type_id'])->first()->label }}</td>

				<td>{{ Utility::formatDate($territory['created_at']) }}</td>

				<td>
					<a class="delete" href="#"> <i class="fa fa-trash-o fa-lg"></i></a>
				</td>

			</tr>

			@endforeach

		</tbody>

	</table>

</div>

{{ $territories->links() }}

@else

<h3>@lang('territories.messages.list_empty')</h3>

@endif