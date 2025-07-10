<?php

namespace Tests\Feature;

use App\Models\Emprunt;
use App\Models\Ouvrage;
use App\Models\Ouvrages;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Utilisateurs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function utilisateur_peut_creer_reservation()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create();
        
        $response = $this->actingAs($user)
                        ->post(route('frontOffice.reservations'), [
                            'ouvrage_id' => $ouvrage->id
                        ]);

        $response->assertRedirect()
                ->assertSessionHas('success');
        
        $this->assertDatabaseHas('reservations', [
            'ouvrage_id' => $ouvrage->id,
            'utilisateur_id' => $user->id,
            'statut' => 'en_attente'
        ]);
    }

    /** @test */
    public function creation_reservation_echoue_si_utilisateur_a_deja_un_emprunt_en_cours()
    {
        $user = User::factory()->create();
        $ouvrage = Ouvrages::factory()->create();
        Emprunt::factory()->create([
            'ouvrage_id' => $ouvrage->id,
            'utilisateur_id' => $user->id,
            'statut' => 'en_cours'
        ]);

        $response = $this->actingAs($user)
                        ->post(route('frontOffice.reservations'), [
                            'ouvrage_id' => $ouvrage->id
                        ]);

        $response->assertRedirect()
                ->assertSessionHas('error');
    }

    /** @test */
    public function utilisateur_peut_annuler_reservation_en_attente()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'utilisateur_id' => $user->id,
            'statut' => 'en_attente'
        ]);

        $response = $this->actingAs($user)
                        ->delete(route('admin.reservations', $reservation));

        $response->assertRedirect()
                ->assertSessionHas('success');
        
        $this->assertEquals('annulee', $reservation->fresh()->statut);
    }

    /** @test */
    public function admin_peut_valider_reservation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reservation = Reservation::factory()->create(['statut' => 'en_attente']);
        $ouvrage = Ouvrages::factory()->create();
        $reservation->ouvrage()->associate($ouvrage)->save();

        $response = $this->actingAs($admin)
                        ->patch(route('admin.reservations.valider', $reservation));

        $response->assertRedirect()
                ->assertSessionHas('success');
        
        $this->assertEquals('validee', $reservation->fresh()->statut);
        $this->assertDatabaseHas('emprunts', [
            'ouvrage_id' => $ouvrage->id,
            'utilisateur_id' => $reservation->utilisateur_id
        ]);
    }

    /** @test */
    public function utilisateur_peut_voir_ses_reservations()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['utilisateur_id' => $user->id]);

        $response = $this->actingAs($user)
                        ->get(route('frontOffice.reservations'));

        $response->assertOk()
                ->assertViewHas('reservationsActives');
    }

    /** @test */
    public function utilisateur_ne_peut_pas_annuler_reservation_validee()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'utilisateur_id' => $user->id,
            'statut' => 'validee'
        ]);

        $response = $this->actingAs($user)
                        ->delete(route('admin.reservations', $reservation));

        $response->assertRedirect()
                ->assertSessionHas('error');
    }

    /** @test */
    public function limite_de_3_reservations_en_attente()
    {
        $user = User::factory()->create();
        Reservation::factory()->count(3)->create([
            'utilisateur_id' => $user->id,
            'statut' => 'en_attente'
        ]);
        $ouvrage = Ouvrages::factory()->create();

        $response = $this->actingAs($user)
                        ->post(route('frontOffice.reservations'), [
                            'ouvrage_id' => $ouvrage->id
                        ]);

        $response->assertRedirect()
                ->assertSessionHas('error');
    }
}
