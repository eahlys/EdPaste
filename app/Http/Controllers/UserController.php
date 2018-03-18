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
		if (!Auth::check()) return redirect('/');
		$userPaste = Paste::where('link', $link)->firstOrFail();
		if ($userPaste->userId != Auth::user()->id) return redirect('/login');
		$userPaste->forceDelete();
		return redirect('/users/dashboard');
	}
	public function account(){
		if (!Auth::check()) return redirect('/login');
		$user = User::where('id', Auth::user()->id)->first();
		return view('paste.account', ['user' => $user]);
	}
	public function editAccount(Request $request){
		if (!Auth::check()) return redirect('/login');
		if (Input::get('password') != ""){
			$this->validate($request, [
				'name' => 'max:30|required',
				'email' => 'required|email',
				'password' => 'max:100|min:6',
				'passwordconfirm' => 'max:100|min:6',
				'currentpassword' => 'max:100',
			]);
		}
		else {
			$this->validate($request, [
				'name' => 'max:30|required',
				'email' => 'required|email',
			]);
		}
		$user = User::where('id', Auth::user()->id)->first();
		$checkEmail = User::where('email', Input::get('email'))->first();
		if (!is_null($checkEmail) && $checkEmail->id != Auth::user()->id) return Redirect::back()->withErrors(['Specified e-mail address already exists']);
		$user->name = Input::get('name');
		$user->email = Input::get('email');
		if (Input::get('password') != "") {
			if (Input::get('password') != Input::get('passwordconfirm')) return Redirect::back()->withErrors(['Password confirmation does not match. Please try again']);
			if (Hash::check(Input::get('currentpassword'), $user->password))
			{
				$user->password = Hash::make(Input::get('password'));
			}
			else return Redirect::back()->withErrors(['Current password is incorrect. Please try again.']);
		}
		$user->save();
		return redirect('users/account');
	}
}
