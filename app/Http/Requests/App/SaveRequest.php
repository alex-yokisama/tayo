<?php

namespace App\Http\Requests\App;

use App\Http\Requests\BaseSaveRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends BaseSaveRequest
{
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'type' => 'required|integer|min:0|max:1',
            'brand' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'os' => 'sometimes|nullable|array|distinct',
            'os.*' => 'integer|min:1',
            'countries' => 'sometimes|nullable|array|distinct',
            'countries.*' => 'integer|min:1',
            'change_log_url' => 'required|string|url|max:255',
            'links' => 'sometimes|array',
            'links.*.os' => 'required|integer|min:1',
            'links.*.price' => 'integer|min:0',
            'links.*.app_store_name' => 'required|string|max:255',
            'links.*.url' => 'required|string|url|max:255',
            'images' => 'sometimes|array|distinct'
        ];

        $rule = Rule::unique('app');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

        return $rules;
    }
}
