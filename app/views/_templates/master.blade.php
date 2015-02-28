<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			@section('title')
			Territory Manager
			@show
		</title>

        <link rel="icon" href="{{ asset('assets/favicon.ico') }}">

		{{ HTML::script('https://api.tiles.mapbox.com/mapbox.js/v2.1.5/mapbox.js') }}
		{{ HTML::script('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-pip/v0.0.2/leaflet-pip.js') }}

		@section('css')
		<!-- css -->
		{{ HTML::style('assets/css/dashboard.css') }}
		{{ HTML::style('assets/css/font-awesome.min.css') }}
		{{ HTML::style('https://api.tiles.mapbox.com/mapbox.js/v2.1.5/mapbox.css') }}
		@show

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		@include('_templates.head')

		@section('master-content')

		<div class="container-fluid">

			<div class="row row-offcanvas row-offcanvas-left">

				@include('_templates.sidebar')

				<div class="col-xs-12 col-sm-9 main">

					<!--toggle sidebar button-->
					<p class="visible-xs">
						<button type="button" class="btn btn-primary" data-toggle="offcanvas">
							<i class="fa fa-arrow-circle-left fa-lg"></i> Expand Menu
						</button>
					</p>

					@include('_helpers.error-display')

					@yield('content')

				</div>

			</div>

		</div>

		@show

		@section('js')
		<!-- js -->
		{{ HTML::script('assets/js/jquery-1.11.0.min.js') }}
		{{ HTML::script('assets/js/bootstrap.min.js') }}
		{{ HTML::script('assets/js/dashboard.js') }}
		@show
	</body>
</html>