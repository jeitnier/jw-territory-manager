@extends('_templates.master')

@section('title')
Edit Profile ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Edit Profile</h1>

					</header>

					<section id="user">

						<div class="row">

							{{ Form::open([
								'route'  => 'user.update',
								'method' => 'PUT',
								'role'   => 'form',
								'class'  => 'col-xs-12 col-sm-8'
							]) }}

								<div class="form-group">
									<label for="old-password">Old Password</label>
									<input type="password" class="form-control input-lg" id="old-password" name="old-password">
								</div>

								<div class="form-group">
									<label for="new-password">New Password</label>
									<input type="password" class="form-control input-lg" id="new-password" name="new-password">
								</div>

								<div class="form-group">
									<label for="new-password-confirm">Confirm New Password</label>
									<input type="password" class="form-control input-lg" id="new-password-confirm" name="new-password-confirm">
								</div>

								<div class="form-group">
									<button class="btn btn-primary btn-lg" type="submit">
										<i class="fa fa-check-circle"></i> Update Password
									</button>
								</div>

							{{ Form::close() }}

						</div>

					</section>

@stop