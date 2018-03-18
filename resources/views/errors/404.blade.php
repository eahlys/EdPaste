@extends('default')

@section('pagetitle') Not found - EdPaste @endsection

@section('navbar')

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
