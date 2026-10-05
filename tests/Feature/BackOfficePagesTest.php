<?php

namespace Tests\Feature;

use App\Models\Jet;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Vérifie que les pages du back-office s'affichent avec des données.
 * À lancer aussi sur PostgreSQL / MySQL : plusieurs requêtes y sont écrites en SQL brut.
 */
class BackOfficePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $jet = Jet::create([
            'nom' => 'Hawker 800XP', 'modele' => '800XP', 'capacite' => 8,
            'description' => 'Jet de test', 'prix' => 3500, 'disponible' => true,
            'localisation' => 'Accra', 'categorie' => 'Midsize',
        ]);

        foreach (['pending', 'confirmed', 'cancelled'] as $i => $status) {
            Reservation::create([
                'first_name' => 'Client', 'last_name' => "N{$i}", 'email' => "c{$i}@example.com",
                'departure_location' => 'Accra', 'arrival_location' => 'Lomé',
                'departure_date' => now()->addDays($i + 1), 'arrival_date' => now()->addDays($i + 2),
                'passengers' => 2, 'jet_id' => $jet->id, 'status' => $status,
            ]);
        }

        $serviceId = DB::table('luxury_services')->insertGetId([
            'nom' => 'Limousine', 'categorie' => 'transport_luxe', 'description' => 'Transfert',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('luxury_packages')->insert([
            'nom' => 'Pack test', 'description' => 'Pack',
            'services_inclus' => json_encode([['service_id' => $serviceId, 'quantite' => 1]]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('luxury_package_requests')->insert([
            'client_prenom' => 'Jean', 'client_nom' => 'Dupont', 'client_email' => 'jean@example.com',
            'titre_demande' => 'Séjour', 'description_demande' => 'Demande',
            'services_souhaites' => json_encode([['service_id' => $serviceId, 'quantite' => 1]]),
            'date_debut_souhaitee' => now()->addWeek(), 'destination_principale' => 'Accra',
            'nombre_personnes' => 2, 'statut' => 'nouvelle', 'reference' => 'REF-LUX-000001',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public static function pages(): array
    {
        return [
            ['/dashboard'],
            ['/reservations'],
            ['/reservations/create'],
            ['/reservations/{reservation}'],
            ['/reservations/{reservation}/edit'],
            ['/jets'],
            ['/jets/create'],
            ['/jets/{jet}'],
            ['/jets/{jet}/edit'],
            ['/luxury-dashboard'],
            ['/luxury-services'],
            ['/luxury-services/create'],
            ['/luxury-packages'],
            ['/luxury-packages/create'],
            ['/luxury-requests'],
        ];
    }

    /**
     * @dataProvider pages
     */
    public function test_back_office_page_renders(string $uri): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Les identifiants varient selon la base (séquences PostgreSQL non réinitialisées)
        $uri = strtr($uri, [
            '{reservation}' => Reservation::first()->id,
            '{jet}' => Jet::first()->id,
        ]);

        $response = $this->actingAs($admin)->get($uri);

        $this->assertSame(200, $response->status(), "{$uri} : " . substr(trim(preg_replace("/\\s+/", " ", strip_tags((string) $response->getContent()))), 0, 300));
    }

    /**
     * @dataProvider pages
     */
    public function test_back_office_page_is_forbidden_for_non_admins(string $uri): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $uri = strtr($uri, ['{reservation}' => Reservation::first()->id, '{jet}' => Jet::first()->id]);

        $this->actingAs($user)->get($uri)->assertForbidden();
    }

    public function test_back_office_redirects_guests_to_login(): void
    {
        $this->get('/reservations')->assertRedirect('/login');
    }

    public function test_public_jet_api_works_on_this_database(): void
    {
        $this->getJson('/api/jets/categories')->assertOk();
        $this->getJson('/api/jets/price-range')->assertOk();
        $this->getJson('/api/jets/search?passengers=2')->assertOk();
    }
}
