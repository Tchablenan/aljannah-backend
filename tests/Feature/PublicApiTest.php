<?php

namespace Tests\Feature;

use App\Models\Jet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeJet(array $attributes = []): Jet
    {
        return Jet::create(array_merge([
            'nom' => 'Hawker 800XP',
            'modele' => '800XP',
            'capacite' => 8,
            'description' => 'Jet de test',
            'prix' => 3500,
            'disponible' => true,
            'localisation' => 'Accra',
            'categorie' => 'Midsize',
        ], $attributes));
    }

    private function reservationPayload(Jet $jet, array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean@example.com',
            'phone' => '+233000000',
            'departure_location' => 'Accra',
            'arrival_location' => 'Lomé',
            'departure_date' => now()->addDays(5)->toDateString(),
            'arrival_date' => now()->addDays(7)->toDateString(),
            'passengers' => 2,
            'jet_id' => $jet->id,
            'message' => null,
            'data_protection_consent' => true,
        ], $overrides);
    }

    public function test_static_jet_routes_are_not_shadowed_by_show(): void
    {
        $this->makeJet();

        $this->getJson('/api/jets/search')->assertOk()->assertJsonStructure(['data', 'pagination']);
        $this->getJson('/api/jets/categories')->assertOk()->assertJsonFragment(['categorie' => 'Midsize']);
        $this->getJson('/api/jets/price-range')->assertOk()->assertJsonStructure(['min_prix', 'max_prix']);
    }

    public function test_jet_list_includes_description(): void
    {
        $this->makeJet();

        $this->getJson('/api/jets')
            ->assertOk()
            ->assertJsonPath('data.0.description', 'Jet de test');
    }

    public function test_reservation_is_created_and_status_returns_stored_reference(): void
    {
        Notification::fake();
        $jet = $this->makeJet();

        $response = $this->postJson('/api/reservations', $this->reservationPayload($jet))
            ->assertCreated()
            ->assertJsonPath('success', true);

        $id = $response->json('data.reservation_id');
        $reference = $response->json('data.reference');
        $this->assertSame('REF-' . str_pad($id, 6, '0', STR_PAD_LEFT), $reference);

        $this->getJson("/api/reservations/{$id}/status?email=jean@example.com")
            ->assertOk()
            ->assertJsonPath('data.reference', $reference);
    }

    public function test_cors_allows_the_vercel_frontend(): void
    {
        $this->makeJet();

        foreach (['https://projet-aljannah.vercel.app', 'https://projet-aljannah-git-main-tchablenan.vercel.app'] as $origin) {
            $this->getJson('/api/jets', ['Origin' => $origin])
                ->assertOk()
                ->assertHeader('Access-Control-Allow-Origin', $origin);
        }

        $this->getJson('/api/jets', ['Origin' => 'https://evil.vercel.app'])
            ->assertHeaderMissing('Access-Control-Allow-Origin');
    }

    public function test_reservation_requires_data_protection_consent(): void
    {
        $jet = $this->makeJet();

        $this->postJson('/api/reservations', $this->reservationPayload($jet, ['data_protection_consent' => false]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('data_protection_consent');
    }
}
