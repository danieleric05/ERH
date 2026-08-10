<?php

namespace App\Http\Controllers;

use App\AccidentTravail;
use App\Equipes;
use App\Santes;
use App\Travailleur;
use App\Unites;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SanteController extends Controller
{
    //
    public function index_santes(){
        $unites = Unites::orderBy('id', 'DESC')->get();
        $data_equipe = Equipes::orderBy('id', 'DESC')->get();
        $data_sante = Santes::orderBy('id', 'DESC')->get();
        $data_travailleur = Travailleur::orderBy('id', 'DESC')->get();
        return view('sante.add', compact('unites', 'data_equipe', 'data_sante', 'data_travailleur'));
    }

    public function post_accident_travail(Request $request)
    {
		
		$journalierAT = \App\Travailleur::find($request->travailleurid);

		if (!$journalierAT) {
		    return Redirect::back()->withErrors("Le travailleur sélectionné est introuvable.");
		}

		$matricule = $journalierAT->matricule;

        $verif = AccidentTravail::Where('travailleurid', $request->travailleurid)->Where('datepub', $request->datepub)->first();

        if($verif){
            return Redirect::back()->withErrors("Impossible qu'un journalier ai deux arret travail le meme jour.");
        }else{

            $tabDate = explode("-", $request->dateconsul);

            $sante = new AccidentTravail();
			
            $sante->travailleurid = $request->travailleurid;
            $sante->travailleur_mat = $matricule;
            $sante->cause = $request->cause;
            $sante->prescription = $request->prescription;
            $sante->datepub = $request->dateconsul;
			
			if($request->arret_travail == 1){
                $sante->arret_travail = $request->arret_travail;
                $sante->debut_arret = $request->debut_arret;
                $sante->fin_arret = $request->fin_arret;
                $sante->cause_arret_travail = $request->cause_arret_travail;
            }else{
                $sante->arret_travail = $request->arret_travail;
                $sante->debut_arret = null;
                $sante->fin_arret = null;
                $sante->cause_arret_travail = null;
            }

            $sante->userid = Auth::user()->id;
            $sante->statutid = 1;

            $sante->mois = intval($tabDate['1']);
            $sante->annee = intval($tabDate['0']);
            if($sante->save()){

					$unite = \App\Unites::find($journalierAT->uniteid);
					$equipe = \App\Equipes::find($journalierAT->equipeid);
					$fonction = \App\Fonction::find($journalierAT->fonction_entrepriseid);

					$uniteJ = $unite?->label ?? 'Non renseignée';
					$equipeJ = $equipe?->label ?? 'Non renseignée';
					$fonctionJ = $fonction?->label ?? 'Non renseignée';
					// on génère une chaîne de caractères aléatoire qui sera utilisée comme frontière
                    $boundary = "-----=" . md5(uniqid(rand()));
                    $headers  = "From: \"ERH | ACCIDENT DE TRAVAIL \"<infos@plasticaci.com>\n";
			        $message = "";
                    $headers .= "MIME-Version: 1.0\n";
                    $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"";

                    $message_html  = "<html>";
                    $message_html .= "<body>";
                    $message_html .= '<div style="width: 100%;">
                    <div style="width: 60%;margin:0 auto;">
                        <div style="text-align: center;width: 100%;font-size:18px; background: #00ba8b;padding: 5px 10px;color:#fff">CAS D\'ACCIDENT DE TRAVAIL</div>
                            <div style="color: rgb(51, 51, 51); border: 1px solid  #00ba8b; padding: 10px 20px 10px; width: 96.1%; border-radius: 0px 0px 4px 4px;">
                                <p style="font-size: 18px;"><strong>Matricule : , '.strtoupper($journalierAT->matricule).' </strong></p>
                                <p style="font-size: 15px; line-height: 1.5em; margin-top: 15px;">
										LE JOURNALIER '.strtoupper($journalierAT->nom).' '.strtoupper($journalierAT->prenom).' <br> A ETE VICTIME D\'UN ACCIDENT DE 
										TRAVAIL, LE : '.strtoupper($sante->datepub).'
                                </p>
                                <div style="margin: 0px auto 20px;margin-top: 20px;margin-bottom: 30px;">
									<strong>Informations</strong><br>
									<span>UNITE : <strong>'.$uniteJ.'</strong></span><br>
									<span>EQUIPE : <strong>'.$equipeJ.'</strong></span><br>
									<span>EQUIPE : <strong>'.$fonctionJ.'</strong></span>
									<br>
									<br>
                                </div>
                                <div style="margin: 0px auto 20px;margin-top: 20px;margin-bottom: 30px;">
								
									<p>
										<strong>Cause detaillee de l\'accident de travail</strong><br>
										'.$sante->cause_arret_travail.' 
										<br>
										<br>
										
										<strong>Prescription</strong><br>
										'.$sante->prescription.' 
										<br>
										<br>
										
										<strong>Arret de travail</strong><br>
										Date de debut : '.$sante->debut_arret.' <br>
										Date de fin : '.$sante->fin_arret.' 
										<br>
										<br>
									</p>
                                    	
                                </div>
                            </div>
                        </div>
                    </div>';
                    $message_html .= "</body>";
                    $message_html .= "</html>";

                    $message .= "\n\n";
                    $message .= "--" . $boundary . "\n";
                    $message .= "Content-Type: text/html; charset=utf-8\"iso-8859-1\"\n";
                    $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
                    $message .= $message_html;
                    $message .= "\n\n";
                    $message .= "--" . $boundary . "--\n";

                    $objet = 'ACCIDENT DE TRAVAIL';
                    $destinataire = 'jaures.okou@plasticaci.com, germain.allah@plasticaci.com, dominique.nguessan@plasticaci.com, audrey.kouplo@plasticaci.com, prisca.goba@plasticaci.com, laurent.kwassi@plasticaci.com, soualiho.koulibaly@plasticaci.com, saad.saad@plasticaci.com, raiss@plasticaci.com';
                    //$destinataire = 'jaures.okou@plasticaci.com, germain.allah@plasticaci.com';

                    mail($destinataire, $objet, $message, $headers);
				
				
					return Redirect::back()->withSuccess("Accident de travail enregistré avec succès.");
					
            }
        }

    }

    public function post_sante(Request $request)
    {
		$travailleur = \App\Travailleur::find($request->travailleurid);

		if (!$travailleur) {
		    return Redirect::back()->withErrors("Le travailleur sélectionné est introuvable.");
		}

		$matricule = $travailleur->matricule;
        $verif = Santes::Where('travailleurid', $request->travailleurid)->Where('datepub', $request->datepub)->Where('arret_travail',1)->first();

        if($verif){
            return Redirect::back()->withErrors("Impossible qu'un journalier ai deux arret travail le meme jour.");
        }else{

            $tabDate = explode("-", $request->dateconsul);

            $sante = new Santes();
            $sante->travailleurid = $request->travailleurid;
            $sante->travailleur_mat = $matricule;
            $sante->consultation = $request->consultation;
            $sante->prescription = $request->prescription;
            $sante->datepub = $request->dateconsul;

            if($request->arret_travail == 1){
                $sante->arret_travail = $request->arret_travail;
                $sante->debut_arret = $request->debut_arret;
                $sante->fin_arret = $request->fin_arret;
                $sante->cause_arret_travail = $request->cause_arret_travail;
            }else{
                $sante->arret_travail = $request->arret_travail;
                $sante->debut_arret = null;
                $sante->fin_arret = null;
                $sante->cause_arret_travail = null;
            }
			
            $sante->userid = Auth::user()->id;
            $sante->mois = intval($tabDate['1']);
            $sante->annee = intval($tabDate['0']);
            if($sante->save()){
                return Redirect::back()->withSuccess("Consultation enregistré avec succès.");
            }
        }

    }

    public function historique_consultation(){
        $listeSante = Santes::orderBy('id', 'DESC')->get();
        return view("sante.historiques", compact('listeSante', 'equipes'));
    }

    public function listesconsultation(){
        $listeSante = Santes::where('id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view("sante.liste", compact('listeSante', 'equipes'));
    }

    public function listesaccident(){
        $listeAT = AccidentTravail::orderBy('id', 'DESC')->get();
        return view("sante.accident_travail.liste", compact('listeAT', 'equipes'));
    }

    public function accident_travail_traiter($id){
		
		$id_article = AccidentTravail::where('id', $id)->first();
        if($id_article){
			
			$accTraiter = AccidentTravail::find($id_article->id);
			$accTraiter->statutid = 2;
			if($accTraiter->save() == true){
				return Redirect::back()->withSuccess("L'accident de travail du matricule : ".strtoupper($id_article->travailleur_mat)." a été traité avec succès");
			}else{
				return Redirect::back()->withErrors("Impossible, vous n'avez pas de précarité.");
			}
			
		}
		
	}

    public function accident_travail(){
        $unites = Unites::orderBy('id', 'DESC')->get();
        $data_equipe = Equipes::orderBy('id', 'DESC')->get();
        $data_sante = Santes::orderBy('id', 'DESC')->get();
        $data_travailleur = Travailleur::orderBy('id', 'DESC')->get();
        return view('sante.accident_travail.add', compact('unites', 'data_equipe', 'data_sante', 'data_travailleur'));
    }


}
