<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie ie7 lte9 lte8 lte7" lang="en-US"><![endif]-->
<!--[if IE 8]><html class="ie ie8 lte9 lte8" lang="en-US">	<![endif]-->
<!--[if IE 9]><html class="ie ie9 lte9" lang="en-US"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="noIE" lang="en-US">
<!--<![endif]-->
<head>
	<title>Lanjalan</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no"/>
	<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=PT+Sans'>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,bold"/>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Alegreya+Sans:regular,italic,bold,bolditalic"/>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Nixie+One:regular,italic,bold,bolditalic"/>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Alegreya+SC:regular,italic,bold,bolditalic"/>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}" media="screen"/>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="{{ asset('js/html5shiv.js') }}"></script>
	<script src="{{ asset('js/respond.js') }}"></script>
	<![endif]-->
	<!--[if IE 8]>
	<script src="{{ asset('js/selectivizr.js') }}"></script>
	<![endif]-->
</head>

<body>
	<div id="drawer-right">
		<div class="cross text-right">
			<a class="toggleDrawer" href="#"><i class="fa fa-times-circle fa-2x"></i></a>
		</div>
		<h2>Navigate</h2>
		<nav>
			<ul class="nav nav-pills nav-stacked">
				<li>
					<a href="/#home"><i class="fa fa-home"></i> Home</a>
				</li>
				<li>
					<a href="/#services"><i class="fa fa-tasks"></i> Services</a>
				</li>
				<li>
					<a href="/#blog"><i class="fa fa-wordpress"></i> Blog</a>
				</li>
				<li>
					<a href="/#parallax"><i class="fa fa-heart"></i> Parallax</a>
				</li>
				<li>
					<a href="/#testimonials"><i class="fa fa-thumbs-up"></i> Testimonials</a>
				</li>
				<li>
					<a href="/#contact"><i class="fa fa-phone-square"></i> Contact</a>
				</li>
			</ul>
		</nav>
		<div class="social">
			<h2>Stay Connected</h2>
			<ul>
				<li><a href=""><i class="fa fa-facebook-square fa-3x"></i></a></li>
				<li><a href=""><i class="fa fa-twitter-square fa-3x"></i></a></li>
				<li><a href=""><i class="fa fa-tumblr-square fa-3x"></i></a></li>
				<li><a href=""><i class="fa fa-google-plus-square fa-3x"></i></a></li>
			</ul>
		</div>
	</div>

	<div id="wrapper">
		@yield('body')
	</div>

	<div class="content-block" id="footer">
		<div class="container">
			<div class="row text-center">
				<div class="col-xs-12">&copy; Copyright Lanjalan 2016</div>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
<script src="{{ asset('js/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.actual.min.js') }}"></script>
<script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
