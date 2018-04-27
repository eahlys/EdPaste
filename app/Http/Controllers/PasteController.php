<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Paste;
use Auth;
use App\User;
use \Input;
use \Hash;
use Session;
use Cookie;
use DB;
use \Carbon;

class PasteController extends Controller
{
	public function index(){
		return view('paste/index');
	}
	
	public function submit(Requests\StorePaste $request){
    $title = (empty(trim(Input::get('pasteTitle')))) ? 'Untitled' : Input::get('pasteTitle');
    
		$expiration = Input::get('expire');
		$privacy = Input::get('privacy');
		
		// Ici on vérifie que l'user a pas foutu le bronx dans les dropdown list
		$possibleValuesPrivacy = array("link", "password", "private");
		if (in_array($privacy, $possibleValuesPrivacy) == false) return view('paste/index');
		
		// Si l'user a choisi password-protected, on hash son pass, sinon on met 'disabled' dans la variable
		if ($privacy == 'password') $password = bcrypt(Input::get('pastePassword'));
		else $password = 'disabled';
		
		$burnAfter = 0;
		// Ici on génère le timestamp d'expiration
		switch ($expiration) {
			case 'never':
			$timestampExp = 0;
			break;
			case 'burn':
			$timestampExp = date('Y-m-d H:i:s', time());
			$burnAfter = 1;
			break;
			case '10m':
			$timestampExp = date('Y-m-d H:i:s', time()+600);
			break;
			case '1h':
			$timestampExp = date('Y-m-d H:i:s', time()+3600);
			break;
			case '1d':
			$timestampExp = date('Y-m-d H:i:s', time()+86400);
			break;
			case '1w':
			$timestampExp = date('Y-m-d H:i:s', time()+604800);
			break;
			default:
			die("User input error.");
			break;
		}
		
		// Génération du lien tant que le lien existe
		$generatedLink = str_random(10);
		$existingPasteWithGeneratedLink = Paste::where('link', $generatedLink)->first();
		while (!is_null($existingPasteWithGeneratedLink)) {
			$generatedLink = str_random(10);
			$existingPasteWithGeneratedLink = Paste::where('link', $generatedLink)->first();
		}
		
		Paste::create([
			'link' => $generatedLink,
			'userId' => (Auth::check()) ? Auth::id() : 0,
			'views' => '0',
			'title' => $title,
			'content' => Input::get('pasteContent'),
			'ip' => $request->ip(),
			'expiration' => $timestampExp,
			'privacy' => $privacy,
			'password' => $password,
			'noSyntax' => Input::has('noSyntax'),
			'burnAfter' => $burnAfter,
      ]);
      
      return redirect('/'.$generatedLink);
    }
		
		public function view($link, Request $request){
      $paste = Paste::where('link', $link)->firstOrFail();
      
      // Est-ce que l'utilisateur connecté est celui qui a écrit la paste ?
      $isSameUser = ((Auth::user() == $paste->user && $paste->userId != 0)) ? true : false;
      
			// Expiration de la paste
			if($paste->expiration != 0){
				if ($paste->burnAfter == 0){
					if (time() > strtotime($paste->expiration)){
						if ($isSameUser) $expiration = "Expired";
						else abort('404');
					}
					else $expiration = Carbon\Carbon::parse($paste->expiration)->diffForHumans();
				}
				else {
					// On retire le mode burn after reading que si la paste ne vient pas d'être créée et que en cas de burn le pass est bon
					if (time() - strtotime($paste->expiration) > 3) {
						$disableBurn = true;
						$expiration = "Burn after reading";
					}
					else $expiration = "Burn after reading (next time)";
				}
      }
      // Petite vérification au cas où l'admin n'ait pas migrate
			elseif ($paste->expiration == "10m" || $paste->expiration == "1h" || $paste->expiration == "1d" || $paste->expiration == "1w" || $paste->expiration == "never" || $paste->expiration == "burn") die("Paste expiration error. Please make sure you have the latest commit of EdPaste and run 'php artisan migrate'.");
			else $expiration = "Never";
      
      // On s'occupe des options de vie privée de la paste (TODO password)
      // https://stackoverflow.com/questions/30212390/laravel-middleware-return-variable-to-controller
			if ($paste->privacy == "private") {
				if($isSameUser) $privacy = "Private";
        else abort('404');
      }
      elseif ($paste->privacy == "password"){
        $privacy = "Password-protected";
        if ($request->isMethod('post')) {
          if(!Hash::check(Input::get('pastePassword'), $paste->password)) return view('paste/password', ['link' => $paste->link, 'wrongPassword' => true]);
        }
        // Si l'user n'est pas le même et que la paste a été créée il y a plus de trois secondes :
        elseif(!$isSameUser && time() - $paste->created_at->timestamp > 3) return view('paste/password', ['link' => $paste->link]);
      }
      elseif ($paste->privacy == "link") $privacy = "Public";
      else die("Error.");
      
      // Ici on vérifie si le burn de la paste doit être supprimé (besoin de le faire après le check password)
      if (isset($disableBurn)) {
        $paste->burnAfter = 0;
        $paste->save();
      }
      
      // Ici on incrémente le compteur de vues à chaque vue
      if (time()-$paste->updated_at->timestamp > 10) $paste->increment('views');
      
      // Renvoi de la view
      return view('paste/view', [
        'username' => ($paste->userId != 0) ? $paste->user->name : "Guest",
        'views' => $paste->views,
        'sameUser' => $isSameUser,
        'link' => $link,
        'title' => $paste->title,
        'content' => $paste->content,
        'expiration' => $expiration,
        'privacy' => $privacy,
        'date' => $paste->created_at->format('M jS, Y'),
        'fulldate' => $paste->created_at->format('d/m/Y - H:i:s'),
        'noSyntax' => $paste->noSyntax,
        ]);
      }
      
