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
            'bio' => 'sometimes|string|max:1000',
            'website' => 'sometimes|string|url|max:255',
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
