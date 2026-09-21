<?php

namespace App\Services;

use App\ActionsCDC;
use App\Categories;
use App\Commune;
use App\Fonction;
use App\Pays;
use App\Travailleur;
use App\Unites;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * Import unique des travailleurs et de leurs contrats depuis le « Répertoire CDD » :
 * stagiaires, CDD, CDI et journaliers. Une ligne = un contrat.
 *
 * - Personne retrouvée par matricule, sinon par nom + prénoms (+ date de naissance) ;
 *   créée si absente (matricule attribué : S = stagiaires, E = CDD/CDI, J = journaliers).
 * - Personne existante : identité complétée (jamais écrasée) ; contrat mis à jour si même
 *   date de début, reconduit (etapeid 6 + action 6) si plus récent, historisé si plus ancien.
 * - Une valeur sans libellé identique et unique dans ERH (unité, fonction, catégorie…) n'est
 *   pas devinée : la ligne est importée sans elle et un avertissement est émis.
 * - Idempotent : réimporter le même fichier ne change rien.
 */
class ImportRepertoireContrats
{
    public const ID_TYPE = ['STAGE' => 1, 'CDD' => 2, 'CDI' => 3, 'JOURNALIER' => 4, 'STAGE ECOLE' => 5, 'STAGE DE QUALIFICATION' => 6];
    private const SERIE = [1 => 'S', 2 => 'E', 3 => 'E', 4 => 'J', 5 => 'S', 6 => 'S'];

    /** En-tête normalisé (sans accent ni ponctuation) => clé interne. */
    public const COLONNES = [
        'ODRE' => 'ordre', 'CIVILITE' => 'civilite', 'NOM' => 'nom', 'PRENOMS' => 'prenoms', 'DATENAISSANCE' => 'naissance',
        'LIEUNAISSANCE' => 'lieu_naissance', 'NATIONALITE' => 'nationalite', 'SITMATRIMONIALE' => 'situation', 'NBREENFANT' => 'enfants',
        'NCNPS' => 'cnps', 'NATUREPIECE' => 'nature_piece', 'CNI' => 'piece', 'DATECNI' => 'piece_date', 'LIEUCNI' => 'piece_lieu',
        'LIEUHABITATION' => 'habitation', 'TELEPHONE' => 'telephone', 'MATRICULE' => 'matricule', 'FONCTION' => 'fonction',
        'NBREMOIS' => 'duree', 'ANCIENNEDATEFIN' => 'ancienne_fin', 'DATEDEBUT' => 'debut', 'DATEFIN' => 'fin', 'MOISESSAI' => 'mois_essai',
        'DATEESSAI' => 'date_essai', 'CATEGORIE' => 'categorie', 'TYPE' => 'type_remuneration', 'SALAIRE' => 'salaire', 'SURSALAIRE' => 'sursalaire',
        'SALAIREF' => 'salaire_lettres', 'TRANSPORT' => 'transport', 'CONTRAT' => 'contrat', 'NBRECDD' => 'nbre_cdd', 'DATEENTREE' => 'date_entree',
        'TOTALPERIODECDD' => 'total_periode', 'OBSERVATION' => 'observation', 'N' => 'numero', 'UNITE' => 'unite', 'EVALUATEUR' => 'evaluateur',
        'DEBUTJOURNALIER' => 'debut_journalier',
    ];

    private array $idxPays = [];
    private array $idxCommune = [];
    private array $idxFonction = [];
    private array $idxCategorie = [];
    private array $idxUnite = [];
    private array $parNom = [];       // nom normalisé => [Travailleur…]
    private array $matriculesPris = [];
    private array $maxSerie = [];
    private array $virtuels = [];    // simulation : personnes « créées » plus haut dans le même fichier

    public function importer(string $fichier, bool $simulation = true, ?int $userid = null): array
    {
        $this->charger();
        $lignes = $this->lire($fichier);
        $rapport = ['lignes' => [], 'total' => ['crees' => 0, 'mis_a_jour' => 0, 'reconduits' => 0, 'historises' => 0, 'inchanges' => 0, 'rejetes' => 0]];

        foreach ($lignes as $numero => $l) {
            $r = ['ligne' => $numero, 'matricule' => null, 'nom' => trim(($l['nom'] ?? '') . ' ' . ($l['prenoms'] ?? '')), 'statut' => null, 'changements' => [], 'avertissements' => [], 'erreur' => null];
            try {
                $this->traiter($l, $r, $simulation, $userid);
            } catch (\Throwable $e) {
                $r['statut'] = 'rejeté';
                $r['erreur'] = $r['erreur'] ?: 'Erreur : ' . $e->getMessage();
            }
            $cle = ['créé' => 'crees', 'mis à jour' => 'mis_a_jour', 'reconduit' => 'reconduits', 'historisé' => 'historises', 'inchangé' => 'inchanges', 'rejeté' => 'rejetes'][$r['statut']] ?? 'rejetes';
            $rapport['total'][$cle]++;
            $rapport['lignes'][] = $r;
        }

        return $rapport;
    }