      public function password($link, Request $request){
        $paste = Paste::where('link', $link)->firstOrFail();
        $messages = array(
          'pastePassword.required' => 'Please enter a password',
        );
        $this->validate($request, [
          'pastePassword' => 'required',
        ], $messages);
        
        if (Hash::check(Input::get('pastePassword'), $paste->password)) {
          Cookie::queue($paste->link, Input::get('pastePassword'), 15);
          return redirect('/'.$link);
        }
        else {
          return view('paste/password', ['link' => $paste->link, 'wrongPassword' => true]);
        }
      }
      
      // TODO Raw
      public function raw($link){
        header('Content-Type: text/plain');
        $paste = Paste::where('link', $link)->firstOrFail();
        
        $timestampUpdated = $paste->updated_at->timestamp;
        $diffTimestamp = time() - $timestampUpdated;
        
        // On génère les messages d'expire et on fait expirer la paste dans la BDD si elle l'est
        if($paste->expiration == "never") {
          $expired = false;
        }
        elseif($paste->expiration == "burn") {
          // Si la paste n'a jamais été vue, c'est donc que l'user qui l'a crée vient d'être redirect dessus, on gère ça ici
          if ($diffTimestamp < 5) {
            $expired = false;
          }
          // Si elle a déjà été vue une fois par son créateur, alors on la passe en mode burn after reading
          else {
            $expired = false;
            $burn = true;
          }
        }
        elseif($paste->expiration == "10m") {
          if ($diffTimestamp > 600) $expired = true;
          else $expired = false;
        }
        elseif($paste->expiration == "1h") {
          if ($diffTimestamp > 3600) $expired = true;
          else $expired = false;
        }
        elseif($paste->expiration == "1d") {
          if ($diffTimestamp > 86400) $expired = true;
          else $expired = false;
        }
        elseif($paste->expiration == "1w") {
          if ($diffTimestamp > 604800) $expired = true;
          else $expired = false;
        }
        elseif($paste->expiration == "expired") {
          $alreadyExpired = true;
          $expired = true;
        }
        // Si y'a un problème, on gère l'exception en arrêtant tout
        else die('Fatal error.');
        
        // On regarde si la paste est expirée
        if ($expired == true) {
          // Si elle n'est pas marquée expirée dans la BDD, on la marque
          if (!isset($alreadyExpired)) {
            $paste->expiration = "expired";
            $paste->save();
          }
          // On regarde si le créateur est connecté, si oui il peut voir sa paste expirée, sinon 404
          if(Auth::check()) {
            if ($paste->userId != Auth::user()->id) {
              return view('errors/404');
            }
          }
          else return view('errors/404');
        }
        if ($paste->privacy == "private") {
          // On regarde si le créateur est connecté, si oui il peut voir sa paste expirée, sinon 404
          if(Auth::check()) {
            if ($paste->userId != Auth::user()->id) {
              return view('errors/404');
            }
            else $privacy = "Private";
          }
          else return view('errors/404');
        }
        elseif ($paste->privacy == "password") {
          // Si la paste a été crée y'a moins de 3 sec alors on demande pas le pass, c'est que l'user la regarde
          if ($diffTimestamp > 3) {
            // Ici on bypass le pass si l'user est le même
            if(Auth::check()) {
              if ($paste->userId != Auth::user()->id) {
                // Si le cookie de password existe on le recheck un coup quand même
                if (Cookie::get($paste->link) !== null) {
                  // On recheck le cookie et on envoie la view de password si le pass a été manipulé
                  if (Hash::check(Cookie::get($paste->link), $paste->password) == false) {
                    return view('paste/password', ['link' => $paste->link]);
                  }
                  else {
                  }
                }
                // Si il existe pas, on va demander le password
                else {
                  return view('paste/password', ['link' => $paste->link]);
                }
              }
            }
            else {
              // Si le cookie de password existe on le recheck un coup quand même
              if (Cookie::get($paste->link) !== null) {
                // On recheck le cookie et on envoie la view de password si le pass a été manipulé
                if (Hash::check(Cookie::get($paste->link), $paste->password) == false) {
                  return view('paste/password', ['link' => $paste->link]);
                }
                else {
                }
              }
              // Si il existe pas, on va demander le password
              else {
                return view('paste/password', ['link' => $paste->link]);
              }
            }
          }
          else $privacy = "Password-protected (bypassed)";
        }
        elseif ($paste->privacy == "link") {
          $privacy = "Public";
        }
        
        // On regarde si la paste est en burn after reading (et donc qu'elle a été vue une seule fois, par son créateur, juste après la rédaction)
        if (isset($burn)) {
          $paste->expiration = "expired";
          $paste->save();
        }
        
        // Ici on incrémente le compteur de vues à chaque vue
        if ($diffTimestamp > 10) DB::table('pastes')->where('link', $link)->increment('views');
        
        // On crée la var envoyée à la view disant si l'user créateur est le viewer
        $sameUser = false;
        if(Auth::check()) {
          if ($paste->userId == Auth::user()->id) {
          }
        }
        return response($paste->content, 200)->header('Content-Type', 'text/plain');
      }
    }
    