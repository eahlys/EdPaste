@extends('default')

@section('pagetitle') Not found - EdPaste @endsection

@section('navbar')
<li class="nav-item"><a href="/" class="nav-link">Home</a></li>
@if (Auth::check())
<li class="nav-item"><a href="/users/account" class="nav-link">My Account</a></li>
<li class="nav-item"><a href=" /logout" class="nav-link">Logout <i>({{ Auth::user()->name }})</i></a></li>
@else
<li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
<li class="nav-item"><a href="/register" class="nav-link">Register</a></li>
@endif
@endsection

@section('content')
<div class="container">
<div class="text-center">
<div class="jumbotron">
  <h1><i>Page not found</i></h1>
  <p class="lead hidden-xs">Content may be not found or expired, or access may be denied</p>
  <hr class="m-y-2">
  <p class="lead">
    <a class="btn btn-danger btn-lg" href="/" role="button">Return to homepage</a>
  </p>
</div>
</div>
</div>
@endsection
