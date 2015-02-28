@extends('_templates.master')

@section('title')
Edit Do Not Call ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Do Not Call: <em>{{ $do_not_call->first_name . ' ' . $do_not_call->last_name }}</em></h1>

					</header>

					<section id="publishers">

						{{ Form::model($do_not_call, array(
							'route'  => array('admin.do-not-calls.update', $do_not_call->id),
							'method' => 'PUT',
							'role'   => 'form',
							'class'  => 'col-xs-12 col-sm-8'
						)) }}

							<div class="form-group input-group-lg">
								<label for="territory-id">Territory</label>
								{{ Form::select('territory_id', Territories::all()->lists('label', 'id'), $do_not_call->territory_id, array('id' => 'territory-id', 'class' => 'form-control')) }}
							</div>

							<div class="form-group">
								<label for="first-name">First Name</label>
								{{ Form::text('first_name', NULL, array('id' => 'first-name', 'class' => 'form-control input-lg')) }}
							</div>

							<div class="form-group">
								<label for="last-name">Last Name</label>
								{{ Form::text('last_name', NULL, array('id' => 'last-name', 'class' => 'form-control input-lg')) }}
							</div>

							<div class="form-group">
								<label for="address">Address</label>
								{{ Form::text('address', NULL, array('id' => 'address', 'class' => 'form-control input-lg')) }}
							</div>

							<div class="form-group">
								<label for="city">City</label>
								{{ Form::text('city', NULL, array('id' => 'city', 'class' => 'form-control input-lg')) }}
							</div>

							<div class="form-group">
								<label for="zip">Zip</label>
								{{ Form::text('zip', NULL, array('id' => 'zip', 'class' => 'form-control input-lg')) }}
							</div>

							<div class="form-group">
								<label for="notes">Notes</label>
								{{ Form::textarea('notes', NULL, array('id' => 'first-notes', 'class' => 'form-control input-lg',
								'rows' => '6')) }}
							</div>

							<div class="form-group">
								<a href="{{ route('admin.do-not-calls.show', $do_not_call->id) }}" class="btn btn-default btn-lg">
									<i class="fa fa-arrow-circle-left"></i> Cancel
								</a>
								<button type="submit" class="btn btn-primary btn-lg">
									<i class="fa fa-check-circle"></i> Update Do Not Call
								</button>
							</div>

						{{ Form::close() }}

					</section>

@stop