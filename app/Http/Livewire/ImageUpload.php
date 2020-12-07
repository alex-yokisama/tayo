<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ImageUpload extends Component
{
    use WithFileUploads;

    public $files;
    public $path = '';

    protected $listeners = ['pathUpdated'];

    public function render()
    {
        return view('livewire.image-upload');
    }

    public function updatedFiles() {
        $this->validate([
            'files.*' => 'image|max:1024'
        ]);

        $this->save();
    }

    public function save()
    {
        if ($this->files == null) {
            return;
        }

        foreach ($this->files as $file) {
            $name = $file->getClientOriginalName();
            if (Storage::disk('images')->exists($this->path.$name)) {
                $i = 1;
                while (Storage::disk('images')->exists($this->path.$i.'_'.$name)) {
                    $i++;
                }
                $name = $i.'_'.$name;
            }

            Storage::disk('images')->putFileAs($this->path, $file, $name);
        }

        $this->files = null;

        $this->emit('filesUploaded');
    }

    public function pathUpdated($path)
    {
        $this->path = $path;
    }
}
