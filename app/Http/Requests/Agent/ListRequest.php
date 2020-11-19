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
        return [
            'name' => 'sometimes|max:255',
            'is_retailer' => 'sometimes|boolean',
            'page' => 'sometimes|integer',
            'perPage' => 'required|integer'
        ];
    }

    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id', 'name']);
    }
}
