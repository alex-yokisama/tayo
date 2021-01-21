<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AppLinks extends Component
{
    public $links;

    public function mount($links = [])
    {
        $this->links = $links;
        if ($this->links === null) {
            $this->links = [];
        }
        if (is_array($this->links)) {
            $this->links = collect($this->links);
        }
        $this->links = $this->links->map(function($item) {
            return (object)$item;
        });
    }

    public function hydrate()
    {
        $this->links = $this->links->map(function($item) {
            if ($item !== null) {
                return (object)$item;
            } else {
                return null;
            }
        });
    }

    public function render()
    {
        return view('livewire.app-links');
    }

    public function add()
    {
        $this->links->push((object)[
            'os' => '',
            'price' => '',
            'app_store_name' => '',
            'url' => ''
        ]);
    }

    public function remove($index)
    {
        $this->links[$index] = null;
    }

    public function setPrimary($index)
    {
        $this->primaryLink = $index;
    }
}
