<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

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
            'roles' => 'sometimes|array',
            'roles.*' => 'integer',
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255']
        ];

        $rule = Rule::unique('users');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['email'][] = $rule;

        if ($this->id === null || $this->password !== null) {
            $rules['password'] = ['required', 'string', new Password, 'confirmed'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->id && preg_match('/^[0-9]+$/', $this->id)) {
            $this->id = (int)$this->id;
        } else {
            $this->id = null;
        }
    }
}
