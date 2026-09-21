<?php

namespace App\Services;

use App\Categories;
use App\Fonction;
use App\Pays;
use App\Unites;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/** Génère le classeur modèle de l'import (mêmes colonnes que le Répertoire CDD), avec listes déroulantes. */
class ModeleImportContrats
{
    public const ENTETES = ['ODRE', 'Civilité', 'NOM', 'PRENOMS', 'DateNaissance', 'LieuNaissance', 'Nationalite', 'Sit.Matrimoniale', 'NbreEnfant', 'N°CNPS',
        'NaturePièce', 'CNI', 'DateCNI', 'lieuCNI', 'LIeuHabitation', 'Téléphone', 'Matricule', 'Fonction', 'NbreMois', 'AncienneDateFin', 'DateDébut', 'DateFin',
        'MoisEssai', 'DateEssai', 'Catégorie', 'type', 'salaire', 'Sursalaire', 'SalaireF', 'transport', 'CONTRAT', 'NBRE CDD', 'Date Entrée', 'Total Période CDD',
        'OBSERVATION', 'n°', 'UNITE', 'Evaluateur', 'DEBUT JOURNALIER'];

    private const LIGNES = 300;

    public function construire(): Spreadsheet
    {
        $wb = new Spreadsheet();
        $ws = $wb->getSheet(0);
        $ws->setTitle('REPERTOIRE CDD 2026');
        $ls = new Worksheet($wb, 'Listes');
        $wb->addSheet($ls, 1);
        $notice = new Worksheet($wb, 'Notice');
        $wb->addSheet($notice, 2);

        $listes = [
            'Civilité' => ['MONSIEUR', 'MADAME', 'MADEMOISELLE'],
            'Sit.Matrimoniale' => ['CELIBATAIRE', 'MARIE(E)', 'VEUF(VE)'],
            'Nationalite' => Pays::orderBy('nationalite')->pluck('nationalite')->map(fn ($v) => trim((string) $v))->filter()->unique()->values()->all(),
            'CONTRAT' => array_keys(ImportRepertoireContrats::ID_TYPE),
            'UNITE' => Unites::orderBy('label')->pluck('label')->map(fn ($v) => trim((string) $v))->filter()->unique()->values()->all(),
            'Fonction' => Fonction::orderBy('label')->pluck('label')->map(fn ($v) => trim((string) $v))->filter()->unique()->values()->all(),
            'Catégorie' => Categories::orderBy('label')->pluck('label')->map(fn ($v) => trim((string) $v))->filter()->unique()->values()->all(),
            'NaturePièce' => ['CNI', 'PASSEPORT', 'ATTESTATION', 'CARTE CONSULAIRE'],
            'type' => ['mensuelle nette', 'salaire de base + sursalaire'],
        ];
        $plages = [];
        $c = 1;
        foreach ($listes as $titre => $valeurs) {
            $L = Coordinate::stringFromColumnIndex($c++);
            $ls->setCellValue($L . '1', $titre);
            foreach ($valeurs as $i => $v) {
                $ls->setCellValueExplicit($L . ($i + 2), $v, 's');
            }
            $plages[$titre] = "Listes!\${$L}\$2:\${$L}\$" . (count($valeurs) + 1);
            $ls->getColumnDimension($L)->setAutoSize(true);
        }
        $ls->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($listes)) . '1')->getFont()->setBold(true);

        $ws->fromArray(self::ENTETES, null, 'A1');
        $derniere = Coordinate::stringFromColumnIndex(count(self::ENTETES));
        $ws->getStyle("A1:{$derniere}1")->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']]]);
        $ws->getStyle("A1:{$derniere}1")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getRowDimension(1)->setRowHeight(32);
        $ws->freezePane('E2');

        $fin = self::LIGNES + 1;
        foreach (self::ENTETES as $i => $titre) {
            $L = Coordinate::stringFromColumnIndex($i + 1);
            $ws->getColumnDimension($L)->setWidth(in_array($titre, ['PRENOMS', 'OBSERVATION', 'SalaireF']) ? 30 : (in_array($titre, ['NOM', 'UNITE', 'Fonction', 'Nationalite', 'CONTRAT']) ? 20 : 15));
            if (isset($plages[$titre])) {
                $dv = new DataValidation();
                $dv->setType(DataValidation::TYPE_LIST)->setAllowBlank(true)->setShowDropDown(false)->setShowErrorMessage(true)
                    ->setErrorTitle('Valeur non reconnue')->setError('Choisissez une valeur de la liste (feuille « Listes »).')->setFormula1($plages[$titre]);
                for ($r = 2; $r <= $fin; $r++) {
                    $ws->getCell("$L$r")->setDataValidation(clone $dv);
                }
            }
            if (preg_match('/^(Date|Ancienne|DEBUT)/i', $titre)) {
                $ws->getStyle("{$L}2:{$L}$fin")->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            }
            if (in_array($titre, ['Téléphone', 'N°CNPS', 'CNI', 'Matricule'])) {
                $ws->getStyle("{$L}2:{$L}$fin")->getNumberFormat()->setFormatCode('@');
            }
        }

        $lignes = [
            "MODÈLE D'IMPORT DES TRAVAILLEURS ET CONTRATS (stagiaires, CDD, CDI, journaliers)", '',
            'Une ligne = un contrat. Une même personne peut avoir plusieurs lignes (CDD successifs).', '',
            'OBLIGATOIRE : NOM, PRENOMS, DateDébut. Avec DateFin (sauf CDI).', '',
            'MATRICULE :',
            '   • Laisser vide (ou « STAGE ») pour une nouvelle personne : ERH attribue le matricule (S pour un stage, E pour un CDD/CDI, J pour un journalier).',
            '   • Renseigner le matricule pour une personne déjà dans ERH. Un matricule qui appartient à un autre nom est refusé.', '',
            'CONTRAT : STAGE, STAGE ECOLE, STAGE DE QUALIFICATION, CDD, CDI ou JOURNALIER. Si vide : STAGE quand la fonction ou le matricule indique « stagiaire », sinon CDD.', '',
            'PERSONNE DÉJÀ DANS ERH :',
            '   • Identité (naissance, pièce, téléphone…) : complétée si vide, jamais écrasée.',
            '   • Même date de début que le contrat en cours : le contrat est mis à jour avec la ligne.',
            '   • Date de début plus récente : reconduction (étape 6 et historique du contrat).',
            '   • Date de début plus ancienne : ajoutée à l\'historique, la fiche en cours n\'est pas modifiée.', '',
            'RÉMUNÉRATION : « type » = mensuelle nette (un seul montant dans salaire) ou salaire de base + sursalaire. Montants en chiffres (100 000 ou 100000 F).',
            'TRANSPORT : un montant, ou « y compris » si la prime est incluse dans le salaire.', '',
            'LISTES : Fonction, UNITE, Catégorie, Nationalité doivent correspondre exactement aux listes d\'ERH (menus déroulants). Une valeur inconnue n\'est pas devinée : la ligne est importée sans elle et signalée.', '',
            'Dates au format jj/mm/aaaa. Toujours commencer par une SIMULATION : rien n\'est enregistré tant que la case « Simulation » est cochée.',
        ];
        foreach ($lignes as $i => $t) {
            $notice->setCellValue('A' . ($i + 1), $t);
        }
        $notice->getColumnDimension('A')->setWidth(150);
        $notice->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $wb->setActiveSheetIndex(0);

        return $wb;
    }
}
