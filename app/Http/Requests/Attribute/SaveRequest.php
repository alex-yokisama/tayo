<?php

namespace App\Http\Requests\Attribute;

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
            'type' => 'required|integer|min:0|max:5',
            'name' => ['required', 'max:255'],
            'measure' => 'sometimes|nullable|integer',
        ];

        $rule = Rule::unique('attribute');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

        if ($this->type == 4 || $this->type == 5) {
            $rules['options'] = 'required|array|distinct';
            $rules['options.*'] = 'max:255';
        }

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

        $this->merge([
            'options' => collect($this->options)->unique()->filter(function ($item) {
                return strlen($item) > 0;
            })->toArray(),
        ]);
    }
}
