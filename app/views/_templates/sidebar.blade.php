@section('sidebar')
<?php if (Sentry::check()) : $user = Sentry::getUser(); ?>
<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
	<?php if ($user->hasAccess('admin')) : // for admins ?>
	<ul class="nav nav-sidebar">
		<li{{ Request::is('admin') ? ' class="active"' : '' }}>
			<a href="{{ route('admin.index') }}">
				<i class="fa fa-dashboard fa-fw"></i> Dashboard
			</a>
		</li>
	</ul>
	<ul class="nav nav-sidebar">
		<li><h3>Publishers</h3></li>
		<li{{ Request::is('admin/publishers*') ? ' class="active"' : '' }}>
			<a href="{{ route('admin.publishers.index') }}">
				<i class="fa fa-group fa-fw"></i> Manage Publishers
			</a>
		</li>
	</ul>
	<ul class="nav nav-sidebar">
		<li><h3>Territories</h3></li>
		<li{{ Request::is('admin/territories') ? ' class="active"' : '' }}>
			<a href="{{ route('admin.territories.index') }}">
				<i class="fa fa-home fa-fw"></i> Manage Territories
			</a>
		</li>
		<li{{ Request::is('admin/territories/sign-out*') ? ' class="active"' : '' }}>
			<a href="{{ route('admin.territories.sign-out.index') }}">
				<i class="fa fa-arrow-circle-o-left fa-fw"></i> Sign Out Territory
			</a>
		</li>
		<li{{ Request::is('admin/territories/sign-in*') ? ' class="active"' : '' }}>
			<a href="{{ route('admin.territories.sign-in') }}">
				<i class="fa fa-arrow-circle-o-right fa-fw"></i> Sign In Territory
			</a>
		</li>
		<li{{ Request::is('admin/do-not-calls*') ? ' class="active"' : '' }}>
			<a href="{{ route('admin.do-not-calls.index') }}">
				<i class="fa fa-ban fa-fw text-danger"></i> Do Not Calls
			</a>
		</li>
	</ul>
	<ul class="nav nav-sidebar">
		<li><h3>Reports</h3></li>
		<li{{ Request::is('admin/reports*') ? ' class="active"' : '' }}><a href="{{ route('admin.reports.index') }}"><i class="fa fa-print fa-fw"></i> Print Reports</a></li>
	</ul>
	<?php else : // for publishers ?>
	<ul class="nav nav-sidebar">
		<li><h3>Territories</h3></li>
		<li{{ Request::is('congregation/sign-out') ? ' class="active"' : '' }}>
		<a href="{{ route('congregation.sign-out.index') }}">
			<i class="fa fa-arrow-circle-o-left fa-fw"></i> Sign Out Territory
		</a>
		</li>
		<li{{ Request::is('congregation/sign-in') ? ' class="active"' : '' }}>
		<a href="{{ route('congregation.sign-in') }}">
			<i class="fa fa-arrow-circle-o-right fa-fw"></i> Sign In Territory
		</a>
		</li>
	</ul>
	<?php endif; ?>
	<ul class="nav nav-sidebar">
		<li><h3>Utilities</h3></li>
		<li{{ Request::is('utility/territory-resolver') ? ' class="active"' : '' }}><a href="{{ route('utility.territory-resolver') }}"><i class="fa fa-home fa-fw"></i> Find Territory By Address</a></li>
		<?php /*<li{{ Request::is('utility/all-maps') ? ' class="active"' : '' }}><a href="{{ route('utility.all-maps') }}"><i class="fa fa-map-marker fa-fw"></i> All Maps</a></li>*/ ?>
	</ul>
</div>
<?php endif; ?>
@show