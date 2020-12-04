<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategorySelect extends Component
{
    public $item;
    public $name;
    public $options;
    public $itemId;

    public function render()
    {
        return view('livewire.category-select');
    }

    public function mount($name, $itemId = null)
    {
        $this->name = $name;
        $this->itemId = $itemId;
        $this->item = Category::find($itemId);

        $this->options = Category::all();
        $this->categoryChanged();
    }

    public function hydrate()
    {
        $this->item = Category::find($this->itemId);
        $this->options = Category::all();
    }

    public function categoryChanged()
    {
        $this->emit('categoryChanged', $this->itemId);
    }
}
