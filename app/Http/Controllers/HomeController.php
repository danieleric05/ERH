<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function logining(){
        return view('login');
    }


    public function post_login(Request $request){

        //Vérification de l'existance du compte
        $exist = User::where('pseudo', strtoupper($request->pseudo))->first();

        if(!$exist){

            return Redirect::back()->withErrors("Désoler cet utilisateur n'exist pas parmi la liste mise à disposition du système.");

        }else{

            //Vérification de l'état du compte
            $verif = User::where('pseudo', strtoupper($request->pseudo))->Where('statut_id', 1)->first();

            if(!$verif){

                return Redirect::back()->withErrors("Désoler cet utilisateur a été suspendu ou désactivé.");

            }else{

                $userConnect = Auth::attempt(['pseudo'=>strtoupper($request->pseudo), 'password'=>$request->password, 'statut_id'=>1]);
                if (($userConnect) && (Auth::check() == true))
                {
                    return redirect()->intended($this->redirectionPath());
                }

                return redirect($this->loginPath())->withInput($request->only('pseudo'))
                    ->withErrors([
                        'pseudo' => $this->getFailedLoginError(),
                    ]);

            }
        }

    }

    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/';
    }

    protected function getFailedLoginError()
    {
        return "Les composants de l'accès saisi ne sont pas corrects.";
    }

    public function redirectionPath()
    {
        if (property_exists($this, 'redirectPath'))
        {
            return $this->redirectionPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/bienvenue';
    }


    public function logoutUser($id)
    {

        $change = User::find($id);

        if ($change) {
            $change->derniere_cnx = new \DateTime();
            $change->save();
        }

        Auth::logout();

        return Redirect()->route('login')->withErrors('Vous êtes à présent déconnecté.');
    }


}
