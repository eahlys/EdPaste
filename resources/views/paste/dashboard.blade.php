@extends('default')

@section('pagetitle') Dashboard - EdPaste @endsection

@section('navbar')
<li class="nav-item"><a href="/" class="nav-link">Home</a></li>
@if (Auth::check())
<li class="nav-item active"><a href="/users/dashboard" class="nav-link">Dashboard</a></li>
{{-- <li class="nav-item"><a href="/users/account" class="nav-link">My Account</a></li> --}}
<li class="nav-item"><a href="/logout" class="nav-link">Logout <i>({{ Auth::user()->name }})</i></a></li>
@else
<li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
<li class="nav-item"><a href="/register" class="nav-link">Register</a></li>
@endif
@endsection

@section('content')
<div class="container">
	<div class="row">
		<h2 class="text-center display-4">Dashboard</h2>
    <table class="table table-striped table-hover">
     <thead>
      <tr>
       <th>#</th>
       <th>Title</th>
       <th class="hidden-xs">Content</th>
       <th>Creation</th>
       <th>Actions</th>
     </tr>
   </thead>
   <tbody>
    @foreach ($userPastes as $userPaste)
    <tr>
     <th scope="row">{{ $loop->iteration }}</th>
     <td><a href="/{{ $userPaste->link }}">@if (strlen($userPaste->title) < 20) {{ $userPaste->title}} @else {{ mb_substr($userPaste->title,0,20,'UTF-8') }}... @endif</a></td>
     <td class="hidden-xs"><i>@if (strlen($userPaste->content) < 90) {{ $userPaste->content}} @else {{ mb_substr($userPaste->content,0,90,'UTF-8') }}... @endif</i></td>
     <td>{{ $userPaste->created_at->format('d/m/Y - H:i') }}</td>
     <td><a href="/users/delete/{{ $userPaste->link }}">Delete</a></td>
   </tr>
   @endforeach
 </tbody>
</table>