@extends('default')

@section('pagetitle') Password prompt - EdPaste @endsection

@section('navbar')
<li class="nav-item"><a href="/" class="nav-link">Home</a></li>
@if (Auth::check())
<li class="nav-item"><a href="/users/dashboard" class="nav-link">Dashboard</a></li>
<li class="nav-item"><a href="/users/account" class="nav-link">My Account</a></li>
<li class="nav-item"><a href=" /logout" class="nav-link">Logout <i>({{ Auth::user()->name }})</i></a></li>
@else
<li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
<li class="nav-item"><a href="/register" class="nav-link">Register</a></li>
@endif
@endsection

@section('content')
<div class="container">
    <h2 class="text-center display-4">Password prompt</h2>
    <div class="row" style="margin-top:25px;">
        <form class="form-inline text-center" action="/{{ $link }}" method="post" accept-charset="utf-8">
            @csrf
            {{-- Ca c'est pour éviter que les navigateurs préremplissent les champs --}}
            <input style="display:none" type="text" name="fakeusernameremembered"/>
            <input style="display:none" type="password" name="fakepasswordremembered"/>
            
            <div class="form-group @if (isset($wrongPassword)) has-error @endif" id="passwordInput">
                <input type="password" class="form-control" name="pastePassword" id="pastePassword" placeholder="Enter paste password" maxlength="40" autofocus="true">
                <button type="submit" id="submit" class="btn @if (isset($wrongPassword)) btn-danger @else btn-outline-success @endif">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
