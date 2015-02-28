@if ($errors->count() > 0)
<div class="row">
	<div class="col-xs-12"><!-- col-xs-12 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4 -->
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
			<h4>Errors Found</h4>
			<ul>
				@foreach ($errors->all() AS $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>
@endif

@if(Session::has('success'))
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
			<h4>Success</h4>
			<p>{{ Session::get('success') }}</p>
		</div>
	</div>
</div>
<div class="clearfix"></div>
@endif

<div id="alert-box" class="row hide">
	<div class="col-xs-12">
		<div class="alert">
			<button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
			<h4>Notification</h4>
			<p></p>
		</div>
	</div>
</div>