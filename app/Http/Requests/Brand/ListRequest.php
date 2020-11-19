<?php

namespace App\Http\Requests\Brand;

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
            'page' => 'sometimes|integer',
            'perPage' => 'required|integer',
            'countries' => 'sometimes|array',
            'countries.*' => 'integer'
        ];
    }

    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id', 'name', 'country']);
    }
}
