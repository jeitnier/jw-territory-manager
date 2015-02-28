@section('head')

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<i class="fa fa-bars"></i>
			</button>
			<a class="navbar-brand" href="{{ url('') }}">Territory Manager</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<?php if (Sentry::check()) : ?>
				<li><span>Logged in as: <em>{{ Sentry::getUser()->username }}</em></span></li>
				<?php if (Sentry::getUser()->hasAccess('admin')) : ?>
				<li{{ Request::is('user*') ? ' class="active"' : '' }}>
					<a href="{{ route('user.edit') }}">My Profile</a>
				</li>
				<li{{ Request::is('admin/settings*') ? ' class="active"' : '' }}>
					<a href="{{ route('admin.settings.edit') }}">Settings</a>
				</li>
				<?php endif; endif; ?>
				<li{{ Request::is('help*') ? ' class="active"' : '' }}>
					<a href="{{ url('help') }}">Help</a>
				</li>
				<?php if (Sentry::check()) : ?>
				<li>
					<a href="{{ route('auth.logout') }}">Logout</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>

@show