    // ------------------------------------------------------------------ lecture

    /** @return array<int, array> lignes indexées par numéro de ligne Excel, clés internes */
    public function lire(string $fichier): array
    {
        $reader = IOFactory::createReaderForFile($fichier);
        $reader->setReadDataOnly(true);
        $feuille = $reader->load($fichier)->getSheet(0);
        $tout = $feuille->toArray(null, true, false, false);
        $entete = array_shift($tout);

        $cles = [];
        foreach ($entete as $i => $h) {
            $n = self::norm($h);
            if ($n !== '' && isset(self::COLONNES[$n])) {
                $cles[$i] = self::COLONNES[$n];
            }
        }
        if (!in_array('nom', $cles, true) || !in_array('debut', $cles, true)) {
            throw new \RuntimeException("En-têtes non reconnus : les colonnes NOM et DateDébut sont obligatoires (ligne 1).");
        }

        $out = [];
        foreach ($tout as $i => $ligne) {
            $rec = [];
            foreach ($cles as $col => $cle) {
                $v = $ligne[$col] ?? null;
                if ($v !== null && !(is_string($v) && trim($v) === '')) {
                    $rec[$cle] = is_string($v) ? trim($v) : $v;
                }
            }
            unset($rec['ordre'], $rec['numero'], $rec['total_periode']);   // colonnes de suivi, sans effet
            if ($rec) {
                $out[$i + 2] = $rec;
            }
        }

        return $out;
    }

    private function charger(): void
    {
        $this->idxPays = $this->indexUnique(Pays::pluck('nationalite', 'id')) + $this->indexUnique(Pays::pluck('label', 'id'));
        $this->idxCommune = $this->indexUnique(Commune::pluck('label', 'id'));
        $this->idxFonction = $this->indexUnique(Fonction::pluck('label', 'id'));
        $this->idxCategorie = $this->indexUnique(Categories::pluck('label', 'id'));
        $this->idxUnite = $this->indexUnique(Unites::pluck('label', 'id'));

        $this->parNom = [];
        $this->matriculesPris = [];
        $this->maxSerie = [];
        $this->virtuels = [];
        foreach (Travailleur::select('id', 'matricule', 'nom', 'prenom', 'prenom_suite', 'date_naissance')->get() as $t) {
            $this->parNom[self::norm($t->nom)][] = $t;
            $this->matriculesPris[$t->matricule] = true;
        }
        foreach (['E', 'J', 'S'] as $p) {
            $this->maxSerie[$p] = (int) preg_replace('/\D/', '', Travailleur::prochainMatricule($p)) - 1;
        }
    }

    // ------------------------------------------------------------------ traitement d'une ligne

    private function traiter(array $l, array &$r, bool $simulation, ?int $userid): void
    {
        if (empty($l['nom']) || empty($l['prenoms'])) {
            $this->rejeter($r, 'Nom et prénoms obligatoires.');

            return;
        }

        // --- contrat
        $debut = $this->date($l['debut'] ?? null);
        $fin = $this->date($l['fin'] ?? null);
        if (!$debut) {
            $this->rejeter($r, 'Date de début du contrat absente ou illisible.');

            return;
        }
        if ($fin && $fin < $debut) {
            $this->rejeter($r, 'La date de fin est antérieure à la date de début.');

            return;
        }
        $matricule = $this->matriculeFichier($l['matricule'] ?? null);
        $typeId = $this->typeContrat($l, $matricule, $r);
        if (!$typeId) {
            return;
        }
        if ($typeId !== 3 && !$fin) {
            $r['avertissements'][] = 'Date de fin absente pour un contrat à durée déterminée.';
        }

        // --- personne
        $personne = $this->trouverPersonne($l, $matricule, $r);
        if ($r['statut'] === 'rejeté') {
            return;
        }

        [$identite, $contrat] = $this->champs($l, $typeId, $debut, $fin, $r);

        if (!$personne) {
            $cle = self::norm($l['nom']) . '|' . self::norm($l['prenoms']);
            if (isset($this->virtuels[$cle])) {     // simulation : même personne déjà « créée » plus haut
                $v = $this->virtuels[$cle];
                $r['matricule'] = $v['matricule'];
                $r['statut'] = $debut === $v['debut'] ? 'inchangé' : ($debut > $v['debut'] ? 'reconduit' : 'historisé');
                $r['changements'][] = $debut === $v['debut'] ? 'même contrat déjà présent plus haut dans le fichier' : "contrat du $debut : rattaché à la fiche créée plus haut ({$v['matricule']})";
                if ($debut > $v['debut']) {
                    $this->virtuels[$cle]['debut'] = $debut;
                }

                return;
            }
            $this->creer($l, $identite, $contrat, $typeId, $matricule, $r, $simulation, $userid);

            return;
        }
        $r['matricule'] = $personne->matricule;
        $this->mettreAJour($personne, $identite, $contrat, $debut, $fin, $r, $simulation, $userid);
    }

