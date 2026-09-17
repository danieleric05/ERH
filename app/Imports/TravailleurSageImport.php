<?php

namespace App\Imports;

use App\Categories;
use App\Departement;
use App\Equipes;
use App\Fonction;
use App\Travailleur;
use App\TypeContrat;
use Carbon\Carbon;

class TravailleurSageImport
{
    public $created = 0;
    public $updated = 0;
    public $errors = [];

    /** @var array<string, array<string, int>> valeur CSV non résolue => [champ => nombre de lignes] */
    private $unresolved = [];

    private $departements = [];
    private $fonctions = [];
    private $equipes = [];
    private $categories = [];
    private $typeContrats = [];

    /**
     * Variantes orthographiques observées dans les exports Sage vers le label exact en base.
     * @var array<string, string>
     */
    private $fonctionAliases = [
        'AIDE MECANICIEN' => 'AIDE-MECANICIEN',
        'CONTR-LEUR QUALITE' => 'CONTROLEUR QUALITE',
        'ELECTROMECANICIEN' => 'ELECTRO-MECANICIEN',
        'INGENIEUR ELECTROMECANIQUE' => 'INGENIEUR  ELECTROMECANICIEN',
        'ASSISTANTE COMPTABLE' => 'ASSISTANT COMPTABLE',
        'ASSISTANT(E) ACHAT' => 'ASSISTANT ACHAT',
        'GESTIONNAIRE DE STOCKS' => 'GESTIONNAIRE DE STOCK',
        'ASSISTANT CONTR-LE DE GESTION' => 'ASSISTANT CONTROLE DE GESTION',
        'TECHNICIEN DE SURFACE' => 'TECHNICIEN(NE) DE SURFACE',
    ];

    /** @var array<string, string> */
    private $equipeAliases = [
        'BACHE NOIRE B' => 'BACHE NOIRE EQUIPE B',
        'BACHE NOIRE C' => 'BACHE NOIRE EQUIPE C',
    ];

    /** @var array<string, string> */
    private $typeContratAliases = [
        'PERMANENT' => 'CDI',
    ];

    public function __construct()
    {
        $this->loadReferenceTables();
    }

    private function loadReferenceTables(): void
    {
        $this->departements = $this->labelMap(Departement::all());
        $this->fonctions = $this->labelMap(Fonction::all());
        $this->equipes = $this->labelMap(Equipes::all());
        $this->typeContrats = $this->labelMap(TypeContrat::all());

        // Labels dupliqués possibles dans e_categorie : on garde le plus petit id.
        $this->categories = [];
        foreach (Categories::orderBy('id')->get() as $c) {
            $key = strtoupper(trim($c->label));
            if (!isset($this->categories[$key])) {
                $this->categories[$key] = $c->id;
            }
        }
    }

    private function labelMap($rows): array
    {
        $map = [];
        foreach ($rows as $row) {
            $map[strtoupper(trim($row->label))] = $row->id;
        }
        return $map;
    }

