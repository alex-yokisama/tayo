<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Agent;

class AgentAutocomplete extends Component
{
    public $item;
    public $suggestions;
    public $search;
    public $name;
    public $type;

    public function mount($name, $item = null, ?int $type = null)
    {
        $this->dismiss();
        if ($item) {
            $this->item = Agent::find($item);
        } else {
            $this->item = null;
        }
        $this->name = $name;
        $this->type = $type;
    }

    public function render()
    {
        return view('livewire.agent-autocomplete');
    }

    public function autocomplete()
    {
        if (strlen($this->search) == 0) {
            $this->suggestions = collect([]);
            return;
        }
        $query = Agent::where(function($query) {
            $query->orWhere('name', 'LIKE', '%'.$this->search.'%')
                  ->orWhere('surname', 'LIKE', '%'.$this->search.'%');
        });
        if ($this->type !== null) {
            $query->where('type_id', $this->type);
        }
        $this->suggestions = $query->limit(10)->get();
    }

    public function hydrate()
    {
        if ($this->item !== null) {
            $this->item = Agent::find($this->item['id']);
        }
    }

    public function add($id)
    {
        $item = Agent::find($id);
        if ($item == null) {
            return;
        }

        $this->dismiss();
        $this->item = $item;
    }

    public function dismiss()
    {
        $this->item = null;
        $this->suggestions = collect([]);
        $this->search = "";
    }
}
