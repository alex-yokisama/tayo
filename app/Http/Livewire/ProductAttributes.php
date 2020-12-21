<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeGroup;

class ProductAttributes extends Component
{
    protected $listeners = ['categoryChanged' => 'updateAttributeList'];
    public $items;
    public $kinds;
    public $productId;
    public $old;

    public function mount($productId, $old = null)
    {
        $this->items = collect([]);
        $this->kinds = collect([]);
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
        $this->kinds = collect([]);
        if ($category === null) {
            return;
        }       

        $groups = AttributeGroup::orderBy('sort_order', 'ASC')->get();
        $kinds = Attribute::kindsList();
        foreach ($kinds as $kind_id => $kind_name) {
            $tmpGroups = collect([]);
            foreach ($groups as $group) {
                $attributes = $category->attributes()->whereHas('group', function($query) use ($group) {
                    $query->where('attribute_group_id', $group->id);
                })->where('kind', $kind_id)->orderBy('sort_order', 'ASC')->get();
                if ($attributes->count() > 0) {
                    $tmpGroups->push((object)[
                        'name' => $group->name,
                        'attributes' => $attributes
                    ]);
                }
            }
            if ($tmpGroups->count() > 0) {
                $this->kinds->push((object)[
                    'name' => $kind_name,
                    'groups' => $tmpGroups
                ]);
            }
        }
    }
}
