<?php

namespace App\Console\Commands;

use App\Departement;
use App\Equipes;
use App\Fonction;
use App\Travailleur;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTravailleursFromCdd extends Command
{
    protected $signature = 'travailleurs:update-cdd {file} {--dry-run}';

    protected $description = "Met à jour les travailleurs existants (par matricule) depuis un export CSV type CDD.csv, sans créer de doublons";

    private array $departementsByLabel = [];
    private array $equipesByLabel = [];
    private array $fonctionsByLabel = [];

    public function handle(): int
    {
        $file = $this->argument('file');
        $dryRun = $this->option('dry-run');

        if (!file_exists($file)) {
            $this->error("Fichier non trouvé: {$file}");
            return self::FAILURE;
        }

        $this->loadReferenceTables();

        $handle = fopen($file, 'r');
        stream_filter_append($handle, 'convert.iconv.ISO-8859-1/UTF-8');

        $headers = array_map('trim', fgetcsv($handle, 0, ';'));

        $updated = 0;
        $unchanged = 0;
        $notFound = 0;
        $notFoundList = [];
        $lineNumber = 1;

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $lineNumber++;
            $row = array_combine($headers, array_pad($data, count($headers), null));
            if ($row === false) {
                continue;
            }

            $matricule = trim($row['Matricule'] ?? '');
            if (empty($matricule)) {
                continue;
            }

            $travailleur = Travailleur::where('matricule', $matricule)->first();

            if (!$travailleur) {
                $notFound++;
                $notFoundList[] = $matricule;
                continue;
            }

            $changes = $this->buildChanges($travailleur, $row);

            if (empty($changes)) {
                $unchanged++;
                continue;
            }

            if ($dryRun) {
                $this->line("[DRY-RUN] {$matricule}: " . json_encode($changes, JSON_UNESCAPED_UNICODE));
            } else {
                foreach ($changes as $field => [$old, $new]) {
                    $travailleur->$field = $new;
                }
                $travailleur->save();
                Log::info("Travailleur mis à jour ({$matricule}) ligne {$lineNumber}: " . json_encode($changes, JSON_UNESCAPED_UNICODE));
            }

            $updated++;
        }

        fclose($handle);

        $this->newLine();
        $this->info("📊 RÉSUMÉ" . ($dryRun ? " (dry-run, rien n'a été écrit)" : ""));
        $this->line("  ✅ Mis à jour : {$updated}");
        $this->line("  ⏭️  Inchangés : {$unchanged}");
        $this->line("  ❌ Matricule introuvable : {$notFound}");

        if ($notFound > 0) {
            $this->newLine();
            $this->line("Matricules introuvables (premiers 20): " . implode(', ', array_slice($notFoundList, 0, 20)));
        }

        return self::SUCCESS;
    }

    private function loadReferenceTables(): void
    {
        foreach (Departement::all() as $d) {
            $this->departementsByLabel[strtoupper(trim($d->label))] = $d->id;
        }
        foreach (Equipes::all() as $e) {
            $this->equipesByLabel[strtoupper(trim($e->label))] = $e->id;
        }
        foreach (Fonction::all() as $f) {
            $this->fonctionsByLabel[strtoupper(trim($f->label))] = $f->id;
        }
    }

    private function buildChanges(Travailleur $travailleur, array $row): array
    {
        $changes = [];

        $this->maybeSet($changes, $travailleur, 'telephone', trim($row['Téléphone portable professionnel'] ?? ''));
        $this->maybeSet($changes, $travailleur, 'email', trim($row['Adresse email professionnelle'] ?? ''));
        $this->maybeSet($changes, $travailleur, 'situation_mat', trim($row['Situation familiale'] ?? ''));
        $this->maybeSetNumeric($changes, $travailleur, 'nombre_enfant', trim($row['Enfants renseignés'] ?? ''));
        $this->maybeSet($changes, $travailleur, 'numero_securite', trim($row['Numéro de Sécurité Sociale'] ?? ''));

        $sexe = trim($row['Sexe'] ?? '');
        if (in_array($sexe, ['Monsieur', 'Madame', 'Mademoiselle'])) {
            $this->maybeSet($changes, $travailleur, 'civilite', $sexe);
        }

        $dateNaissance = $this->parseDate($row['Date de naissance'] ?? null);
        if ($dateNaissance) {
            $this->maybeSet($changes, $travailleur, 'date_naissance', $dateNaissance);
        }

        $fonctionLabel = strtoupper(trim($row['Emploi occupé'] ?? ''));
        if ($fonctionLabel !== '' && isset($this->fonctionsByLabel[$fonctionLabel])) {
            $this->maybeSet($changes, $travailleur, 'fonction_entrepriseid', $this->fonctionsByLabel[$fonctionLabel]);
        }

        $departementLabel = strtoupper(trim($row['Intitulé département'] ?? ''));
        if ($departementLabel !== '' && isset($this->departementsByLabel[$departementLabel])) {
            $this->maybeSet($changes, $travailleur, 'departementid', $this->departementsByLabel[$departementLabel]);
        }

        $serviceLabel = strtoupper(trim($row['Intitulé service'] ?? ''));
        if ($serviceLabel !== '' && isset($this->equipesByLabel[$serviceLabel])) {
            $this->maybeSet($changes, $travailleur, 'equipeid', $this->equipesByLabel[$serviceLabel]);
        }

        return $changes;
    }

    private function maybeSet(array &$changes, Travailleur $travailleur, string $field, $newValue): void
    {
        if ($newValue === '' || $newValue === null) {
            return;
        }

        $oldValue = $travailleur->$field;

        if ((string) $oldValue !== (string) $newValue) {
            $changes[$field] = [$oldValue, $newValue];
        }
    }

    private function maybeSetNumeric(array &$changes, Travailleur $travailleur, string $field, $newValue): void
    {
        if ($newValue === '' || $newValue === null || !is_numeric($newValue)) {
            return;
        }

        $oldValue = $travailleur->$field;

        if (!is_numeric($oldValue) || intval($oldValue) !== intval($newValue)) {
            $changes[$field] = [$oldValue, intval($newValue)];
        }
    }

    private function parseDate(?string $dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d/m/Y', trim($dateString))->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }
}
