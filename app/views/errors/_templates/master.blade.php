<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>
			@section('title')
			RankPop | rundown.io BETA
			@show
		</title>
		
		<!-- css -->
		<link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
		{{ HTML::style('css/custom.css') }}
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css') }}
		
		<!-- js -->
		{{ HTML::script('//code.jquery.com/jquery-1.10.1.min.js') }}
		{{ HTML::script('js/bootstrap.js') }}
		{{ HTML::script('js/bootstrap-datepicker.js') }}
		{{ HTML::script('js/modernizr.min.js') }}
		{{ HTML::script('js/ajax-cache.js') }}
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	      <script src="../../assets/js/html5shiv.js"></script>
	      <script src="../../assets/js/respond.min.js"></script>
	    <![endif]-->
		
		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('ico/apple-touch-icon-144-precomposed.png') }}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('ico/apple-touch-icon-114-precomposed.png') }}">
		  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('ico/apple-touch-icon-72-precomposed.png') }}">
		                <link rel="apple-touch-icon-precomposed" href="{{ asset('ico/apple-touch-icon-57-precomposed.png') }}">
		                               <link rel="shortcut icon" href="{{ asset('ico/favicon.png') }}">
	</head>
	
	<body id="errors">
	
		<div class="container-liquid">
			<div class="col-xs-12">
				<div class="logo">
					<img class="img-responsive" src="{{ asset('img/errors-logo.png') }}">
				</div>
				<div class="content">
					@yield('content')
				</div>
			</div><!--/span-->
		</div><!--/.container-->
	
		<!-- javascript -->
		{{ HTML::script('js/custom.js') }}
		@if ('production' == $app->environment())
		
		<!-- google analytics -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-46563024-1', 'rundown.io');
			ga('send', 'pageview');
		</script>
		@endif
	
	</body>
</html>
