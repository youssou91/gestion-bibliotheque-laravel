<?php

namespace Tests\Feature;

use App\Models\Amende;
use App\Models\Emprunt;
use App\Models\Ouvrage;
use App\Models\Ouvrages;
use App\Models\Stock;
use App\Models\Stocks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpruntControllerTest extends TestCase
{
    use RefreshDatabase;

    // Test création d'emprunt
    public function test_creation_emprunt_reussie()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create(['statut' => 'disponible']);
        Stocks::factory()->create(['ouvrage_id' => $ouvrage->id, 'quantite' => 3]);

        $response = $this->actingAs($user)
            ->post('/emprunts', ['livre_id' => $ouvrage->id]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('emprunts', [
            'ouvrage_id' => $ouvrage->id,
            'utilisateur_id' => $user->id,
            'statut' => 'en_cours'
        ]);

        $this->assertEquals('emprunté', $ouvrage->fresh()->statut);
        $this->assertEquals(2, $ouvrage->stock->fresh()->quantite);
    }

    // Test échec création emprunt (déjà emprunté)
    public function test_emprunt_echoue_si_deja_emprunte()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create(['statut' => 'emprunté']);

        $response = $this->actingAs($user)
            ->post('/emprunts', ['livre_id' => $ouvrage->id]);

        $response->assertRedirect()
            ->assertSessionHas('error');
    }

    // Test retour d'emprunt sans retard
    public function test_retour_emprunt_sans_retard()
    {
        $user = User::factory()->create();
        $emprunt = Emprunt::factory()->create([
            'utilisateur_id' => $user->id,
            'date_retour' => Carbon::tomorrow(),
            'statut' => 'en_cours'
        ]);

        $response = $this->actingAs($user)
            ->post("/emprunts/{$emprunt->id}/retour");

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('retourne', $emprunt->fresh()->statut);
        $this->assertEquals(0, $emprunt->fresh()->amende);
    }

    // Test retour avec retard
    public function test_retour_avec_retard_genere_amende()
    {
        $user = User::factory()->create();
        $emprunt = Emprunt::factory()->create([
            'utilisateur_id' => $user->id,
            'date_retour' => Carbon::yesterday(),
            'statut' => 'en_retard'
        ]);

        $response = $this->actingAs($user)
            ->post("/emprunts/{$emprunt->id}/retour");

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('amendes', [
            'emprunt_id' => $emprunt->id,
            'montant' => 1.50 // 1 jour de retard
        ]);
    }

    // Test vérification automatique des retards
    public function test_verification_automatique_retards()
    {
        $empruntEnRetard = Emprunt::factory()->create([
            'date_retour' => Carbon::yesterday(),
            'statut' => 'en_cours'
        ]);

        $user = User::factory()->create();
        $this->actingAs($user)->get('/mes-emprunts');

        $this->assertEquals('en_retard', $empruntEnRetard->fresh()->statut);
    }

    // Test affichage liste emprunts
    public function test_affichage_liste_emprunts()
    {
        $user = User::factory()->create();
        Emprunt::factory()->count(3)->create(['utilisateur_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/mes-emprunts');

        $response->assertOk()
            ->assertViewHas('empruntsEnCours')
            ->assertViewHas('empruntsHistorique');
    }

    // Test tableau de bord admin
    public function test_tableau_de_bord_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Emprunt::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get('/admin/dashboard');

        $response->assertOk()
            ->assertViewHas('empruntsMois')
            ->assertViewHas('retards')
            ->assertViewHas('lastEmprunts');
    }

    // Test mise à jour statut stock
    public function test_mise_a_jour_statut_stock()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create(['statut' => 'disponible']);
        $stock = Stocks::factory()->create([
            'ouvrage_id' => $ouvrage->id,
            'quantite' => 1,
            'statut' => 'En stock'
        ]);

        $this->actingAs($user)
            ->post('/emprunts', ['livre_id' => $ouvrage->id]);

        $this->assertEquals('Rupture', $stock->fresh()->statut);
    }
}