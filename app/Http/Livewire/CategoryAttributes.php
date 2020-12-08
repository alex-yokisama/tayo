<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;

class CategoryAttributes extends Component
{
    public $inheritedAttributes;
    public $availableAttributes;
    public $ownAttributes;
    public $name;

    protected $listeners = ['categoryChanged'];

    public function mount($name, $categoryId = null, array $ownAttributes = [])
    {
        $this->categoryChanged($categoryId);
        $this->ownAttributes = collect($ownAttributes);
    }

    public function render()
    {
        return view('livewire.category-attributes');
    }

    public function categoryChanged($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            $this->inheritedAttributes = collect([]);
            $this->availableAttributes = Attribute::orderBy('name', 'ASC')->get();
        } else {
            $this->inheritedAttributes = $category->attributes()->orderBy('name', 'ASC')->get();
            $this->availableAttributes = Attribute::whereNotIn('id', $this->inheritedAttributes->map(function($item) {
                return $item->id;
            })->toArray())->get();
        }
    }
}
