<?php

namespace App\Services;

class TaxCalculator
{
    /**
     * Ghana Revenue Authority (GRA) Tax Rates (2024/2025)
     * Note: These are applicable to standard business transactions.
     */
    public const VAT_RATE = 0.15; // Value Added Tax
    public const NHIL_RATE = 0.025; // National Health Insurance Levy
    public const GETFUND_RATE = 0.025; // GETFund Levy
    public const COVID_LEVY_RATE = 0.01; // COVID-19 Health Recovery Levy

    /**
     * Calculate the total tax amount for a given base price.
     * In Ghana, NHIL, GETFund, and COVID Levy are usually calculated on the base price,
     * and VAT is calculated on the (Base Price + NHIL + GETFund + COVID Levy).
     *
     * @param float $basePrice
     * @return array
     */
    public function calculateGhanaTaxes(float $basePrice): array
    {
        $nhil = $basePrice * self::NHIL_RATE;
        $getFund = $basePrice * self::GETFUND_RATE;
        $covidLevy = $basePrice * self::COVID_LEVY_RATE;

        // The base for VAT calculation
        $vatBase = $basePrice + $nhil + $getFund + $covidLevy;
        $vat = $vatBase * self::VAT_RATE;

        $totalTaxes = $nhil + $getFund + $covidLevy + $vat;
        $totalWithTaxes = $basePrice + $totalTaxes;

        return [
            'base_price' => round($basePrice, 2),
            'nhil' => round($nhil, 2),
            'get_fund' => round($getFund, 2),
            'covid_levy' => round($covidLevy, 2),
            'vat' => round($vat, 2),
            'total_taxes' => round($totalTaxes, 2),
            'total_amount' => round($totalWithTaxes, 2),
            'currency' => 'USD', // Based on project requirements
        ];
    }
}