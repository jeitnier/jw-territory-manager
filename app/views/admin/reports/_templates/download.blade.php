<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=US-ASCII">
		@section('css')
		<!-- css -->
		{{ HTML::style('assets/css/dashboard.css') }}
		{{ HTML::style('assets/css/font-awesome.min.css') }}
		@show
	</head>
	<body style="padding-top: 0; background-color: #fff;">

		{{ $html }}

	</body>
</html>