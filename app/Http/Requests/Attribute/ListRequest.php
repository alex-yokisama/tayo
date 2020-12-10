<?php

namespace App\Http\Requests\Attribute;

use App\Http\Requests\BaseListRequest;
use Illuminate\Validation\Rule;

class ListRequest extends BaseListRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'sometimes|max:255',
            'option' => 'sometimes|max:255',
            'page' => 'sometimes|integer',
            'perPage' => 'sometimes|integer',
            'measures' => 'sometimes|array',
            'measures.*' => 'integer'
        ];
        if ($this->type !== null) {
            $rules['type'] = 'sometimes|integer|min:0|max:5';
        }
        return $rules;
    }

    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id', 'name', 'type', 'measure']);
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        if ($this->type == "any") {
            $this->type = null;
        }
    }
}
