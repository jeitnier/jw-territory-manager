@extends('admin.reports._templates.master')
@section('content')

		<header>
			
			<h1 class="page-header">Publisher Info</h1>
			
		</header>

		<section>

			<dl>
				<dt><h2>Name</h2></dt>
				<dd>{{ $publisher->first_name . ' '. $publisher->last_name }}</dd>

				<dt><h2>Username</h2></dt>
				<dd>{{ $publisher->username }}</dd>

				@if (UserPasswords::where('user_id', '=', $publisher->id)->get()->count() > 0)
				<dt><h2>Temporary Password</h2></dt>
				<dd class="text-primary">{{ UserPasswords::where('user_id', '=', $publisher->id)->first()->temp_password }}</dd>
				@endif

				<dt><h2>Created On</h2></dt>
				<dd>{{ Utility::formatDate($publisher->created_at) }}</dd>
			</dl>

		</section>

@stop