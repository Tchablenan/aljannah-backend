<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Jet;
use App\Models\Reservation;
use App\Services\TaxCalculator;

$jet = Jet::find(1);
if (!$jet) {
    echo "Jet not found\n";
    exit(1);
}

$taxCalculator = new TaxCalculator();
$taxDetails = $taxCalculator->calculateGhanaTaxes((float)$jet->prix);

$reservation = Reservation::create([
    'first_name' => 'Test',
    'last_name' => 'Tinker',
    'email' => 'tinker_script@test.com',
    'phone' => '+123456789',
    'departure_location' => 'Lomé',
    'arrival_location' => 'Accra',
    'departure_date' => '2026-06-10',
    'arrival_date' => '2026-06-15',
    'passengers' => 2,
    'jet_id' => $jet->id,
    'status' => 'pending',
    'passport_number' => 'SCRIPT12345',
    'passport_expiry' => '2030-01-01',
    'date_of_birth' => '1990-01-01',
    'nationality' => 'Togolese',
    'luggage_count' => 3,
    'luggage_weight_kg' => 60.0,
    'data_protection_consent' => true,
    'base_price' => $taxDetails['base_price'],
    'nhil_amount' => $taxDetails['nhil'],
    'getfund_amount' => $taxDetails['get_fund'],
    'covid_levy_amount' => $taxDetails['covid_levy'],
    'vat_amount' => $taxDetails['vat'],
    'total_taxes_amount' => $taxDetails['total_taxes'],
    'total_amount_with_taxes' => $taxDetails['total_amount'],
]);

echo "--- Réservation Créée via Script ---\n";
echo "ID: " . $reservation->id . "\n";
echo "Référence: " . $reservation->reference . "\n";
echo "Passeport: " . $reservation->passport_number . "\n";
echo "Bagages: " . $reservation->luggage_count . " sacs / " . $reservation->luggage_weight_kg . " kg\n";
echo "Prix de base ($): " . $reservation->base_price . "\n";
echo "Total avec taxes ($): " . $reservation->total_amount_with_taxes . "\n";
echo "-----------------------------------\n";