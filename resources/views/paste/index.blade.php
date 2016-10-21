@extends('default')

@section('pagetitle') Home - EdPaste @endsection

@section('navbar')
<li class="nav-item active"><a href="#" class="nav-link">Home</a></li>
@if (Auth::check())
<li class="nav-item"><a href="/users/dashboard" class="nav-link">Dashboard</a></li>
{{-- <li class="nav-item"><a href="/users/account" class="nav-link">My Account</a></li> --}}
<li class="nav-item"><a href=" /logout" class="nav-link">Logout <i>({{ Auth::user()->name }})</i></a></li>
@else
<li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
<li class="nav-item"><a href="/register" class="nav-link">Register</a></li>
@endif
@endsection

@section('script')
<script src="jquery.autogrowtextarea.min.js"></script>
<script>
	function checkvalue(value)
	{
		if(value==="password")
			document.getElementById('passwordInput').style.display='block';
		else
			document.getElementById('passwordInput').style.display='none';
	}
</script>
@endsection

@section('content')
<div class="container">
	<form action="/" method="post" accept-charset="utf-8">
		{{ csrf_field() }}
		{{-- Ca c'est pour éviter que les navigateurs préremplissent les champs --}}
		<input style="display:none" type="text" name="fakeusernameremembered"/>
		<input style="display:none" type="password" name="fakepasswordremembered"/>
		<div class="row">
			<div class="form-group col-xs-12 @if ($errors->has('pasteTitle')) has-error @endif">
				<label for="pasteTitle">Title</label>
				<input type="text" class="form-control" name="pasteTitle" id="pasteTitle" placeholder="Title (optional)" maxlength="70" value="{{ old('pasteTitle') }}@if (isset($clonedTitle))Clone of : {{ $clonedTitle }}@endif">
				@if ($errors->has('pasteTitle'))
				<span class="help-block">
					<strong>{{ $errors->first('pasteTitle') }}</strong>
				</span>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="form-group col-xs-12 @if ($errors->has('pasteContent')) has-error @endif">
				<label for="pasteContent">Content</label>
				<script type="text/javascript">
					$(document).ready(function(){
						$("#pasteContent").autoGrow();
					});
				</script>
				<textarea class="form-control input-sm" name="pasteContent" id="pasteContent" rows="15" placeholder="Paste your text here...">@if (isset($clonedContent)){{ $clonedContent }}@endif</textarea>
				@if ($errors->has('pasteContent'))
				<span class="help-block">
					<strong>{{ $errors->first('pasteContent') }}</strong>
				</span>
				@endif
			</div>
		</div>				
		<div class="row">
			<div class="form-group col-sm-3">
				<label for="expire">Paste expiration</label>
				<select class="form-control" name="expire" id="expire">
					<option value="never" selected="selected">Never</option>
					<option value="burn">Burn after reading</option>
					<option value="10m">10 minutes</option>
					<option value="1h">1 hour</option>
					<option value="1d">1 day</option>
					<option value="1w">1 week</option>
				</select>
			</div>
			<div class="form-group col-sm-3 @if ($errors->has('pastePassword')) has-error @endif">
				<label for="privacy">Privacy</label>
				<select class="form-control" name="privacy" id="privacy" onchange='checkvalue(this.value)'>
					<option value="link">Unlisted, access with link</option>
					<option value="password" @if ($errors->has('pastePassword')) selected="selected" @endif>Password-protected</option>
					@if (Auth::check())
					<option value="private">Private, only me</option>
					@endif
				</select>
			</div>
			{{-- Ce truc n'apparait que si "Password-protected" est séléctionné plus haut --}}
			<div class="form-group col-sm-2 @if ($errors->has('pastePassword')) has-error @endif" id="passwordInput" @if (!$errors->has('pastePassword')) style="display:none;" @endif>
				<label for="pastePassword">Password</label>
				<input type="password" class="form-control" name="pastePassword" id="pastePassword" placeholder="Enter a password..." maxlength="40">
				@if ($errors->has('pastePassword'))
				<span class="help-block">
					<strong>{{ $errors->first('pastePassword') }}</strong>
				</span>
				@endif
			</div>
			{{-- Le captcha n'aparaît que pour les users non-id --}}
			@if (!Auth::check())
			<div class="form-group col-sm-3 @if ($errors->has('g-recaptcha-response')) has-error @endif">
				{!! app('captcha')->display(); !!}
				@if ($errors->has('g-recaptcha-response'))
				<span class="help-block">
					<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
				</span>
				@endif
			</div>
			@endif
		</div>
		<div class="row">
			<div class="form-group text-center">
				<script>
					$(function () {
						$('[data-toggle="tooltip"]').tooltip()
					})
				</script>
				{{-- La tooltip n'apparaît que pour les users non-id et le btn devient danger si y'a des erreurs --}}
				<div class="checkbox">
					<label><input type="checkbox" name="noSyntax">Disable syntax highlighting</label>
				</div>
				<button type="submit" id="submit" class="btn @if (count($errors) > 0) btn-danger @else btn-outline-success @endif  btn-lg" @if (!Auth::check()) data-toggle="tooltip" data-placement="right" title="Registered users have access to other privacy tools and can bypass captchas" @endif>Submit</button>
			</div>
		</div>

	</div>
</div>
</form>
</div>
@endsection