    private function creer(array $l, array $identite, array $contrat, int $typeId, ?string $matricule, array &$r, bool $simulation, ?int $userid): void
    {
        if ($matricule && isset($this->matriculesPris[$matricule])) {
            $this->rejeter($r, "Le matricule $matricule existe déjà pour une autre personne.");

            return;
        }
        if (!$matricule) {
            $serie = self::SERIE[$typeId];
            $this->maxSerie[$serie]++;
            $matricule = $serie . str_pad((string) $this->maxSerie[$serie], $serie === 'J' ? 7 : 5, '0', STR_PAD_LEFT);
        }
        $this->matriculesPris[$matricule] = true;
        $this->virtuels[self::norm($l['nom']) . '|' . self::norm($l['prenoms'])] = ['matricule' => $matricule, 'debut' => $contrat['date_debut_contrat']];
        $r['matricule'] = $matricule;
        $r['statut'] = 'créé';
        $r['changements'][] = 'nouvelle fiche';

        if ($simulation) {
            return;
        }
        DB::transaction(function () use ($l, $identite, $contrat, $typeId, $matricule, $userid, &$r) {
            $t = new Travailleur();
            $t->matricule = $matricule;
            $prenoms = strtoupper($l['prenoms']);
            $t->nom = strtoupper($l['nom']);
            $t->prenom = mb_substr($prenoms, 0, 19);
            $t->prenom_suite = mb_strlen($prenoms) > 19 ? mb_substr($prenoms, 19) : null;
            foreach ($identite + $contrat as $champ => $v) {
                $t->$champ = $v;
            }
            $t->etapeid = 2;
            $t->statutid = 2;
            $t->type_employer = $typeId === 4 ? 1 : 2;
            $t->inscrit_le = Carbon::now()->toDateString();
            $t->ip = 'import-repertoire';
            $t->userid = $userid;
            $t->mois = date('m');
            $t->annee = date('Y');
            $t->save();
            $this->action($t, 1, $contrat['date_debut_contrat'], $contrat['date_fin_contrat'] ?? null, $userid);
            $this->parNom[self::norm($t->nom)][] = $t;
        });
    }

