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
    <div class="row" style="margin-top:25px;">
        <form class="form-inline text-center" action="/{{ $link }}" method="post" accept-charset="utf-8">
            {{ csrf_field() }}
            {{-- Ca c'est pour éviter que les navigateurs préremplissent les champs --}}
            <input style="display:none" type="text" name="fakeusernameremembered"/>
            <input style="display:none" type="password" name="fakepasswordremembered"/>

            <div class="form-group @if ($errors->has('pastePassword') || isset($wrongPassword)) has-error @endif" id="passwordInput">
                @if ($errors->has('pastePassword'))
                <span class="help-block">
                    <strong>{{ $errors->first('pastePassword') }}</strong>
                </span>
                @elseif (isset($wrongPassword))
                 <span class="help-block">
                    <strong>Wrong password</strong>
                </span>
                @endif
                <input type="password" class="form-control" name="pastePassword" id="pastePassword" placeholder="Enter paste password" maxlength="40">
                <button type="submit" id="submit" class="btn @if (count($errors) > 0 || isset($wrongPassword)) btn-danger @else btn-outline-success @endif">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
