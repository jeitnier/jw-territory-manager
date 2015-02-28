@extends('_templates.master')

@section('title')
Add Publisher ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Add Publisher</h1>

					</header>

					<section id="publishers">

						<div class="row">

							{{ Form::open(array(
								'route' => 'admin.publishers.store',
								'role'  => 'form',
								'class' => 'col-xs-12 col-sm-8'
							)) }}

								<div class="form-group">
									<label for="first-name">First Name</label>
									<input type="text" class="form-control input-lg" id="first-name" name="first_name" required autofocus>
								</div>

								<div class="form-group">
									<label for="last-name">Last Name</label>
									<input type="text" class="form-control input-lg" id="last-name" name="last_name" required>
								</div>

								<div class="form-group">
									<a href="{{ route('admin.publishers.index') }}" class="btn btn-default btn-lg"><i class="fa fa-arrow-circle-left"></i> Back</a>
									<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-plus-circle"></i> Add
									                                                                                  Publisher</button>
								</div>

							{{ Form::close() }}

						</div>

					</section>

@stop