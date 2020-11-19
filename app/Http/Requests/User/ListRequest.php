<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseListRequest;
use Illuminate\Validation\Rule;

class ListRequest extends BaseListRequest
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
            'name' => 'sometimes|max:255',
            'email' => 'sometimes|max:255',
            'roles' => 'sometimes|array',
            'roles.*' => 'integer'
        ];
    }

    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id', 'name', 'email']);
    }
}