    /**
     * @param string $filePath
     * @param string $type 'journalier' ou 'embauche'
     * @param bool $dryRun
     */
    public function importFromFile(string $filePath, string $type, bool $dryRun = false): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Fichier non trouvé: {$filePath}");
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Impossible d'ouvrir le fichier: {$filePath}");
        }

        stream_filter_append($handle, 'convert.iconv.ISO-8859-1/UTF-8');

        $headers = fgetcsv($handle, 0, ';');
        if (!$headers) {
            fclose($handle);
            throw new \Exception("Fichier CSV vide ou non lisible");
        }
        $headers = array_map('trim', $headers);

        $lineNumber = 1;
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $lineNumber++;

            $row = array_combine($headers, array_pad($data, count($headers), null));
            if ($row === false || $this->isEmptyRow($row)) {
                continue;
            }

            try {
                $this->processRow($row, $type, $dryRun, $lineNumber);
            } catch (\Exception $e) {
                $this->errors[] = [
                    'ligne' => $lineNumber,
                    'matricule' => $row['Matricule'] ?? 'N/A',
                    'nom' => $row['Nom'] ?? 'N/A',
                    'erreur' => $e->getMessage(),
                ];
            }
        }

        fclose($handle);
    }

    private function isEmptyRow(array $row): bool
    {
        return empty(trim($row['Matricule'] ?? '')) && empty(trim($row['Nom'] ?? ''));
    }

    private function processRow(array $row, string $type, bool $dryRun, int $lineNumber): void
    {
        $matricule = strtoupper(trim($row['Matricule'] ?? ''));
        if (empty($matricule)) {
            $this->errors[] = [
                'ligne' => $lineNumber,
                'matricule' => 'N/A',
                'nom' => $row['Nom'] ?? 'N/A',
                'erreur' => 'Matricule vide',
            ];
            return;
        }

        $nom = strtoupper(trim($row['Nom'] ?? ''));
        if (empty($nom)) {
            $this->errors[] = [
                'ligne' => $lineNumber,
                'matricule' => $matricule,
                'nom' => 'N/A',
                'erreur' => 'Nom vide',
            ];
            return;
        }

        $travailleur = Travailleur::where('matricule', $matricule)->first();
        $isNew = $travailleur === null;
        if ($isNew) {
            $travailleur = new Travailleur();
            $travailleur->matricule = $matricule;
        }

        // Champs simples : le CSV Sage fait toujours foi.
        $travailleur->civilite = trim($row['Civilite'] ?? '') ?: $travailleur->civilite;
        $travailleur->nom = $nom;

        $prenoms = trim($row['Prenom'] ?? '');
        if ($prenoms !== '') {
            $prenomParts = explode(' ', $prenoms, 2);
            $travailleur->prenom = strtoupper($prenomParts[0]);
            $travailleur->prenom_suite = isset($prenomParts[1]) ? strtoupper($prenomParts[1]) : null;
        }

        $dateNaissance = $this->parseDate($row['Date de naissance'] ?? null);
        if ($dateNaissance) {
            $travailleur->date_naissance = $dateNaissance;
        }

        $dateAnciennete = $this->parseDate($row["Date d'anciennete"] ?? null);
        if ($dateAnciennete) {
            $travailleur->date_debut_contrat = $dateAnciennete;
        }

        $cnps = trim($row['N° CNPS'] ?? '');
        if ($cnps !== '') {
            $travailleur->numero_securite = $cnps;
        }

        $sitMat = trim($row['Sit. Matrimoniale'] ?? '');
        if ($sitMat !== '') {
            $travailleur->situation_mat = $sitMat;
        }

        // Clés étrangères : on n'écrase que si la valeur du CSV est résolue.
        $this->applyFK($travailleur, 'departementid', $this->departements, $row['Departement'] ?? '', 'Departement', $lineNumber);
        $this->applyFK($travailleur, 'fonction_entrepriseid', $this->fonctions, $this->resolvePoste($row['Poste'] ?? '', $row['Civilite'] ?? ''), 'Poste', $lineNumber);
        $this->applyFK($travailleur, 'equipeid', $this->equipes, $this->resolveAlias($row['Service'] ?? '', $this->equipeAliases), 'Service', $lineNumber);

        if ($type === 'embauche') {
            $this->applyFK($travailleur, 'categorieid', $this->categories, $row['Categorie'] ?? '', 'Categorie', $lineNumber);
            $this->applyFK($travailleur, 'idtype_contrat', $this->typeContrats, $this->resolveAlias($row['Nature du contrat'] ?? '', $this->typeContratAliases), 'Nature du contrat', $lineNumber);
            $travailleur->type_employer = 2;
        } else {
            $travailleur->type_employer = 1;
            $journalierId = $this->typeContrats['JOURNALIER'] ?? null;
            if ($journalierId) {
                $travailleur->idtype_contrat = $journalierId;
            }
        }

        if ($isNew) {
            $travailleur->statutid = $travailleur->statutid ?? 1;
            $travailleur->etapeid = $travailleur->etapeid ?? 2;
            $travailleur->inscrit_le = $travailleur->inscrit_le ?: Carbon::now()->toDateString();
            $travailleur->mois = $travailleur->mois ?: Carbon::now()->month;
            $travailleur->annee = $travailleur->annee ?: Carbon::now()->year;
        }

        if (!$dryRun) {
            $travailleur->save();
        }

        $isNew ? $this->created++ : $this->updated++;
    }

    private function applyFK($travailleur, string $field, array $map, string $rawValue, string $label, int $lineNumber): void
    {
        $value = strtoupper(trim($rawValue));
        if ($value === '') {
            return;
        }

        if (isset($map[$value])) {
            $travailleur->$field = $map[$value];
            return;
        }

        if (!isset($this->unresolved[$label][$rawValue])) {
            $this->unresolved[$label][$rawValue] = 0;
        }
        $this->unresolved[$label][$rawValue]++;
    }

    /**
     * Applique les alias orthographiques connus pour le champ Poste, y compris
     * le cas "COUTURIER (E)" résolu selon la civilité de la ligne.
     */
    private function resolvePoste(string $rawValue, string $civilite): string
    {
        $value = strtoupper(trim($rawValue));

        if ($value === 'COUTURIER (E)' || $value === 'COUTURIER(E)') {
            $civilite = strtoupper(trim($civilite));
            return in_array($civilite, ['MADAME', 'MADEMOISELLE'], true) ? 'COUTURIERE' : 'COUTURIER';
        }

        return $this->fonctionAliases[$value] ?? $rawValue;
    }

    /** @param array<string, string> $aliases */
    private function resolveAlias(string $rawValue, array $aliases): string
    {
        $value = strtoupper(trim($rawValue));
        return $aliases[$value] ?? $rawValue;
    }

    private function parseDate(?string $dateStr): ?string
    {
        if (empty($dateStr)) {
            return null;
        }

        $dateStr = trim($dateStr);
        if ($dateStr === '' || $dateStr === '00/01/1900') {
            return null;
        }

        try {
            return Carbon::createFromFormat('d/m/Y', $dateStr)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSummary(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'errors' => $this->errors,
            'unresolved' => $this->unresolved,
            'total' => $this->created + $this->updated + count($this->errors),
        ];
    }
}
