<?php

namespace App\Http\Requests\OS;

use App\Http\Requests\BaseListRequest;
use Illuminate\Validation\Rule;

class ListRequest extends BaseListRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'sometimes|max:255',
            'categories' => 'sometimes|array',
            'categories.*' => 'integer',
            'licenses' => 'sometimes|array',
            'licenses.*' => 'integer',
            'brands' => 'sometimes|array',
            'brands.*' => 'integer',
            'page' => 'sometimes|integer',
            'perPage' => 'sometimes|integer',
        ];
        return $rules;
    }

    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id', 'name', 'license', 'brand']);
    }
}
