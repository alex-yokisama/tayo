<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'country' => 'required|integer',
            'symbol' => 'sometimes|max:3',
            'name' => ['required', 'max:255']
        ];

        $rule = Rule::unique('currency');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->id && preg_match('/^[0-9]+$/', $this->id)) {
            $this->merge([
                'id' => (int)$this->id,
            ]);
        } else {
            $this->merge([
                'id' => null,
            ]);
        }
    }
}
