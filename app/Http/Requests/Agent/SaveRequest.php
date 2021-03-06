<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\BaseSaveRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends BaseSaveRequest
{
    public function rules()
    {
        $rules = [
            'is_retailer' => 'required|boolean',
            'website' => 'sometimes|string|nullable|url|max:255',
            'type' => 'required|integer|min:0|max:1',
            'surname' => 'sometimes|nullable|string|max:255',
            'name' => 'required|string|max:255',
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        $this->merge([
            'is_retailer' => $this->is_retailer ? 1: 0
        ]);
    }
}
