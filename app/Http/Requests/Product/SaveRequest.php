<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseSaveRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends BaseSaveRequest
{
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255'],
            'model' => 'required|string|max:255',
            'model_family' => 'sometimes|string|nullable|max:255',
            'price_msrp' => 'required|numeric|min:0',
            'price_current' => 'required|numeric|min:0',
            'currency_msrp' => 'required|integer|min:1',
            'currency_current' => 'required|integer|min:1',
            'size_length' => 'sometimes|nullable|integer|min:0',
            'size_width' => 'sometimes|nullable|integer|min:0',
            'size_height' => 'sometimes|nullable|integer|min:0',
            'weight' => 'sometimes|nullable|integer|min:0',
            'date_publish' => 'required|date',
            'is_promote' => 'sometimes|nullable|boolean',
            'excerpt' => 'sometimes|string|nullable|max:100',
            'summary_main' => 'sometimes|string|nullable|max:500',
            'summary_value' => 'sometimes|string|nullable|max:500',
            'full_overview' => 'sometimes|string|nullable',
            'seo_keywords' => 'sometimes|string|nullable|max:255',
            'tags' => 'sometimes|nullable|array|distinct',
            'tags.*' => 'string|max:255',
            'category' => 'required|integer|min:1',
            'brand' => 'required|integer|min:1',
            'country' => 'required|integer|min:1',
            'countries' => 'sometimes|nullable|array|distinct',
            'countries.*' => 'integer|min:1',
            'websites' => 'sometimes|nullable|array|distinct',
            'websites.*' => 'integer|min:1',
            'product_attributes' => 'sometimes|nullable|array',
            'product_attributes.*' => 'required',
            'links' => 'sometimes|array',
            'links.*.agent' => 'required|integer|min:1',
            'links.*.price_old' => 'integer|min:0',
            'links.*.price_new' => 'integer|min:0',
            'links.*.currency' => 'required|integer|min:1',
            'links.*.link' => 'required|string|url|max:255',
            'links.*.link' => 'sometimes|nullable|string|max:500',
            'images' => 'sometimes|array|distinct'
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
}
