<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $allowedSorts = collect(['id', 'name', 'email']);
        if (!$allowedSorts->contains($this->sort)) {
            $this->merge([
                'sort' => 'id',
            ]);
        }
        if (strtoupper($this->order) != 'DESC') {
            $this->merge([
                'order' => 'ASC',
            ]);
        } else {
            $this->merge([
                'order' => 'DESC',
            ]);
        }
    }
}
