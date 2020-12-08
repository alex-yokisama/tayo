<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class ProductAttributes extends Component
{
    protected $listeners = ['categoryChanged' => 'updateAttributeList'];
    public $items;
    public $productId;
    public $old;

    public function mount($productId, $old = null)
    {
        $this->items = collect([]);
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
        if ($category === null) {
            $this->items = collect([]);
            return;
        }
        $this->items = $category->attributes()->orderBy('name', 'ASC')->get();
    }
}
