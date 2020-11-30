<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\BaseListRequest;
use Illuminate\Validation\Rule;

class ListRequest extends BaseListRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'sometimes|max:255',
            'website' => 'sometimes|max:255',
            'page' => 'sometimes|integer',
            'perPage' => 'required|integer'
        ];

        if ($this->is_retailer !== null) {
            $rules['is_retailer'] = 'sometimes|boolean';
        }
        return $rules;
    }

    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id', 'name']);
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        if ($this->is_retailer == "any") {
            $this->is_retailer = null;
        }
    }
}
