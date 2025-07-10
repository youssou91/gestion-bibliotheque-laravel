<?php

namespace Tests\Feature;

use App\Models\Commentaire;
use App\Models\Commentaires;
use App\Models\Ouvrage;
use App\Models\Ouvrages;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentaireControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function utilisateur_peut_soumettre_commentaire()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('commentaires.store', $ouvrage->id), [
                'commentaire' => 'Excellent ouvrage!',
                'note' => 5
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('commentaires', [
            'contenu' => 'Excellent ouvrage!',
            'note' => 5,
            'ouvrage_id' => $ouvrage->id,
            'utilisateur_id' => $user->id
        ]);
    }

    /** @test */
    public function validation_soumission_commentaire()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create();

        // Test contenu vide
        $response = $this->actingAs($user)
            ->post(route('commentaires.store', $ouvrage->id), [
                'commentaire' => '',
                'note' => 5
            ]);

        $response->assertSessionHasErrors(['commentaire']);

        // Test note invalide
        $response = $this->actingAs($user)
            ->post(route('commentaires.store', $ouvrage->id), [
                'commentaire' => 'Bon livre',
                'note' => 6
            ]);

        $response->assertSessionHasErrors(['note']);
    }

    /** @test */
    public function admin_peut_voir_liste_commentaires()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Commentaires::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.commentaires.index'));

        $response->assertOk()
            ->assertViewHas('comments')
            ->assertViewHas('pendingCount')
            ->assertViewHas('approuve30')
            ->assertViewHas('rejete30');
    }

    /** @test */
    public function admin_peut_approuver_commentaire()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commentaire = Commentaires::factory()->create(['statut' => 'en_attente']);

        $response = $this->actingAs($admin)
            ->patch(route('admin.commentaires.approuve', $commentaire));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('approuve', $commentaire->fresh()->statut);
    }

    /** @test */
    public function admin_peut_rejeter_commentaire()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commentaire = Commentaires::factory()->create(['statut' => 'en_attente']);

        $response = $this->actingAs($admin)
            ->patch(route('admin.commentaires.reject', $commentaire));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('rejete', $commentaire->fresh()->statut);
    }

    /** @test */
    public function affichage_details_commentaire()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commentaire = Commentaires::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.commentaires.show', $commentaire));

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'contenu',
                'note',
                'utilisateur' => ['id', 'name'],
                'ouvrage' => ['id', 'titre']
            ]);
    }

    /** @test */
    public function statistiques_commentaires_correctes()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Commentaires::factory()->count(3)->create(['statut' => 'en_attente']);
        Commentaires::factory()->count(2)->create(['statut' => 'approuve', 'updated_at' => now()->subDays(10)]);
        Commentaires::factory()->count(1)->create(['statut' => 'rejete', 'updated_at' => now()->subDays(5)]);

        $response = $this->actingAs($admin)
            ->get(route('admin.commentaires.index'));

        $response->assertViewHas('pendingCount', 3)
            ->assertViewHas('approuve30', 2)
            ->assertViewHas('rejete30', 1);
    }
}