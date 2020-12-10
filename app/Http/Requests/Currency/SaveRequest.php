<?php

namespace App\Http\Requests\Currency;

use App\Http\Requests\BaseSaveRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends BaseSaveRequest
{
    public function rules()
    {
        $rules = [
            'country' => 'required|integer',
            'symbol' => 'required|string|max:3',
            'name' => ['required', 'string', 'max:255']
        ];

        $rule = Rule::unique('currency');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

        return $rules;
    }
}
