@extends('default')

@section('pagetitle') Dashboard - EdPaste @endsection

@section('navbar')
<li class="nav-item"><a href="/" class="nav-link">Home</a></li>
@if (Auth::check())
<li class="nav-item active"><a href="/users/dashboard" class="nav-link">Dashboard</a></li>
<li class="nav-item"><a href="/users/account" class="nav-link">My Account</a></li>
<li class="nav-item"><a href="/logout" class="nav-link">Logout <i>({{ Auth::user()->name }})</i></a></li>
@else
<li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
<li class="nav-item"><a href="/register" class="nav-link">Register</a></li>
@endif
@endsection

@section('script')
<script>
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
</script>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<h2 class="text-center display-4">Dashboard</h2>
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Title</th>
          <th class="hidden-xs">Content</th>
          <th class="hidden-xs"></th>
          <th class="hidden-xs"></th>
          <th class="hidden-xs">Views</th>
          <th>Creation</th>
          <th></th>
        </tr>
      </thead>
    </tbody>
    @foreach ($userPastes as $userPaste)
    <tr>
      <td><a href="/{{ $userPaste->link }}">@if (strlen($userPaste->title) <= 20) {{ $userPaste->title}} @else {{ mb_substr($userPaste->title,0,20,'UTF-8') }}... @endif</a></td>
      <td class="hidden-xs"><i>@if (!$userPaste->noSyntax) <i class="fa fa-file-code-o"></i> &nbsp; @endif @if (strlen($userPaste->content) < 90) {{ $userPaste->content}} @else {{ mb_substr($userPaste->content,0,90,'UTF-8') }}... @endif</i></td>
      {{--  Bloc d'infos  --}}
      <td class="hidden-xs">
        @if ($userPaste->privacy == "link") <i class="fa fa-globe fa-lg" data-toggle="tooltip" data-placement="bottom" title="Public"></i> 
        @elseif ($userPaste->privacy == "password") <i class="fa fa-key fa-lg" data-toggle="tooltip" data-placement="bottom" title="Password-protected"></i> 
        @elseif ($userPaste->privacy == "private") <i class="fa fa-user-secret fa-lg" data-toggle="tooltip" data-placement="bottom" title="Private"></i> @endif 
      </td>
      <td class="hidden-xs">
        @if ($userPaste->expiration == "0") <i class="fa fa-calendar-check-o fa-lg" data-toggle="tooltip" data-placement="bottom" title="Never expires"></i> 
        @elseif ($userPaste->burnAfter == "1") <i class="fa fa-exclamation-circle fa-lg" data-toggle="tooltip" data-placement="bottom" title="Burn after reading"></i>
        @elseif (time() > strtotime($userPaste->expiration)) <i class="fa fa-calendar-times-o fa-lg" data-toggle="tooltip" data-placement="bottom" title="Expired"></i> 
        @else <i class="fa fa-hourglass fa-lg" data-toggle="tooltip" data-placement="bottom" title="Expiration set"></i>@endif
      </td>
      <td> {{ $userPaste->views }}</td>
      {{-- Là on repasse à la date --}}
      <td>{{ $userPaste->created_at->format('M jS, Y') }}</td>
      <td>
        <button class="btn btn-danger btn-sm pull-right" type="button" data-toggle="modal" data-target="#delete{{ $loop->iteration }}" aria-expanded="false" aria-controls="collapseExample{{ $loop->iteration }}"><i class="fa fa-trash-o"></i></button></td>
      </tr>
      <div class="modal fade" id="delete{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="preview" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="preview" style="word-wrap: break-word;">Delete "<i>{{ $userPaste->title }}</i>" ?</h4>
            </div>
            <div class="modal-body">Are you sure ? You <b>cannot</b> undo this !</div>
            <div class="modal-footer">
              <a class="btn btn-danger btn-sm" href="/users/delete/{{ $userPaste->link }}" role="button">Yes</a>
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </tbody>
  </table>
  