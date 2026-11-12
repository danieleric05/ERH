<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProfilSettingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('user');
        Schema::create('user', function ($table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('pseudo')->nullable();
            $table->string('password');
            $table->integer('idrole')->nullable();
            $table->integer('statut_id')->default(1);
            $table->dateTime('derniere_cnx')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    private function makeUser(int $role): User
    {
        return User::create([
            'name' => 'Test',
            'pseudo' => 'TEST'.$role,
            'password' => Hash::make('ancien-mdp'),
            'idrole' => $role,
            'statut_id' => 1,
        ]);
    }

    public function test_settings_accessible_aux_roles_1_et_2(): void
    {
        $this->actingAs($this->makeUser(1))->get(route('settings'))->assertOk();
        $this->actingAs($this->makeUser(2))->get(route('settings'))->assertOk();
    }

    public function test_settings_refuse_au_role_3(): void
    {
        $this->actingAs($this->makeUser(3))->get(route('settings'))->assertForbidden();
    }

    public function test_changement_de_mot_de_passe(): void
    {
        $user = $this->makeUser(3);

        $this->actingAs($user)->post(route('profil.mot-de-passe'), [
            'current_password' => 'ancien-mdp',
            'password' => 'nouveau-mdp',
            'password_confirmation' => 'nouveau-mdp',
        ])->assertRedirect(route('mon-profil'));

        $this->assertTrue(Hash::check('nouveau-mdp', $user->fresh()->password));
    }

    public function test_changement_refuse_si_ancien_mot_de_passe_faux(): void
    {
        $user = $this->makeUser(1);

        $this->actingAs($user)->post(route('profil.mot-de-passe'), [
            'current_password' => 'faux',
            'password' => 'nouveau-mdp',
            'password_confirmation' => 'nouveau-mdp',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('ancien-mdp', $user->fresh()->password));
    }
}
