<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'max:255'],
            'sku' => ['required', 'max:255'],
            'model' => 'sometimes|nullable|max:255',
            'model_family' => 'sometimes|nullable|max:255',
            'price_msrp' => 'required|numeric|min:0',
            'price_current' => 'required|numeric|min:0',
            'size_length' => 'sometimes|nullable|integer|min:0',
            'size_width' => 'sometimes|nullable|integer|min:0',
            'size_height' => 'sometimes|nullable|integer|min:0',
            'color' => 'sometimes|nullable|max:255',
            'weight' => 'sometimes|nullable|integer|min:0',
            'battery_size' => 'sometimes|nullable|integer|min:0',
            'battery_life' => 'sometimes|nullable|integer|min:0',
            'date_publish' => 'required|date',
            'is_promote' => 'sometimes|nullable|boolean',
            'excerpt' => 'sometimes|nullable|max:100',
            'summary_main' => 'sometimes|nullable|max:500',
            'summary_value' => 'sometimes|nullable|max:500',
            'full_overview' => 'sometimes|nullable',
            'seo_keywords' => 'sometimes|nullable|max:255',
            'tags' => 'sometimes|nullable|array|distinct',
            'tags.*' => 'max:255',
            'category' => 'required|integer|min:1',
            'brand' => 'required|integer|min:1',
            'country' => 'required|integer|min:1',
            'countries' => 'sometimes|nullable|array|distinct',
            'countries.*' => 'integer|min:1',
            'product_attributes' => 'sometimes|nullable|array',
            'product_attributes.*' => 'required',
            'links' => 'sometimes|array',
            'links.*.agent' => 'required|integer|min:1',
            'links.*.price_old' => 'integer|min:0',
            'links.*.price_new' => 'integer|min:0',
            'links.*.currency' => 'required|integer|min:1',
            'links.*.link' => 'required|max:255'
        ];

        if ($this->links) {
            $rules['primary_link'] = 'required|integer';
        }

        $rule = Rule::unique('product');
        if ($this->id) {
            $rule->ignore($this->id);
        }
        $rules['name'][] = $rule;
        $rules['sku'][] = $rule;

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->id && preg_match('/^[0-9]+$/', $this->id)) {
            $this->merge([
                'id' => (int)$this->id,
            ]);
        } else {
            $this->merge([
                'id' => null,
            ]);
        }
    }
}
