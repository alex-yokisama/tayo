<?php

namespace App\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteRequest extends FormRequest
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
        var_dump($this->attributes);
        return [
            'items' => 'required|array',
            'items.*' => 'integer'
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->attributes) {
            $this->merge([
                'items' => [],
            ]);
        }
    }
}
