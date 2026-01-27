<?php

namespace App\Http\Controllers;

use App\Autorisations;
use App\HAO1;
use App\Sanctions;
use App\Santes;
use App\Travailleur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SanctionController extends Controller
{

    public function post_sanction(Request $request){

        $verif = Sanctions::Where('demandeurid', $request->demandeurid)->Where('employeid', $request->concerneid)->Where('datesanction', $request->datesanction)->first();

        if($verif){
            return Redirect::back()->withErrors("Une sanction existe déjà pour ce(s) employé(s) à cette date.");
        }else{

            $tabDate = explode("-", $request->datesanction);

            $variables = new Sanctions();
            $variables->demandeurid = ($request->demandeurid) ;
            $variables->employeid = serialize($request->concerneid);
            $variables->motif = $request->motif;
            $variables->expose_motif = $request->expose_motif;
            $variables->datesanction = $request->datesanction;
            $variables->datefautes = $request->datefautes;
            $variables->sanction_applique = $request->sanction_applique;
            $variables->nombre_jour = $request->nombre_jour;
			
            $variables->debut = $request->debut;
            $variables->fin = $request->fin;
            $variables->quart = $request->quarts;
            $variables->statutid = 1; // enregistrer
            $variables->userid = Auth::user()->id;
            $variables->mois = intval($tabDate['1']);
            $variables->annee = intval($tabDate['0']);

            $variables->save();
            if($variables->save()){
				
				return Redirect::route('techarger_sanctions', $variables->id)->withSuccess("La sanction a été enregistré avec succès, Merci de télécharger le fichier PDF ");

            }
        }


    }

    public function techarger_sanctions($id){
		$sanct = Sanctions::Where('id', $id)->first();
        return view('sanctions.fiche_sanction', compact('sanct'));
    }

    public function listes_sanctions(){
        $Sanctions = Sanctions::orderBy('id', 'DESC')->get();
        return view('sanctions.liste', compact('Sanctions'));
    }

    public function post_autorisation(Request $request){

        $verif = Autorisations::Where('demandeurid', $request->demandeurid)->Where('statut', $request->statut)->Where('debut', $request->date_debut)->Where('fin', $request->date_fin)->first();

        if($verif){
            return Redirect::back()->withErrors("Impossible, d'avoir une autorisation de la meme periode dans l'année.");
        }else{

            $debut = strtotime($request->date_debut);
            $fin = strtotime($request->date_fin);
            $nb_jour = ceil(abs($fin - $debut) / 86400) + 1;
            $nuitee = ceil(abs($fin - $debut) / 86400);

            $tabDate = explode("-", $request->date_fin);

            $variables = new Autorisations();

            $variables->demandeurid = ($request->demandeurid) ;
            $variables->statut = $request->statut;
            $variables->motif_absence = $request->motif_absence;
            $variables->debut = $request->date_debut;
            $variables->fin = $request->date_fin;

            $variables->date_reprise = $request->date_reprise;
            $variables->interim_assurer_par = $request->interim_assurer_par;
            $variables->commentaire = $request->commentaire;
            $variables->continent = $request->continent;
            $variables->pays = $request->pays;
            $variables->ville = $request->ville;

            $variables->nuitee = intval($nuitee);
            $variables->journee = intval($nb_jour);
            $variables->mode_transport = $request->mode_transport;
            $variables->divers = $request->divers;
            $variables->heure_debut = $request->divers;
            $variables->heure_fin = $request->divers;
            $variables->weekend = $request->weekend;
            $variables->achat_mission = $request->achat_mission;
            $variables->participation_evenement = $request->part_eve;
            $variables->statutid = 1; // enregistrer
            $variables->userid = Auth::user()->id;
            $variables->mois = intval($tabDate['1']);
            $variables->annee = intval($tabDate['0']);

            $variables->save();
            if($variables->save()){
                return Redirect::back()->withSuccess("L'autorisation a été enregistré avec succès.");
            }
        }

    }


    public function variables_sante($id){

        $SantesFirst = Santes::Where('id', $id)->first();

        if($SantesFirst){

            $debut = strtotime($SantesFirst->debut_arret);
            $fin = strtotime($SantesFirst->fin_arret);
            $dif = ceil(abs($fin - $debut) / 86400) + 1;

            $nb_jour = intval($dif);

            $mat_travail = Travailleur::where('id', $SantesFirst->travailleurid)->first();

            if($mat_travail){

                $hao1_mat_first = HAO1::where('matricule', $mat_travail->matricule)->first();

                if($hao1_mat_first){

                    $update_H0A1 = HAO1::find($hao1_mat_first->id);
                    $update_H0A1->variable += intval($nb_jour);
                    $update_H0A1->userid = Auth::user()->id;
                    $update_H0A1->updated_at = Carbon::now();
                    $update_H0A1->save();

                    if($update_H0A1->save()){

                        $update_Santes = Santes::find($id);
                        $update_Santes->statutid = 3;
                        $update_Santes->userid = Auth::user()->id;
                        $update_Santes->updated_at = Carbon::now();
                        $update_Santes->save();

                        if( $update_Santes->save() ){
                            return Redirect::back()->withSuccess("La variable a été ajouté avec succès.");
                        }

                    }else{
                        return Redirect::back()->withErrors("Error, Veuillez contacter le service informatique");
                    }

                }else{
                    return Redirect::back()->withErrors("Error, le HA01 du travailleur n'a pas été ajouté . Veuillez contacter le service informatique");
                }


            }else{
                return Redirect::back()->withErrors("Impossible, le matricule du travailleur est inconnu. Veuillez contacter le service informatique");
            }


        }

    }

    public function sanctionvariable($id){

        $SanctionsFirst = Sanctions::Where('id', $id)->first();

        if($SanctionsFirst){

            $nb_jour = $SanctionsFirst->nombre_jour;

            $consernes = unserialize($SanctionsFirst->employeid);

            $verif_traitement = HAO1::where('statutid', 1)->get();

            foreach ($verif_traitement as $all){

                foreach ($consernes as $mat){

                    if($mat == $all->matricule){

                        $update_H0A1 = HAO1::find($all->id);
                        //$update_H0A1 = new HAO1();
                        $update_H0A1->variable += intval($nb_jour);
                        $update_H0A1->userid = Auth::user()->id;
                        $update_H0A1->updated_at = Carbon::now();
                        $update_H0A1->save();

                    }

                }

            }

            $update_Sanc = Sanctions::find($id);
            $update_Sanc->statutid = 2;
            $update_Sanc->userid = Auth::user()->id;
            $update_Sanc->updated_at = Carbon::now();
            $update_Sanc->save();

            if($update_Sanc->save() == true){
                return Redirect::back()->withSuccess("La sanction a été ajouté au variable avec succès.");
            }

        }

        return view('sanctions.liste', compact('Sanctions'));
    }


    public function autorisationvariable($id){

        $AutoFirst = Autorisations::Where('id', $id)->first();

        if($AutoFirst){

            $debut = strtotime($AutoFirst->debut);
            $fin = strtotime($AutoFirst->fin);
            $dif = ceil(abs($fin - $debut) / 86400) + 1;

            $nb_jour = intval($dif);

            $verif_traitement = HAO1::where('statutid', 1)->get();

            if($verif_traitement){

                $demandeurid = Travailleur::where('id', $AutoFirst->demandeurid)->first();

                if($demandeurid){

                    $hao1_mat_first = HAO1::where('matricule', $demandeurid->matricule)->first();
                    $update_H0A1 = HAO1::find($hao1_mat_first->id);
                    $update_H0A1->variable += intval($nb_jour);
                    $update_H0A1->userid = Auth::user()->id;
                    $update_H0A1->updated_at = Carbon::now();
                    $update_H0A1->save();

                }else{
                    return Redirect::back()->withErrors("Impossible, le demandeur n'est pas inscrit dans la base de donnée.");
                }

            }else{
                return Redirect::back()->withErrors("Impossible, de traiter les HA01, ils n'ont pas été importer.");
            }

            $update_Auto = Autorisations::find($id);
            $update_Auto->statutid = 3; //
            $update_Auto->userid = Auth::user()->id;
            $update_Auto->updated_at = Carbon::now();
            $update_Auto->save();

            if($update_Auto->save() == true){
                return Redirect::back()->withSuccess("La sanction a été ajouté au variable avec succès.");
            }

        }

        return view('sanctions.liste', compact('Sanctions'));

    }

    public function missionvariable($id){

        $AutoFirst = Autorisations::Where('id', $id)->first();

        if($AutoFirst){

            $nb_jour = intval($AutoFirst->journee);

            $verif_traitement = HAO1::where('statutid', 1)->get();

            if($verif_traitement){

                $demandeurid = Travailleur::where('id', $AutoFirst->demandeurid)->first();

                if($demandeurid){

                    $hao1_mat_first = HAO1::where('matricule', $demandeurid->matricule)->first();
                    $update_H0A1 = HAO1::find($hao1_mat_first->id);
                    $update_H0A1->variable = intval($nb_jour);
                    $update_H0A1->userid = Auth::user()->id;
                    $update_H0A1->updated_at = Carbon::now();
                    $update_H0A1->save();

                }else{
                    return Redirect::back()->withErrors("Impossible, le demandeur n'est pas inscrit dans la base de donnée.");
                }

            }else{
                return Redirect::back()->withErrors("Impossible, de traiter les HA01, ils n'ont pas été importer.");
            }

            $update_Auto = Autorisations::find($id);
            $update_Auto->statutid = 3; //
            $update_Auto->userid = Auth::user()->id;
            $update_Auto->updated_at = Carbon::now();
            $update_Auto->save();

            if($update_Auto->save() == true){
                return Redirect::back()->withSuccess("La sanction a été ajouté au variable avec succès.");
            }

        }

        return view('sanctions.liste', compact('Sanctions'));
    }
}
