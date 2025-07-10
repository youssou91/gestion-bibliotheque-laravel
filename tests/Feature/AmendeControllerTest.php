<?php

namespace Tests\Feature;

use App\Models\Amende;
use App\Models\Emprunt;
use App\Models\Ouvrage;
use App\Models\Ouvrages;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AmendeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function affichage_liste_amendes_avec_filtres()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $amendePayee = Amende::factory()->create(['statut' => 'payee']);
        $amendeImpayee = Amende::factory()->create(['statut' => 'impayee']);

        // Test sans filtre
        $response = $this->actingAs($admin)
                        ->get(route('admin.amendes'));
        
        $response->assertOk()
                 ->assertViewHas('amendes')
                 ->assertSee($amendePayee->motif)
                 ->assertSee($amendeImpayee->motif);

        // Test avec filtre statut
        $response = $this->actingAs($admin)
                        ->get(route('admin.amendes', ['statut' => 'payee']));
        
        $response->assertOk()
                 ->assertSee($amendePayee->motif)
                 ->assertDontSee($amendeImpayee->motif);

        // Test avec recherche
        $response = $this->actingAs($admin)
                        ->get(route('admin.amendes', [
                            'search' => $amendePayee->utilisateur->nom
                        ]));
        
        $response->assertOk()
                 ->assertSee($amendePayee->motif);
    }

    /** @test */
    public function creation_amende_par_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create();
        $emprunt = Emprunt::factory()->create();

        $response = $this->actingAs($admin)
                        ->post(route('admin.amendes.store'), [
                            'utilisateur_id' => $user->id,
                            'ouvrage_id' => $ouvrage->id,
                            'emprunt_id' => $emprunt->id,
                            'montant' => 10.50,
                            'motif' => 'Retard de retour'
                        ]);

        $response->assertRedirect()
                ->assertSessionHas('success');

        $this->assertDatabaseHas('amendes', [
            'montant' => 10.50,
            'motif' => 'Retard de retour',
            'statut' => 'impayee'
        ]);
    }

    /** @test */
    public function mise_a_jour_amende()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $amende = Amende::factory()->create(['montant' => 5.00]);
        $newUser = User::factory()->create();

        $response = $this->actingAs($admin)
                        ->put(route('admin.amendes.update', $amende), [
                            'utilisateur_id' => $newUser->id,
                            'ouvrage_id' => $amende->ouvrage_id,
                            'montant' => 7.50,
                            'motif' => 'Nouveau motif',
                            'statut' => 'payee'
                        ]);

        $response->assertRedirect()
                ->assertSessionHas('success');

        $this->assertDatabaseHas('amendes', [
            'id' => $amende->id,
            'montant' => 7.50,
            'motif' => 'Nouveau motif',
            'statut' => 'payee'
        ]);
    }

    /** @test */
    public function suppression_amende()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $amende = Amende::factory()->create();

        $response = $this->actingAs($admin)
                        ->delete(route('admin.amendes.destroy', $amende));

        $response->assertRedirect()
                ->assertSessionHas('success');

        $this->assertDatabaseMissing('amendes', ['id' => $amende->id]);
    }

    /** @test */
    public function calcul_statistiques_amendes()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Amende::factory()->create(['montant' => 10.00, 'statut' => 'payee']);
        Amende::factory()->create(['montant' => 15.50, 'statut' => 'impayee']);
        Amende::factory()->create(['montant' => 5.00, 'statut' => 'payee']);

        $response = $this->actingAs($admin)
                        ->get(route('admin.amendes'));

        $response->assertViewHas('stats', [
            'total' => 3,
            'payees' => 2,
            'impayees' => 1,
            'montant_total' => 30.50,
            'montant_paye' => 15.00,
            'montant_impaye' => 15.50
        ]);
    }

    /** @test */
    public function validation_creation_amende()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Test montant négatif
        $response = $this->actingAs($admin)
                        ->post(route('admin.amendes.store'), [
                            'utilisateur_id' => 999, // Invalide
                            'ouvrage_id' => 999, // Invalide
                            'montant' => -5.00
                        ]);

        $response->assertSessionHasErrors([
            'utilisateur_id',
            'ouvrage_id',
            'montant'
        ]);
    }
}