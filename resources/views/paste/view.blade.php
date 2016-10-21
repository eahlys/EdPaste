@extends('default')

@section('pagetitle') {{ $title }} - EdPaste @endsection

@section('navbar')
<li class="nav-item"><a href="/" class="nav-link">Home</a></li>
@if (Auth::check())
<li class="nav-item"><a href="/users/dashboard" class="nav-link">Dashboard</a></li>
{{-- <li class="nav-item"><a href="/users/account" class="nav-link">My Account</a></li> --}}
<li class="nav-item"><a href=" /logout" class="nav-link">Logout <i>({{ Auth::user()->name }})</i></a></li>
@else
<li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
<li class="nav-item"><a href="/register" class="nav-link">Register</a></li>
@endif
@endsection

@section('style')
<link rel="stylesheet" href="/highlight_styles/tomorrow.css">
<style>
	@if ($noSyntax == false)
	pre {
		overflow: auto;
		word-wrap: normal;
		background:none;
		padding:0px;
		font-size: 75%;
		word-break: normal;
	}
	pre code {
		white-space: pre;
	}
	.hljs-line-numbers {
		text-align: right;
		border-right: 1px solid #ccc;
		color: #999;
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	@else
	pre {
		color: #000;
		word-break: normal;
	}
	@endif
</style>
@endsection

@section('script')
@if ($noSyntax == false)
<script src="highlight.pack.js"></script>
<script src="highlightjs-line-numbers.min.js"></script>
<script>
	hljs.initHighlightingOnLoad();
	hljs.initLineNumbersOnLoad();
</script>
@endif
@endsection

@section('content')
<div class="container">
	@if ($expiration == "Burn after reading")
	<div class="alert alert-danger" role="alert">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Be careful!</strong> <i>This paste is in burn after reading mode, which means it can be viewed only once.</i>
	</div>
	@elseif ($expiration == "Burn after reading (next time)")
	<div class="alert alert-warning" role="alert">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Be careful!</strong> <i>You have selected burn after reading mode, keep in mind that refreshing this page before sharing the paste will destroy it.</i>
	</div>
	@endif
	@if ($expired == true)
	<div class="alert alert-info" role="alert">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<i>This paste has expired, however since you've wrote it, you may view it whenever you want.</i>
	</div>
	@endif
	<div class="row">
		<div class="col-sm-11">
			<h3 style="margin-top:0px; word-wrap: break-word;">{{ $title }}</h3>
		</div>
		{{-- Ici le petit panel de gestion --}}
		@if ($sameUser == true)
		<div class="col-sm-1">
			<a class="btn btn-danger btn-sm pull-right hidden-xs" href="/users/delete/{{ $link }}" role="button"><i class="fa fa-trash-o"></i></a>
		</div>
		@endif
	</div>
	<div class="row">
		<div class="col-xs-12">
			<ul class="list-inline" style="color:#999FA4;">
				<script>
					$(function () {
						$('[data-toggle="tooltip"]').tooltip()
					})
				</script>
				<li><i class="fa fa-user" data-toggle="tooltip" data-placement="bottom" title="Username"></i> <i>{{ $username }}</i></li>
				<li><i class="fa fa-calendar" data-toggle="tooltip" data-placement="bottom" title="Date of creation"></i> <i data-toggle="tooltip" data-placement="bottom" title="{{ $fulldate }}">{{ $date }}</i></li>
				<li><i class="fa fa-eye" data-toggle="tooltip" data-placement="bottom" title="Times viewed"></i> <i>{{ $views }} view(s)</i></li>
				{{-- Expiration cachée si xs --}}
				@if ($expiration == "Never")
				<li class="hidden-xs"><i class="fa fa-clock-o" data-toggle="tooltip" data-placement="bottom" title="Expiration"></i> <i>{{ $expiration }}</i></li>
				@else
				<li><i class="fa fa-clock-o" data-toggle="tooltip" data-placement="bottom" title="Expiration"></i> <i>{{ $expiration }}</i></li>
				@endif

				{{-- Privacy cachée si xs --}}
				@if ($privacy == "Public")
				<li class="hidden-xs"><i class="fa fa-lock" data-toggle="tooltip" data-placement="bottom" title="Privacy"></i> <i>{{ $privacy }}</i></li>
				@else
				<li><i class="fa fa-lock" data-toggle="tooltip" data-placement="bottom" title="Privacy"></i> <i>{{ $privacy }}</i></li>
				@endif

			</ul>
		</div>
	</div>

	{{-- N'est formaté que si le SH est activé --}}
	<div class="row" @if ($noSyntax == true) style="margin-bottom:20px;" @endif>
		<div class="col-sm-12">
			<label for="paste"><i>@if ($noSyntax == false) Syntax-highlighted @else Plain-text @endif</i></label>
			<pre id="paste"><code>{{ $content }}</code></pre>
		</div>
	</div>

	{{-- N'apparaît que si le SH est activé --}}
	@if ($noSyntax == false)
	<div class="row" style="margin-bottom:20px;">
		<div class="col-sm-12">
			<label for="noFormatPaste"><i>Plain-text</i></label>
			<textarea class="form-control input-sm" id="noFormatPaste" rows="25" readonly="true">{{ $content }}</textarea>
		</div>
	</div>
	@endif
</div>
@endsection
