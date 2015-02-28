<!DOCTYPE html>
<html lang="en">
	<head>
		@section('css')
		<!-- css -->
		{{ HTML::style('assets/css/dashboard.css') }}
		{{ HTML::style('assets/css/font-awesome.min.css') }}
		<style>
			body {
				background-color: #fff;
				padding-top: 0;
			}
			.page-header { margin-top: 20px; }
		</style>
		@show
	</head>
	<body>

		@yield('content')

		@section('js')
		<!-- js -->
		{{ HTML::script('assets/js/jquery-1.11.0.min.js') }}
		@show

	</body>
</html>