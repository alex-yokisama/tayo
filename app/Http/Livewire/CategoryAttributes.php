<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeGroup;

class CategoryAttributes extends Component
{
    public $inheritedAttributes;
    public $attributeGroups;
    public $ownAttributes;
    public $featuredAttributes;
    public $name;

    protected $listeners = ['categoryChanged'];

    public function mount($name, $categoryId = null, array $ownAttributes = [], array $featuredAttributes = [])
    {
        $this->categoryChanged($categoryId);
        $this->ownAttributes = collect($ownAttributes);
        $this->featuredAttributes = collect($featuredAttributes);
    }

    public function render()
    {
        return view('livewire.category-attributes');
    }

    public function categoryChanged($categoryId)
    {
        $category = Category::find($categoryId);
        $this->attributeGroups = AttributeGroup::whereHas('attributes')->orderBy('sort_order', 'ASC')->get();
        if ($category) {
            $this->inheritedAttributes = $category->attributes()->orderBy('name', 'ASC')->get();
        } else {
            $this->inheritedAttributes = collect([]);
        }
    }
}
