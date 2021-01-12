<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeGroup;

class ProductAttributes extends Component
{
    protected $listeners = ['categoryChanged' => 'updateAttributeList'];
    public $groups;
    public $kind_id;
    public $productId;
    public $old;

    public function mount($productId, $old = null, $kind_id)
    {
        $this->groups = collect([]);
        $this->kind_id = $kind_id;
        $this->productId = $productId;
        $this->old = $old;
    }

    public function render()
    {
        return view('livewire.product-attributes');
    }

    public function updateAttributeList($categoryId)
    {
        $this->errors = false;
        $category = Category::find($categoryId);
        $this->groups = collect([]);
        if ($category === null) {
            return;
        }

        $groups = AttributeGroup::orderBy('sort_order', 'ASC')->get();

        foreach ($groups as $group) {
            $attributes = $category->attributes()->whereHas('group', function($query) use ($group) {
                $query->where('attribute_group_id', $group->id);
            })->where('kind', $this->kind_id)->orderBy('sort_order', 'ASC')->get();
            if ($attributes->count() > 0) {
                $this->groups->push((object)[
                    'name' => $group->name,
                    'attributes' => $attributes
                ]);
            }
        }
    }
}
