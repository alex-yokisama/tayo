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
            'name' => ['required', 'string', 'max:255']
        ];

        $rule = Rule::unique('agent');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

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
