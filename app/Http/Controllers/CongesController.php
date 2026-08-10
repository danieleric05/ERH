<?php

namespace App\Http\Controllers;

use App\Conges;
use App\Travailleur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CongesController extends Controller
{
    public function ajouter(){
        $data_travailleur = Travailleur::where('etapeid', '!=', 3)->orderBy('id', 'DESC')->get();
        return view('conges.add', compact('data_travailleur'));
    }

    public function post_conges(Request $request){

        $debut = strtotime($request->debut);
        $fin = strtotime($request->fin);
        $nombre_jours = ceil(abs($fin - $debut) / 86400) + 1;

        $tabDate = explode('-', $request->debut);

        $conge = new Conges();
        $conge->travailleurid = $request->travailleurid;
        $conge->type_conge = $request->type_conge;
        $conge->debut = $request->debut;
        $conge->fin = $request->fin;
        $conge->date_reprise = $request->date_reprise;
        $conge->nombre_jours = intval($nombre_jours);
        $conge->justification = $request->justification;
        $conge->statutid = 1; // en attente
        $conge->userid = Auth::user()->id;
        $conge->mois = intval($tabDate[1]);
        $conge->annee = intval($tabDate[0]);

        if($conge->save()){
            return Redirect::route('listeconges')->withSuccess("La demande de congé a été enregistrée avec succès.");
        }

        return Redirect::back()->withErrors("Erreur lors de l'enregistrement, veuillez reprendre le processus.");
    }

    public function liste(){
        $liste_conges = Conges::orderBy('id', 'DESC')->get();
        $travailleursById = Travailleur::whereIn('id', $liste_conges->pluck('travailleurid'))->get()->keyBy('id');
        return view('conges.liste', compact('liste_conges', 'travailleursById'));
    }

    public function calendrier(){
        return view('conges.calendrier');
    }

    public function evenements(){
        $liste_conges = Conges::where('statutid', '!=', 3)->get();
        $travailleursById = Travailleur::whereIn('id', $liste_conges->pluck('travailleurid'))->get()->keyBy('id');

        $couleurs = [1 => '#f0ad4e', 2 => '#00acac']; // 1: en attente (orange), 2: valide (vert)

        $evenements = $liste_conges->map(function($conge) use ($travailleursById, $couleurs) {
            $travailleur = $travailleursById->get($conge->travailleurid);
            $nom = $travailleur ? ($travailleur->nom.' '.$travailleur->prenom) : 'Inconnu';

            return [
                'title' => $nom,
                'start' => $conge->debut,
                'end' => date('Y-m-d', strtotime($conge->fin.' +1 day')),
                'color' => $couleurs[$conge->statutid] ?? '#999',
            ];
        });

        return response()->json($evenements);
    }

    public function valider($id){
        $conge = Conges::find($id);

        if (!$conge) {
            return Redirect::back()->withErrors("Demande de congé introuvable.");
        }

        $conge->statutid = 2; // valide
        $conge->userid = Auth::user()->id;

        if($conge->save()){
            return Redirect::back()->withSuccess("La demande de congé a été validée avec succès.");
        }
    }

    public function refuser($id){
        $conge = Conges::find($id);

        if (!$conge) {
            return Redirect::back()->withErrors("Demande de congé introuvable.");
        }

        $conge->statutid = 3; // refuse
        $conge->userid = Auth::user()->id;

        if($conge->save()){
            return Redirect::back()->withSuccess("La demande de congé a été refusée.");
        }
    }
}
