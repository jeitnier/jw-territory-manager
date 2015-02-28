@extends('_templates.master')

@section('title')
Edit {{ $publisher->first_name . ' ' . $publisher->last_name }} ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Publisher: <em>{{ $publisher->first_name . ' ' . $publisher->last_name }}</em></h1>

					</header>

					<section id="publishers">

						{{ Form::model($publisher, [
							'route'  => ['admin.publishers.update', $publisher->id],
							'method' => 'PUT',
							'role'   => 'form',
							'class'  => 'col-xs-12 col-sm-8'
						]) }}

							<div class="form-group">
								<label for="first-name">First Name</label>
								{{ Form::text('first_name', NULL, ['id' => 'first-name', 'class' => 'form-control input-lg']) }}
							</div>

							<div class="form-group">
								<label for="last-name">Last Name</label>
								{{ Form::text('last_name', NULL, ['class' => 'form-control input-lg']) }}
							</div>

							<div class="form-group">
								<label>Is Publisher Active?</label>
								<div class="clearfix"></div>
								<div class="row indent-left">
									<div class="col-xs-12">
										<label>
											<input type="radio" class="input-special" name="activated"
											value="1" {{ (bool) $publisher->activated ? 'checked' : '' }}>
											<span> Yes</span>
										</label>
										<br>
										<label>
											<input type="radio" class="input-special" name="activated"
											value="0" {{ (bool) $publisher->activated ? '' : 'checked' }}>
											<span> No</span>
										</label>
									</div>
								</div>

							</div>

							<div class="form-group">
								<a href="{{ route('admin.publishers.show', $publisher->id) }}" class="btn btn-default btn-lg">
									<i class="fa fa-arrow-circle-left"></i> Cancel
								</a>
								<button type="submit" class="btn btn-primary btn-lg">
									<i class="fa fa-check-circle"></i> Update Publisher
								</button>
							</div>

						{{ Form::close() }}

					</section>

@stop