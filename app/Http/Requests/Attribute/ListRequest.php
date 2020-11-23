<?php

namespace App\Http\Requests\Attribute;

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
        return [
            'name' => 'sometimes|max:255',
            'option' => 'sometimes|max:255',
            'type' => 'sometimes|integer|min:0|max:5',
            'page' => 'sometimes|integer',
            'perPage' => 'required|integer',
            'measures' => 'sometimes|array',
            'measures.*' => 'integer'
        ];
    }

    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id', 'name', 'type', 'measure']);
    }
}
