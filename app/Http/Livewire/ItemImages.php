<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ItemImages extends Component
{
    public $images;
    public $path = '';
    public $files;
    public $folders;
    public $selected_items = [];
    public $name;
    public $multiple;

    protected $listeners = ['filesUploaded'];

    public function filesUploaded()
    {
        $this->updateFileSystem();
    }

    public function mount($name, $images = [], $multiple = true) {
        $this->name = $name;
        $this->multiple = $multiple;

        $this->images = collect($images)->filter(function($item) {
            return Storage::disk('images')->exists($item);
        })->map(function($item) {
            return (object)[
                'path' => $item,
                'url' => Storage::disk('images')->url($item)
            ];
        });
        $this->images = $this->images->values();
        $this->resetFileSystem();
    }

    public function hydrate()
    {
        $this->images = $this->images->filter(function($item) {
            return Storage::disk('images')->exists($item['path']);
        })->map(function($item) {
            return (object)[
                'path' => $item['path'],
                'url' => Storage::disk('images')->url($item['path'])
            ];
        });
        $this->updateFileSystem();
    }

    public function moveUp($index)
    {
        if ($index >= $this->images->count() || $index <= 0) {
            return;
        }
        $tmp = $this->images[$index];
        $this->images[$index] = $this->images[$index - 1];
        $this->images[$index - 1] = $tmp;
    }

    public function moveDown($index)
    {
        if ($index >= $this->images->count() - 1 || $index < 0) {
            return;
        }
        $tmp = $this->images[$index];
        $this->images[$index] = $this->images[$index + 1];
        $this->images[$index + 1] = $tmp;
    }

    private function updateFileSystem()
    {
        if (!Storage::disk('images')->exists($this->path)) {
            $this->path = '';
        }
        $this->selected_items = [];
        $this->folders = collect(Storage::disk('images')->directories($this->path))->map(function($item) {
            return (object)[
                'path' => $item,
                'name' => basename(Storage::disk('images')->path($item))
            ];
        });
        $this->files = collect(Storage::disk('images')->files($this->path))->map(function($item) {
            return (object)[
                'path' => $item,
                'name' => basename(Storage::disk('images')->path($item)),
                'url' => Storage::disk('images')->url($item)
            ];
        });
        $this->emit('pathUpdated', $this->path);
    }

    public function folder($folder)
    {
        if (!collect(Storage::disk('images')->directories($this->path))->contains($folder)) {
            return;
        }
        $this->path = $folder;
        $this->updateFileSystem();
    }

    public function back()
    {
        if ($this->path == '') {
            return;
        }
        $this->path = collect(explode('/', $this->path))->reverse()->slice(1)->reverse()->join('/');

        $this->updateFileSystem();
    }

    public function remove($index)
    {
        if ($index >= $this->images->count() || $index < 0) {
            return;
        }
        $this->images->splice($index, 1);
        $this->images = $this->images->values();
    }

    public function select()
    {
        if (!$this->multiple) {
            foreach ($this->selected_items as $selected) {
                if (is_file(Storage::disk('images')->path($selected))) {
                    $this->images = collect([((object)[
                        'path' => $selected,
                        'url' => Storage::disk('images')->url($selected)
                    ])]);
                    break;
                }
            }
        } else {
            foreach ($this->selected_items as $selected) {
                if (is_file(Storage::disk('images')->path($selected)) && !$this->images->contains('path', $selected)) {
                    $this->images->push((object)[
                        'path' => $selected,
                        'url' => Storage::disk('images')->url($selected)
                    ]);
                }
            }
        }
        $this->resetFileSystem();
    }

    public function resetFileSystem()
    {
        $this->path = '';
        $this->selected = [];
        $this->updateFileSystem();
    }

    public function delete()
    {
        foreach ($this->selected_items as $selected) {
            if (is_dir(Storage::disk('images')->path($selected))) {
                Storage::disk('images')->deleteDirectory($selected);
            } else {
                Storage::disk('images')->delete($selected);
            }
        }
        $this->images = $this->images->map(function($item) {
            return json_decode(json_encode($item), true);
        });
        $this->hydrate();
    }

    public function createFolder($folderName)
    {
        if ($folderName == '' || Storage::disk('images')->exists($this->path.'/'.$folderName)) {
            return;
        }
        Storage::disk('images')->makeDirectory($this->path.'/'.$folderName);
        $this->updateFileSystem();
    }

    public function render()
    {
        if (!$this->multiple) {
            $this->images = $this->images->splice(0, 1);
        }
        return view('livewire.item-images');
    }
}
