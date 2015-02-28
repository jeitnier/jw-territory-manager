@extends('_templates.master')

@section('title')
Login ::
@parent
@stop

@section('master-content')

					<div id="login" class="container">

						@include('_helpers.error-display')

						{{ Form::open(array(
							'route'  => 'auth.login',
							'method' => 'POST',
							'class'  => 'form-signin',
							'role'   => 'form'
						)) }}

							<h2 class="form-signin-heading">Please Sign In</h2>
							<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
							<input type="password" name="password" class="form-control" placeholder="Password" required>
							<label>
								<input type="checkbox" class="input-special" value="remember-me">
								<span> <span>Remember Me</span></span>
							</label>
							<button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>

						{{ Form::close() }}

					</div>

@overwrite