    private function mettreAJour(Travailleur $t, array $identite, array $contrat, string $debut, ?string $fin, array &$r, bool $simulation, ?int $userid): void
    {
        $modif = [];
        $etat = 'inchangé';

        // identité : on complète, on n'écrase jamais
        foreach ($identite as $champ => $v) {
            if ($this->vide($t->$champ)) {
                $modif[$champ] = $v;
                $r['changements'][] = "$champ = $v";
            }
        }

        $courant = $t->date_debut_contrat;
        if (!$courant || $debut === $courant) {
            // même contrat : la ligne du répertoire fait foi
            foreach ($contrat as $champ => $v) {
                $ancien = $t->$champ;
                if ($this->different($ancien, $v)) {
                    $modif[$champ] = $v;
                    $r['changements'][] = "$champ : " . ($this->vide($ancien) ? '(vide)' : $this->texte($ancien)) . ' → ' . $this->texte($v);
                }
            }
            if (!$courant) {
                $modif['date_debut_contrat'] = $debut;
            }
            $etat = $modif ? 'mis à jour' : 'inchangé';
            $historique = false;
        } elseif ($debut > $courant) {
            if ((int) $t->etapeid === 4) {
                $this->rejeter($r, "Certificat de travail déjà délivré : la reconduction doit se faire depuis l'application.");

                return;
            }
            foreach ($contrat as $champ => $v) {
                $modif[$champ] = $v;
            }
            $modif['etapeid'] = 6;
            $modif['motif_fin_contrat'] = null;
            $r['changements'][] = "reconduction : $courant → $debut";
            $etat = 'reconduit';
            $historique = ['actionid' => 6];
        } else {
            // contrat plus ancien que le contrat en cours : trace d'historique, la fiche en cours n'est pas modifiée
            $existe = ActionsCDC::where('travailleurid', $t->id)->whereIn('actionid', [1, 6])->where('debut_contrat', $debut)->exists();
            if (!$existe) {
                $premier = !ActionsCDC::where('travailleurid', $t->id)->where('actionid', 1)->exists();
                $historique = ['actionid' => $premier ? 1 : 6];
                $r['changements'][] = "contrat antérieur ($debut) ajouté à l'historique";
                $etat = 'historisé';
            } else {
                $etat = $modif ? 'mis à jour' : 'inchangé';
                $historique = false;
            }
        }

        $r['statut'] = $etat;
        if ($simulation || (!$modif && !isset($historique['actionid']))) {
            return;
        }
        DB::transaction(function () use ($t, $modif, $historique, $debut, $fin, $userid) {
            if ($modif) {
                $t->forceFill($modif)->save();
            }
            if (isset($historique['actionid'])) {
                $this->action($t, $historique['actionid'], $debut, $fin, $userid);
            }
        });
    }

    private function action(Travailleur $t, int $actionid, ?string $debut, ?string $fin, ?int $userid): void
    {
        $a = new ActionsCDC();
        $a->date_choisit = $debut;
        $a->debut_contrat = $debut;
        $a->fin_contrat = $fin;
        $a->travailleurid = $t->id;
        $a->travailleur_mat = $t->matricule;
        $a->userid = $userid ?: 0;
        $a->actionid = $actionid;
        $a->save();
    }

    // ------------------------------------------------------------------ champs

