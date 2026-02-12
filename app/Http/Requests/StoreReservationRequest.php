<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'departure_location' => 'required|string|max:255',
            'arrival_location' => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'arrival_date' => 'required|date|after_or_equal:departure_date',
            'passengers' => 'required|integer|min:1|max:50',
            'jet_id' => 'required|exists:jets,id',
            'message' => 'nullable|string|max:1000',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'luggage_count' => 'nullable|integer|min:0',
            'luggage_weight_kg' => 'nullable|numeric|min:0',
            // Consentement requis
            'data_protection_consent' => 'required|accepted',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Log::error('Validation failed for Reservation:', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->all(),
        ]);

        parent::failedValidation($validator);
    }
}