<?php

namespace App\Http\Controllers;

use App\Role;
use App\Unite;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * La table `user` est partagée avec Statprod. Le champ `app` distingue
     * les comptes : app=2 pour ERH, app=1 pour Statprod (comptes génériques
     * par poste), app=0 pour un usage historique non attribué.
     */
    private const APP_ERH = 2;

    private function ensureIsAdmin(): void
    {
        if (Auth::user()->idrole != 2) {
            abort(403, 'Accès non autorisé');
        }
    }

    public function index()
    {
        $this->ensureIsAdmin();

        $data_utilisateurs = User::with(['role', 'unite'])
            ->where('app', self::APP_ERH)
            ->orderBy('id', 'DESC')
            ->get();

        return view('utilisateurs.liste', compact('data_utilisateurs'));
    }

    public function create()
    {
        $this->ensureIsAdmin();

        $data_roles = Role::orderBy('label')->get();
        $data_unites = Unite::orderBy('label')->get();

        return view('utilisateurs.add', compact('data_roles', 'data_unites'));
    }

    public function store(Request $request)
    {
        $this->ensureIsAdmin();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:200',
            'pseudo' => 'required|string|max:100|unique:user,pseudo',
            'password' => 'required|string|min:6',
            'idrole' => 'required|integer|exists:role,id',
            'idunite' => 'required|integer|exists:unite,id',
            'contact' => 'nullable|string|max:50',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->pseudo = strtoupper($request->pseudo);
        $user->password = Hash::make($request->password);
        $user->password_erh = '';
        $user->idrole = $request->idrole;
        $user->idunite = $request->idunite;
        $user->contact = $request->contact ?? '';
        $user->fonction = $request->fonction;
        $user->statut_id = 1;
        $user->app = self::APP_ERH;
        $user->save();

        return redirect()->route('listeutilisateurs')
            ->withSuccess("L'utilisateur {$user->pseudo} a été créé avec succès.");
    }

    public function edit($id)
    {
        $this->ensureIsAdmin();

        $edit = User::where('app', self::APP_ERH)->findOrFail($id);
        $data_roles = Role::orderBy('label')->get();
        $data_unites = Unite::orderBy('label')->get();

        return view('utilisateurs.edit', compact('edit', 'data_roles', 'data_unites'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureIsAdmin();

        $user = User::where('app', self::APP_ERH)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:200',
            'pseudo' => ['required', 'string', 'max:100', Rule::unique('user', 'pseudo')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'idrole' => 'required|integer|exists:role,id',
            'idunite' => 'required|integer|exists:unite,id',
            'contact' => 'nullable|string|max:50',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->pseudo = strtoupper($request->pseudo);
        $user->idrole = $request->idrole;
        $user->idunite = $request->idunite;
        $user->contact = $request->contact ?? '';
        $user->fonction = $request->fonction;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('listeutilisateurs')
            ->withSuccess("L'utilisateur {$user->pseudo} a été mis à jour avec succès.");
    }

    public function toggleStatus($id)
    {
        $this->ensureIsAdmin();

        $user = User::where('app', self::APP_ERH)->findOrFail($id);

        if ($user->id === Auth::id()) {
            return Redirect::back()->withErrors("Vous ne pouvez pas désactiver votre propre compte.");
        }

        $user->statut_id = $user->statut_id === 1 ? 0 : 1;
        $user->save();

        $message = $user->statut_id === 1
            ? "L'utilisateur {$user->pseudo} a été réactivé."
            : "L'utilisateur {$user->pseudo} a été désactivé.";

        return Redirect::back()->withSuccess($message);
    }
}
