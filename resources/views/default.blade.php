<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="stylesheet" media="screen" href="https://paste.edraens.net/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Oswald">
	<link rel=stylesheet type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

	<script src="https://paste.edraens.net/jquery.js"></script>
	<script src="https://paste.edraens.net/bootstrap.min.js"></script>
	<!-- Salutations jeune fouineur ! :) -->
	<title>@yield('pagetitle')</title>
	<style>
	.navbar-brand, .nav-link{
		font-family:Oswald;
	}
	body {
		margin-top:75px;
	}
	.alert {
		margin-bottom: 10px;
	}
	</style>
	@yield('style')
	@yield('script')
	<nav class="navbar navbar-fixed-top navbar-default">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="/">EdPaste</a>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="navbar" style="max-height:500px;">
				<ul class="nav navbar-nav navbar-right">
					@yield('navbar')
				</ul>
			</div>
		</div>
	</nav>
</head>

<body>
	@yield('content')
</body>
<footer>
<div class="container">
<div class="row">
	<h5 class="text-center"><small><i>Made by Pierre T. - <a href="https://github.com/Edraens" target="_blank">Edraens</a>, 2016</i></small></h5>
</div>
</div><br />
</footer>

</html>
