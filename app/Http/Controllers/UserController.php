<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Paste;
use Auth;
use App\User;
use \Input;
use DB;


class UserController extends Controller
{
	public function dashboard(){
		// Check if user's logged in
		if (!Auth::check()) return redirect('/login');
		$userPastes = Paste::where('userId', Auth::user()->id)->get();
		return view('paste/dashboard', ['userPastes' => $userPastes]);
	}
	public function delete($link){
		// TODO : Faudrait peut-être faire un post à la place
		if (!Auth::check()) return redirect('/');
		$userPaste = Paste::where('link', $link)->firstOrFail();
		if ($userPaste->userId != Auth::user()->id) return redirect('/login');
		$userPaste->forceDelete();
		return redirect('/users/dashboard');
	}
	public function account(){
		return redirect('/');
	}
}
