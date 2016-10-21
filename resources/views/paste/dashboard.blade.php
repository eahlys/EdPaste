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
       <th></th>
       <th>Creation</th>
       <th>Actions</th>
     </tr>
   </thead>
   <tbody>
    @foreach ($userPastes as $userPaste)
    <tr>
     <th scope="row">{{ $loop->iteration }}</th>
     <td><a href="/{{ $userPaste->link }}">@if (strlen($userPaste->title) <= 20) {{ $userPaste->title}} @else {{ mb_substr($userPaste->title,0,20,'UTF-8') }}... @endif</a></td>
     <td class="hidden-xs"><i>@if (strlen($userPaste->content) < 90) {{ $userPaste->content}} @else {{ mb_substr($userPaste->content,0,90,'UTF-8') }}... @endif</i></td>

     {{-- Ici le bouton de preview suivi du modal --}}
     <td>
       <button class="btn btn-secondary btn-sm" type="button" data-toggle="modal" data-target="#preview{{ $loop->iteration }}" aria-expanded="false" aria-controls="collapseExample{{ $loop->iteration }}">
        View
      </button>
      {{-- Ici le modal --}}
      <!-- Modal -->
      <div class="modal fade" id="preview{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="preview" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="preview" style="word-wrap: break-word;">{{ $userPaste->title }}</h4>
            </div>
            <div class="modal-body">
              <pre id="paste" style="color: #000; word-break: normal;"><code><i>{{ $userPaste->content }}</i></code></pre>
            </div>
            <div class="modal-footer">
              @if ($userPaste->noSyntax == 0)
              <a class="btn btn-primary" href="/{{ $userPaste->link }}" role="button" target="_blank">View formatted paste</a>
              @endif
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
</td>
    {{-- Là on repasse à la date --}}
    <td>{{ $userPaste->created_at->format('d/m/Y') }}</td>
    {{-- <td><a href="/users/delete/{{ $userPaste->link }}">Delete</a></td> --}}
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
            <div class="modal-body">
             Are you sure ?
            </div>
            <div class="modal-footer">
              <a class="btn btn-danger" href="/users/delete/{{ $userPaste->link }}" role="button">Yes</a>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
          </div>
        </div>
      </div>
  @endforeach
</tbody>
</table>