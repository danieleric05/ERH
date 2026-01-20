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

        // Validation des champs
        $request->validate([
            'pseudo' => 'required|string',
            'password' => 'required|string',
        ]);

        // Étape 1 : Vérifier que l'utilisateur existe
        $user = User::where('pseudo', strtoupper($request->pseudo))->first();

        if (!$user) {
            return Redirect::back()
                ->withInput($request->only('pseudo'))
                ->withErrors("Désolé, cet utilisateur n'existe pas parmi la liste mise à disposition du système.");
        }

        // Étape 2 : Vérifier que le compte est actif
        if ($user->statut_id != 1) {
            return Redirect::back()
                ->withInput($request->only('pseudo'))
                ->withErrors("Désolé, cet utilisateur a été suspendu ou désactivé.");
        }

        // Étape 3 : Tenter la connexion avec seulement pseudo et password
        $userConnect = Auth::attempt([
            'pseudo' => strtoupper($request->pseudo),
            'password' => $request->password
        ]);

        if ($userConnect && Auth::check()) {
            return redirect()->intended($this->redirectionPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only('pseudo'))
            ->withErrors([
                'pseudo' => $this->getFailedLoginError(),
            ]);
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


    public function logoutUser(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->derniere_cnx = new \DateTime();
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect()->route('se-connecter')->with('success', 'Vous êtes à présent déconnecté.');
    }


}
