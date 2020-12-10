<?php

namespace App\Http\Requests\Measure;

use App\Http\Requests\BaseSaveRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends BaseSaveRequest
{
    public function rules()
    {
        $rules = [
            'short_name' => 'required|string|max:5',
            'name' => ['required', 'string', 'max:255']
        ];

        $rule = Rule::unique('measure');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

        return $rules;
    }
}
