<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseDeleteRequest extends FormRequest
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
        return [
            'items' => 'required|array',
            'items.*' => 'integer'
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->items) {
            $this->merge([
                'items' => [],
            ]);
        }
    }
}
