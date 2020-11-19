<?php

namespace App\Http\Requests\Brand;

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
        return [
            'brands' => 'required|array',
            'brands.*' => 'integer'
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->brands) {
            $this->merge([
                'brands' => [],
            ]);
        }
    }
}
