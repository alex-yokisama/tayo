<div class="space-y-2">
    <div class="" x-data="fileSystem()">
        <x-common.button.a x-on:click.prevent="show = true" href="#">&plus;</x-common.button.a>
        <div class="fixed z-30 inset-0 bg-black bg-opacity-50 flex items-center justify-center" x-show="show" style="display: none;">
            <div class="bg-white w-3/4 h-3/4 flex flex-col relative" x-on:click.away="show = false">
                <span x-on:click="show = false" class="absolute top-0 right-0 text-2xl mx-3 font-bold cursor-pointer">&times;</span>
                <div class="px-4 my-4 space-y-2">
                    <div class="">
                        Current location: <span class="font-bold">/{{ $path }}</span>
                    </div>
                    <x-common.button.group class="justify-end">
                        <x-common.a.a href="#" wire:click.prevent="back" title="back" style="{{ $path == '' ? 'display: none;' : '' }}">
                            ↰ back
                        </x-common.a.a>
                        <x-common.a.a href="#" x-on:click.prevent="showConfirmDelete($wire, $refs)" class="text-red-500">
                            delete
                        </x-common.a.a>
                    </x-common.button.group>
                </div>
                <div class="overflow-auto flex-grow">
                    <div x-ref="fileSystem" class="p-4 gap-4 grid grid-cols-2 md:grid-cols-4 sm:grid-cols-3" id="path-{{ $path }}">
                        @foreach ($folders as $folder)
                            <div class="flex flex-col space-y-1 items-center justify-center">
                                <div class="w-24 h-24 flex flex-col items-end" wire:click="folder('{{ $folder->path }}')">
                                    <div class="w-2/3 h-1/4 bg-blue-200 rounded-t-xl"></div>
                                    <div class="w-full h-3/4 bg-blue-200 rounded-b-xl rounded-tl-xl"></div>
                                </div>
                                <label class="block">
                                    <input type="checkbox" id="{{ rand() }}" wire:model.defer="selected_items" value="{{ $folder->path }}">
                                    <span>{{ $folder->name }}</span>
                                </label>
                            </div>
                        @endforeach
                        @foreach ($files as $file)
                            <label class="flex flex-col space-y-1 items-center justify-center">
                                <div class="w-24 h-24 bg-contain bg-no-repeat bg-center" style="background-image: url('{{ $file->url }}"></div>
                                <div class="">
                                    <input type="checkbox" id="{{ rand() }}" wire:model.defer="selected_items" value="{{ $file->path }}">
                                    <span>{{ $file->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="px-4 my-4 space-y-2">
                    <x-common.button.group class="justify-end">
                        <x-common.a.a href="#" x-on:click.prevent="showCreateFolder($refs)">
                            New folder
                        </x-common.a.a>
                        <x-common.a.a href="#" x-on:click.prevent="showUploadFilesModal = true">
                            Upload files
                        </x-common.a.a>
                        <x-common.button.a href="#" x-on:click.prevent="select($wire, $refs)">
                            Select
                        </x-common.button.a>
                    </x-common.button.group>
                </div>
                <div x-show="showDeleteModal" class="fixed z-40 inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="p-6 pb-4 bg-white" x-on:click.away="showDeleteModal = false">
                        <p>Delete selected items? </p>
                        <x-common.button.group class="justify-end">
                            <x-common.a.a href="#" class="text-red-500" x-on:click.prevent="confirmDelete($wire)">
                                Delete
                            </x-common.a.a>
                            <x-common.a.a href="#" x-on:click.prevent="showDeleteModal = false">
                                Cancel
                            </x-common.a.a>
                        </x-common.button.group>
                    </div>
                </div>
                <div x-show="showCreateFolderModal" class="fixed z-40 inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="p-6 pb-4 bg-white" x-on:click.away="showCreateFolderModal = false">
                        <p>Enter folder name: </p>
                        <x-common.input.input x-ref="folderName" name="" value="" class="mb-1" x-on:keydown.enter.prevent="createFolder($wire, $refs)" placeholder="Folder name" />
                        <x-common.button.group class="justify-end">
                            <x-common.button.a href="#" x-on:click.prevent="createFolder($wire, $refs)">
                                Create
                            </x-common.button.a>
                            <x-common.a.a href="#" x-on:click.prevent="showCreateFolderModal = false">
                                Cancel
                            </x-common.a.a>
                        </x-common.button.group>
                    </div>
                </div>
                <div x-show="showUploadFilesModal" class="fixed z-40 inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="p-6 pb-4 bg-white" x-on:click.away="showUploadFilesModal = false">
                        <div class="my-2">
                            @livewire('image-upload', key('fileUpload'))
                        </div>
                        <x-common.button.group class="justify-end">
                            <x-common.a.a href="#" x-on:click.prevent="showUploadFilesModal = false">
                                Cancel
                            </x-common.a.a>
                        </x-common.button.group>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($images->count() > 0)
        <table>
            @foreach ($images as $index => $image)
                <tr>
                    <x-common.table.td>
                        <div class="w-16 h-16 bg-contain bg-no-repeat bg-center" style="background-image: url('{{ $image->url }}')">
                            <input type="hidden" name="{{ $name }}" value="{{ $image->path}}">
                        </div>
                    </x-common.table.td>
                    <x-common.table.td>
                        <span>
                            {{ $image->path}}
                        </span>
                    </x-common.table.td>

                    @if ($images->count() > 1)
                        <x-common.table.td>
                            <a class="block px-2 py-0.5" href="#" wire:click.prevent="moveUp({{ $index }})" title="move up">
                                <x-common.arrow.sort-up class="border-black"/>
                            </a>
                        </x-common.table.td>
                        <x-common.table.td>
                            <a class="block px-2 py-0.5" href="#" wire:click.prevent="moveDown({{ $index }})" title="move down">
                                <x-common.arrow.sort-down class="border-black"/>
                            </a>
                        </x-common.table.td>
                    @endif

                    <x-common.table.td>
                        <a class="block px-2 py-0.5" href="#" wire:click.prevent="remove({{ $index }})" title="remove">
                            <span class="text-lg font-bold">&times;</span>
                        </a>
                    </x-common.table.td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
<script>
    function fileSystem() {
        let obj = {
            show: false,
            showDeleteModal: false,
            showCreateFolderModal: false,
            showUploadFilesModal: false,
            select($wire, $refs) {
                $wire.select().then(() => {
                    this.show = false;
                    [...$refs.fileSystem.querySelectorAll("input[type='checkbox']")].forEach((item) => {
                        item.checked = false;
                    });
                });
            },
            showConfirmDelete($wire, $refs) {
                if ([...$refs.fileSystem.querySelectorAll("input[type='checkbox']:checked")].length > 0) {
                    this.showDeleteModal = true;
                }
            },
            confirmDelete($wire) {
                $wire.delete().then(() => {
                    this.showDeleteModal = false;
                });
            },
            showCreateFolder($refs) {
                this.showCreateFolderModal = true;
                setTimeout(() => {
                    $refs.folderName.focus();
                }, 100);
            },
            createFolder($wire, $refs) {
                if ($refs.folderName.value.length > 0) {
                    $wire.createFolder($refs.folderName.value).then(() => {
                        $refs.folderName.value = "";
                        this.showCreateFolderModal = false;
                    });
                }
            },
            filesUploaded() {
                this.showUploadFilesModal = false;
            }
        }
        Livewire.on("filesUploaded", () => {
            obj.filesUploaded();
        });
        return obj;
    }
</script>