    /** @return array{0: array, 1: array} [identité, contrat] au format des colonnes de e_travailleur */
    private function champs(array $l, int $typeId, string $debut, ?string $fin, array &$r): array
    {
        $id = [];
        $ct = ['idtype_contrat' => $typeId, 'date_debut_contrat' => $debut];
        if ($fin) {
            $ct['date_fin_contrat'] = $fin;
        }

        $civ = self::norm($l['civilite'] ?? '');
        $civilite = ['MADEMOISELLE' => 'Mademoiselle', 'MLLE' => 'Mademoiselle', 'MADAME' => 'Madame', 'MME' => 'Madame', 'MONSIEUR' => 'Monsieur', 'M' => 'Monsieur', 'MR' => 'Monsieur'][$civ] ?? null;
        if ($civ !== '' && !$civilite) {
            $r['avertissements'][] = "Civilité « {$l['civilite']} » non reconnue.";
        }
        $id['civilite'] = $civilite;

        $sit = self::norm($l['situation'] ?? '');
        $situation = ['CELIBATAIRE' => 'Celibataire', 'MARIE' => 'Marie', 'MARIEE' => 'Marie', 'VEUF' => 'Veuf(ve)', 'VEUVE' => 'Veuf(ve)', 'VEUFVE' => 'Veuf(ve)'][$sit] ?? null;
        if ($sit !== '' && !$situation) {
            $r['avertissements'][] = "Situation matrimoniale « {$l['situation']} » non reconnue.";
        }
        $id['situation_mat'] = $situation;

        $id['date_naissance'] = $this->date($l['naissance'] ?? null);
        $id['lieu_naissance'] = isset($l['lieu_naissance']) ? strtoupper($l['lieu_naissance']) : null;
        $id['nombre_enfant'] = isset($l['enfants']) && is_numeric($l['enfants']) ? (int) $l['enfants'] : null;
        $id['numero_securite'] = isset($l['cnps']) ? preg_replace('/\s+/', '', (string) $l['cnps']) : null;
        $id['telephone'] = isset($l['telephone']) ? preg_replace('/\s+/', '', (string) $l['telephone']) : null;
        $id['nature_piece'] = isset($l['nature_piece']) ? strtoupper($l['nature_piece']) : null;
        $id['pieceidentite'] = isset($l['piece']) ? (string) $l['piece'] : null;
        $id['pieceidentite_livrele'] = $this->date($l['piece_date'] ?? null);
        $id['pieceidentite_lieu'] = isset($l['piece_lieu']) ? strtoupper($l['piece_lieu']) : null;
        $id['lieu_habitation'] = isset($l['habitation']) ? strtoupper($l['habitation']) : null;

        foreach ([['nationalite', 'nationaliteid', $this->idxPays, 'Nationalité'], ['habitation', 'communeid', $this->idxCommune, 'Lieu d\'habitation']] as [$cle, $champ, $idx, $lib]) {
            if (isset($l[$cle])) {
                $n = self::norm($l[$cle]);
                if (isset($idx[$n])) {
                    $id[$champ] = $idx[$n];
                } elseif ($cle === 'nationalite') {
                    $r['avertissements'][] = "$lib « {$l[$cle]} » sans correspondance exacte dans ERH : non renseignée.";
                }
            }
        }
        foreach ([['fonction', 'fonction_entrepriseid', $this->idxFonction, 'Fonction'], ['categorie', 'categorieid', $this->idxCategorie, 'Catégorie'], ['unite', 'uniteid', $this->idxUnite, 'Unité']] as [$cle, $champ, $idx, $lib]) {
            if (isset($l[$cle])) {
                $n = self::norm($l[$cle]);
                if (isset($idx[$n])) {
                    $ct[$champ] = $idx[$n];
                } else {
                    $r['avertissements'][] = "$lib « {$l[$cle]} » sans correspondance exacte et unique dans ERH : non renseignée.";
                }
            }
        }

        // --- durée, essai, historique CDD
        $duree = isset($l['duree']) ? (int) preg_replace('/\D/', '', (string) $l['duree']) : null;
        if (!$duree && $fin) {
            $duree = (int) round(Carbon::parse($debut)->floatDiffInMonths(Carbon::parse($fin)->addDay()));
        }
        $ct['duree_mois'] = $duree ?: null;
        $ct['mois_essai'] = isset($l['mois_essai']) ? (int) preg_replace('/\D/', '', (string) $l['mois_essai']) : null;
        $ct['date_fin_essai'] = $this->date($l['date_essai'] ?? null);
        $ct['ancienne_date_fin'] = $this->date($l['ancienne_fin'] ?? null);
        $ct['nbre_cdd'] = isset($l['nbre_cdd']) && is_numeric($l['nbre_cdd']) ? (int) $l['nbre_cdd'] : null;
        $ct['date_entree'] = $this->date($l['date_entree'] ?? null);
        $ct['debut_journalier'] = $this->date($l['debut_journalier'] ?? null);
        $ct['evaluateur'] = $l['evaluateur'] ?? null;
        $ct['observation'] = $l['observation'] ?? null;

        // --- rémunération : « mensuelle nette » = un seul montant ; avec sursalaire = base + sursalaire
        $salaire = $this->nombre($l['salaire'] ?? null);
        $sursalaire = $this->nombre($l['sursalaire'] ?? null);
        if ($salaire !== null) {
            $ct['salaire_base'] = $salaire;
            $ct['type_remuneration'] = $sursalaire ? 2 : 1;
            if ($sursalaire) {
                $ct['sursalaire'] = $sursalaire;
            }
        }
        $ct['salaire_lettres'] = isset($l['salaire_lettres']) ? preg_replace('/\s+/', ' ', trim($l['salaire_lettres'])) : null;
        if (isset($l['transport'])) {
            if (preg_match('/COMPRIS|INCLUS/', self::norm($l['transport']))) {
                $ct['transport_inclus'] = 1;
            } elseif (($n = $this->nombre($l['transport'])) !== null) {
                $ct['prime_transport'] = $n;
                $ct['transport_inclus'] = 0;
            }
        }

        return [array_filter($id, fn ($v) => $v !== null && $v !== ''), array_filter($ct, fn ($v) => $v !== null && $v !== '')];
    }

    // ------------------------------------------------------------------ identification

    private function matriculeFichier($v): ?string
    {
        $m = strtoupper(trim((string) $v));

        return preg_match('/^[A-Z]\d{3,}$/', $m) ? $m : null;   // « STAGE », vide, tirets… = pas de matricule
    }

