<?php

namespace Tests\Unit;

use App\Travailleur;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TravailleurAncienneteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-11-10');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function travailleurExistant(array $originaux): Travailleur
    {
        return (new Travailleur)->setRawAttributes($originaux, true);
    }

    public function test_anciennete_depuis_date_entree(): void
    {
        $t = new Travailleur;
        $t->date_entree = '2023-09-01';
        $t->date_debut_contrat = '2025-01-01';

        $this->assertSame('3 ans 2 mois', $t->anciennete);
    }

    public function test_anciennete_singulier_et_repli_sur_debut_contrat(): void
    {
        $t = new Travailleur;
        $t->date_debut_contrat = '2025-11-10';

        $this->assertSame('1 an', $t->anciennete);
    }

    public function test_anciennete_arretee_a_la_fin_de_contrat_passee(): void
    {
        $t = new Travailleur;
        $t->date_debut_contrat = '2024-01-01';
        $t->date_fin_contrat = '2025-03-15';

        $this->assertSame('1 an 2 mois', $t->anciennete);
    }

    public function test_anciennete_moins_d_un_mois_et_sans_date(): void
    {
        $t = new Travailleur;
        $this->assertNull($t->anciennete);

        $t->date_debut_contrat = '2026-11-01';
        $this->assertSame("moins d'un mois", $t->anciennete);
    }

    public function test_anciennete_ignore_dates_zero_et_debut_futur(): void
    {
        $t = new Travailleur;
        $t->date_entree = '0000-00-00';
        $t->date_fin_contrat = '0000-00-00';
        $this->assertNull($t->anciennete);

        $t->date_debut_contrat = '2025-11-10';
        $this->assertSame('1 an', $t->anciennete);

        $t->date_fin_contrat = '0001-01-01';
        $this->assertSame('1 an', $t->anciennete);

        $t->date_fin_contrat = '2024-01-01';
        $this->assertSame('1 an', $t->anciennete);

        $t->date_debut_contrat = '0001-01-01';
        $this->assertNull($t->anciennete);

        $t->date_debut_contrat = '2027-01-01';
        $this->assertNull($t->anciennete);
    }

    public function test_passage_cdd_vers_cdi_remplit_avec_debut_de_contrat_modifie(): void
    {
        $t = $this->travailleurExistant(['idtype_contrat' => 2, 'date_debut_contrat' => '2025-01-01']);
        $t->idtype_contrat = 3;
        $t->date_debut_contrat = '2026-06-01';
        $t->renseignerDatePassageCdi();

        $this->assertSame('2026-06-01', $t->date_passage_cdi);
    }

    public function test_passage_cdd_vers_cdi_sans_changement_de_debut_prend_aujourd_hui(): void
    {
        $t = $this->travailleurExistant(['idtype_contrat' => 2, 'date_debut_contrat' => '2025-01-01']);
        $t->idtype_contrat = '3';
        $t->renseignerDatePassageCdi();

        $this->assertSame('2026-11-10', $t->date_passage_cdi);
    }

    public function test_date_saisie_conservee(): void
    {
        $t = $this->travailleurExistant(['idtype_contrat' => 2]);
        $t->idtype_contrat = 3;
        $t->date_passage_cdi = '2026-02-02';
        $t->renseignerDatePassageCdi();

        $this->assertSame('2026-02-02', $t->date_passage_cdi);
    }

    public function test_cdi_vers_cdi_et_creation_directe_en_cdi_ne_remplissent_rien(): void
    {
        $t = $this->travailleurExistant(['idtype_contrat' => 3]);
        $t->idtype_contrat = 3;
        $t->renseignerDatePassageCdi();
        $this->assertNull($t->date_passage_cdi);

        $nouveau = new Travailleur;
        $nouveau->idtype_contrat = 3;
        $nouveau->renseignerDatePassageCdi();
        $this->assertNull($nouveau->date_passage_cdi);
    }
}
