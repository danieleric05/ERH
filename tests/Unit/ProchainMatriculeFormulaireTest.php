<?php

namespace Tests\Unit;

use App\Travailleur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProchainMatriculeFormulaireTest extends TestCase
{
    public function test_numeros_compares_en_entier_et_motif_exact(): void
    {
        Schema::create('e_travailleur', function ($t) {
            $t->increments('id');
            $t->string('matricule');
        });
        foreach (['J0009', 'J00010', 'J000X', 'XJ00099', 'E012', 'E0100', 'E1'] as $m) {
            DB::table('e_travailleur')->insert(['matricule' => $m]);
        }

        $this->assertSame('J00011', Travailleur::prochainMatriculeFormulaire('J'));
        $this->assertSame('E0101', Travailleur::prochainMatriculeFormulaire('E'));
    }
}