    private function typeContrat(array $l, ?string $matricule, array &$r): ?int
    {
        $c = self::norm($l['contrat'] ?? '');
        $carte = ['STAGE' => 1, 'CDD' => 2, 'CDI' => 3, 'JOURNALIER' => 4, 'CDDJ' => 4, 'STAGEECOLE' => 5, 'STAGEDEQUALIFICATION' => 6, 'STAGEQUALIFICATION' => 6];
        if ($c !== '') {
            if (isset($carte[$c])) {
                return $carte[$c];
            }
            $this->rejeter($r, "Type de contrat « {$l['contrat']} » inconnu (CDD, CDI, STAGE, STAGE ECOLE, STAGE DE QUALIFICATION, JOURNALIER).");

            return null;
        }
        $indices = self::norm(($l['matricule'] ?? '') . ' ' . ($l['fonction'] ?? ''));
        if (str_contains($indices, 'STAGE') || str_contains($indices, 'STAGIAIRE')) {
            $r['avertissements'][] = 'Type de contrat déduit : STAGE (matricule ou fonction « stagiaire »).';

            return 1;
        }
        $r['avertissements'][] = 'Type de contrat absent : CDD retenu par défaut (répertoire CDD).';

        return 2;
    }

    private function trouverPersonne(array $l, ?string $matricule, array &$r): ?Travailleur
    {
        if ($matricule) {
            $t = Travailleur::where('matricule', $matricule)->first();
            if ($t) {
                if (self::norm($t->nom) !== self::norm($l['nom'])) {
                    $this->rejeter($r, "Le matricule $matricule appartient à « {$t->nom} », pas à « {$l['nom']} ».");

                    return null;
                }

                return $t;
            }

            return null;    // matricule inconnu : la personne sera créée avec ce matricule
        }

        $nom = self::norm($l['nom']);
        $prenoms = self::norm($l['prenoms']);
        $naissance = $this->date($l['naissance'] ?? null);
        $candidats = [];
        foreach ($this->parNom[$nom] ?? [] as $t) {
            $p = self::norm($t->prenom . $t->prenom_suite);
            if ($p === $prenoms || str_starts_with($p, $prenoms) || str_starts_with($prenoms, $p)) {
                if ($naissance && $t->date_naissance && $t->date_naissance !== $naissance) {
                    continue;
                }
                $candidats[$t->id] = $t;
            }
        }
        if (count($candidats) > 1) {
            $this->rejeter($r, 'Plusieurs travailleurs correspondent (' . implode(', ', array_map(fn ($t) => $t->matricule, $candidats)) . ') : indiquer le matricule.');

            return null;
        }

        return $candidats ? Travailleur::find(array_key_first($candidats)) : null;
    }

    // ------------------------------------------------------------------ utilitaires

    private function rejeter(array &$r, string $motif): void
    {
        $r['statut'] = 'rejeté';
        $r['erreur'] = $motif;
    }

    public static function norm($s): string
    {
        $s = mb_strtoupper(trim((string) $s), 'UTF-8');
        $s = strtr($s, ['É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Î' => 'I', 'Ï' => 'I', 'Ô' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ç' => 'C']);

        return preg_replace('/[^A-Z0-9]/', '', $s);
    }

    private function indexUnique($labels): array
    {
        $par = [];
        foreach ($labels as $id => $label) {
            if ($label !== null && trim((string) $label) !== '') {
                $par[self::norm($label)][$id] = true;
            }
        }
        $idx = [];
        foreach ($par as $n => $ids) {
            if (count($ids) === 1) {
                $idx[$n] = array_key_first($ids);
            }
        }

        return $idx;
    }

    private function date($v): ?string
    {
        if ($v === null || $v === '') {
            return null;
        }
        try {
            if (is_numeric($v)) {
                return $v > 20000 ? Carbon::instance(Date::excelToDateTimeObject($v))->format('Y-m-d') : null;
            }
            $s = trim((string) $v);
            $d = preg_match('#^\d{1,2}/\d{1,2}/\d{4}$#', $s) ? Carbon::createFromFormat('d/m/Y', $s) : Carbon::parse($s);

            return $d->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function nombre($v): ?float
    {
        if ($v === null || $v === '') {
            return null;
        }
        if (is_numeric($v)) {
            return (float) $v;
        }
        $n = preg_replace('/[^\d,\.]/', '', str_replace(["\u{00A0}", ' '], '', (string) $v));
        $n = str_replace(',', '.', $n);

        return is_numeric($n) && $n !== '' ? (float) $n : null;
    }

    private function vide($v): bool
    {
        return $v === null || $v === '' || $v === 'NULL';
    }

    private function different($a, $b): bool
    {
        if ($this->vide($a) && $this->vide($b)) {
            return false;
        }
        if (is_numeric($a) && is_numeric($b)) {
            return (float) $a !== (float) $b;
        }

        return (string) $a !== (string) $b;
    }

    private function texte($v): string
    {
        return is_scalar($v) ? (string) $v : json_encode($v);
    }
}
