<?php

namespace App\Http\Requests\Role;

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
            'permissions' => 'array|required',
            'permissions.*' => 'integer',
            'name' => ['required', 'max:255']
        ];

        $rule = Rule::unique('roles');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->id && preg_match('/^[0-9]+$/', $this->id)) {
            $this->id = (int)$this->id;
        } else {
            $this->id = null;
        }

        if (!$this->permissions) {
            $this->merge([
                'permissions' => [],
            ]);
        }
    }
}
