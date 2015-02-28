@extends('_templates.master')

@section('title')
Add Do Not Call ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Add Do Not Call</h1>

					</header>

					<section id="publishers">

						@if ( ! Territories::all()->isEmpty())

						<div class="row">

							{{ Form::open(array(
								'route' => 'admin.do-not-calls.store',
								'role'  => 'form',
								'class' => 'col-xs-12 col-sm-8'
							)) }}

								<div class="form-group">
									<label for="territory-id">Territory</label>
									<select id="territory-id" name="territory_id" class="form-control input-lg" required autofocus>
										@foreach (Territories::labelAscending()->get() AS $territory)
										<option value="{{ $territory->id }}">{{ $territory->label }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group">
									<label for="first-name">First Name</label>
									<input type="text" class="form-control input-lg" id="first-name" name="first_name">
								</div>

								<div class="form-group">
									<label for="last-name">Last Name</label>
									<input type="text" class="form-control input-lg" id="last-name" name="last_name">
								</div>

								<div class="form-group">
									<label for="address">Address</label>
									<input type="text" class="form-control input-lg" id="address" name="address">
								</div>

								<div class="form-group">
									<label for="city">City</label>
									<input type="text" class="form-control input-lg" id="city" name="city">
								</div>

								<div class="form-group">
									<label for="zip">Zip</label>
									<input type="text" class="form-control input-lg" id="zip" name="zip">
								</div>

								<div class="form-group">
									<label for="notes">Notes</label>
									<textarea class="form-control input-lg" id="notes" name="notes" rows="6"></textarea>
								</div>

								<div class="form-group">
									<a href="{{ route('admin.do-not-calls.index') }}" class="btn btn-default btn-lg"><i class="fa fa-arrow-circle-left"></i> Back</a>
									<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-plus-circle"></i> Add Do Not  Call</button>
								</div>

							{{ Form::close() }}

						</div>

						@else

						<h3>@lang('donotcalls.messages.territories_needed')</h3>

						@endif

					</section>

@stop