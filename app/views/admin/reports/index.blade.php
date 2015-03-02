@extends('_templates.master')

@section('css')
@parent
{{ HTML::style('assets/css/bootstrap-multiselect.min.css') }}
@stop

@section('content')

				<header>

					<h1 class="page-header">Reports</h1>

				</header>

				<section id="reports">

					<h5>Select Reports to Download:</h5>

					{{ Form::open(array(
						'route' => 'admin.reports.generate',
						'role'  => 'form'
					)) }}

						<div class="clearfix">
							<label>
								<input type="checkbox" id="select-all" class="input-special">
								<span> <strong>Select / De-Select All</strong></span>
							</label>
						</div>

						<hr>

						<div class="clearfix">
							<label>
								<input type="checkbox" class="input-special" value="true" name="reports[circuit-overseer]">
								<span> Circuit Overseer Report (S-13)</span>
								&nbsp;<a href="{{ route('admin.reports.show', 'circuit-overseer') }}"><i class="fa fa-desktop fa-lg" title="View"
								></i></a>
							</label>
						</div>

						<div class="clearfix">
							<label>
								<input type="checkbox" class="input-special" value="true" name="reports[territories-signed-out]">
								<span> Territories Currently Signed Out</span>
								&nbsp;<a href="{{ route('admin.reports.show', 'territories-signed-out') }}"><i class="fa fa-desktop fa-lg" title="View"></i></a>
							</label>
						</div>

						<div class="clearfix">
							<label>
								<input type="checkbox" class="input-special" value="true" id="publisher-history" name="reports[publisher-history]">
								<span> Publisher History</span>
							</label>

							<div class="form-group indent">
								<select id="publisher-id" class="form-control input-lg hidden" name="reports[publisher-history][]" multiple="multiple">
									@foreach ($publishers AS $publisher)
									<option value="{{ $publisher->id }}">{{ $publisher->first_name . ' ' . $publisher->last_name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="clearfix">
							<label>
								<input type="checkbox" class="input-special" value="true" id="territory-history" name="reports[territory-history]">
								<span> Territory History</span>
							</label>

							<div class="form-group indent">
								<select id="territory-id" class="form-control input-lg" name="reports[territory-history][]" multiple="multiple">
									@foreach (Territories::labelAscending() AS $territory)
									<option value="{{ $territory->id }}">{{ $territory->label }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="clearfix">
							<label>
								<input type="checkbox" class="input-special" value="true" name="reports[territories-over-one-year]">
								<span> Territories Over a Year Old</span>
								&nbsp;<a href="{{ route('admin.reports.show', 'territories-over-one-year') }}"><i class="fa fa-desktop fa-lg" title="View"></i></a>
							</label>
						</div>

						<div class="clearfix">
							<label>
								<input type="checkbox" class="input-special" value="true" name="reports[territories-overdue]">
								<span> Territories Overdue</span>
								&nbsp;<a href="{{ route('admin.reports.show', 'territories-overdue') }}"><i class="fa fa-desktop fa-lg" title="View"></i></a>
							</label>
						</div>

						<div class="clearfix">
							<label>
								<input type="checkbox" class="input-special" value="true" name="reports[do-not-calls-all]">
								<span> All Do Not Calls</span>
							</label>
						</div>

						<br>

						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-download"></i> Download Reports</button>
						</div>

					{{ Form::close() }}

					{{--
					<div class="well">

						<h4>Legend</h4>

						<i class="fa fa-eye fa-lg"></i> Open Report

					</div>
					--}}

				</section>

@stop

@section('js')
@parent
{{ HTML::script('assets/js/bootstrap-multiselect.js') }}
<script>
	var multiselect_options = {
		buttonClass            : 'btn btn-default',
		enableFiltering        : true,
		includeSelectAllOption : true
	};

	// initialize multiselects and immediately hide them
	$('#publisher-id').multiselect(multiselect_options).next().addClass('hidden');
	$('#territory-id').multiselect(multiselect_options).next().addClass('hidden');
</script>
@stop