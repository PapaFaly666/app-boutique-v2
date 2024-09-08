<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
         // Récupère l'ID du client à partir de la route
        $clientId = $this->route('client');

        return [
            'surnom' => 'sometimes|required|string|max:255',
            'adresse' => 'sometimes|nullable|string|max:255',
            'telephone' => "sometimes|required|string|max:15|unique:clients,telephone,$clientId",
        ];
    }
}
