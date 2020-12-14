<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CopyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer'
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->backUrl) {
            $this->backUrl = '/admin/products';
        }
    }
}
