<?php

namespace Tests\Feature;

use App\Models\Favori;
use App\Models\Ouvrage;
use App\Models\Ouvrages;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function utilisateur_peut_voir_ses_favoris()
    {
        $user = User::factory()->create();
        $favoris = Favori::factory()->count(3)->create([
            'utilisateur_id' => $user->id
        ]);

        $response = $this->actingAs($user)
                        ->get(route('favoris.index'));

        $response->assertOk()
                 ->assertViewHas('favoris')
                 ->assertSee($favoris->first()->ouvrage->titre);
    }

    /** @test */
    public function utilisateur_peut_ajouter_un_ouvrage_aux_favoris()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create();

        $response = $this->actingAs($user)
                        ->post(route('favoris.ajouter', $ouvrage->id));

        $response->assertRedirect()
                 ->assertSessionHas('success');

        $this->assertDatabaseHas('favoris', [
            'utilisateur_id' => $user->id,
            'ouvrage_id' => $ouvrage->id
        ]);
    }

    /** @test */
    public function ajout_favori_echoue_si_non_connecte()
    {
        $ouvrage = Ouvrages::factory()->create();

        $response = $this->post(route('favoris.ajouter', $ouvrage->id));

        $response->assertRedirect()
                 ->assertSessionHas('error');
    }

    /** @test */
    public function utilisateur_peut_retirer_un_ouvrage_des_favoris()
    {
        $user = User::factory()->create();
        $favori = Favori::factory()->create([
            'utilisateur_id' => $user->id
        ]);

        $response = $this->actingAs($user)
                        ->delete(route('favoris.retirer', $favori->ouvrage_id));

        $response->assertRedirect()
                 ->assertSessionHas('success');

        $this->assertDatabaseMissing('favoris', [
            'id' => $favori->id
        ]);
    }

    /** @test */
    public function retrait_favori_echoue_si_non_connecte()
    {
        $favori = Favori::factory()->create();

        $response = $this->delete(route('favoris.retirer', $favori->ouvrage_id));

        $response->assertRedirect()
                 ->assertSessionHas('error');
    }

    /** @test */
    public function on_ne_peut_pas_ajouter_un_ouvrage_deja_en_favoris()
    {
        $user = User::factory()->create();
        $favori = Favori::factory()->create([
            'utilisateur_id' => $user->id
        ]);

        $response = $this->actingAs($user)
                        ->post(route('favoris.ajouter', $favori->ouvrage_id));

        $response->assertRedirect()
                 ->assertSessionHas('info');
    }

    /** @test */
    public function retrait_echoue_si_favori_inexistant()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create();

        $response = $this->actingAs($user)
                        ->delete(route('favoris.retirer', $ouvrage->id));

        $response->assertRedirect()
                 ->assertSessionHas('error');
    }
}