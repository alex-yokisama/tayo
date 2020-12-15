<?php

namespace App\Http\Requests\Brand;

use App\Http\Requests\BaseSaveRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends BaseSaveRequest
{
    public function rules()
    {
        $rules = [
            'country' => 'required|integer',
            'bio' => 'sometimes|nullable|string|max:1000',
            'website' => 'sometimes|nullable|string|url|max:255',
            'contacts' => 'sometimes|array',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.role' => 'sometimes|nullable|string|max:255',
            'contacts.*.email' => 'sometimes|nullable|string|email|max:255',
            'contacts.*.phone' => 'sometimes|nullable|string|max:255',
            'name' => ['required', 'string', 'max:255']
        ];

        $rule = Rule::unique('brand');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

        return $rules;
    }
}
