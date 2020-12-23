<x-custom-layout>

    <x-slot name="title">
        OS List
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>OS List</x-common.h.h1>
            <x-common.button.group>
                <x-common.a.a x-data="deleteItemsButton()" @click.prevent="$dispatch('show-delete-items-modal');" class="text-red-500" href="#">Delete</x-common.a.a>
                <script>
                    function deleteItemsButton() {
                        return {
                            show: false
                        }
                    }
                </script>
                <x-common.button.a href="/admin/os?backUrl={{ urlencode($backUrl) }}">New</x-common.button.a>
            </x-common.button.group>
        </div>

        @if (session('status') == 'success')
            <x-common.alert.success>
                {{ session('message') }}
            </x-common.alert.success>
        @endif

        @if ($errors->any())
            <div class="space-y-1">
                @foreach ($errors->all() as $error)
                    <x-common.alert.error>
                        {{ $error }}
                    </x-common.alert.error>
                @endforeach
            </div>
        @endif
    </x-slot>

    <x-list>
        <x-slot name="search">
            <div class="space-x-2">
                <label>Name</label>
                <input type="text" name="name" value="{{ Request()->name }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Categories</label>
                <div class="">
                    @foreach ($categories as $category)
                        <label>
                            <input type="checkbox" {{ collect(Request()->categories)->contains($category->id) ? 'checked' : '' }} name="categories[]" value="{{ $category->id }}">
                            {{ $category->name }}
                        </label><br>
                    @endforeach
                </div>
            </div>
            <div class="space-x-2">
                <label>Brands</label>
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($brands as $brand)
                        <label>
                            <input type="checkbox" {{ collect(Request()->brands)->contains($brand->id) ? 'checked' : '' }} name="brands[]" value="{{ $brand->id }}">
                            {{ $brand->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="space-x-2">
                <label>Licenses</label>
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($licenses as $license)
                        <label>
                            <input type="checkbox" {{ collect(Request()->licenses)->contains($license->id) ? 'checked' : '' }} name="licenses[]" value="{{ $license->id }}">
                            {{ $license->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="space-x-2">
                <label>Is kernel</label>
                <select class="border px-2 py-0.5" name="is_kernel">
                    <option value="any">any</option>
                    <option value="1" {{ Request()->is_open_source == '1' ? 'selected' : '' }}>yes</option>
                    <option value="0" {{ Request()->is_open_source == '0' ? 'selected' : '' }}>no</option>
                </select>
            </div>
        </x-slot>

        {{ $items->withQueryString()->links('vendor.pagination.custom-tailwind', ['allowedPerPages' => $allowedPerPages]) }}
        <div class="overflow-auto">
            <form class="deleteItemsForm" action="delete_oss" method="post">
                @csrf
                <input type="hidden" name="backUrl" value="{{ $backUrl }}">
                <x-common.table.table x-data="tableComponent()">
                    <x-slot name="thead">
                        <x-common.table.th><input type="checkbox" @change="check" x-bind:checked="checked"></x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="name" />
                        </x-common.table.th>
                        <x-common.table.th>Logo</x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="brand" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="license" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="version" />
                        </x-common.table.th>
                        <x-common.table.th>Is&nbsp;kernel</x-common.table.th>
                        <x-common.table.th></x-common.table.th>
                    </x-slot>
                    @foreach ($items as $item)
                        <x-common.table.tr x-bind:class="{ 'border-b-2': !showSpoilers[{{ $item->id }}] }">
                            <x-common.table.td><input class="selectAllCheckable" type="checkbox" name="items[]" value="{{ $item->id }}"></x-common.table.td>
                            <x-common.table.td>
                                <a href="#" class="text-blue-900 flex items-center space-x-2" @click.prevent="showSpoiler({{ $item->id }})">
                                    <span>{{ $item->name }}</span>
                                    <span x-show="!showSpoilers[{{ $item->id }}]">
                                        <x-common.arrow.sort-down class="border-blue-900" />
                                    </span>
                                    <span x-show="showSpoilers[{{ $item->id }}]" style="display: none;">
                                        <x-common.arrow.sort-up class="border-blue-900" />
                                    </span>
                                </a>
                            </x-common.table.td>
                            <x-common.table.td>
                                @if ($item->image != null)
                                    <div class="w-16 h-16 bg-contain bg-no-repeat bg-center" style="background-image: url('{{ $item->imageUrl }}');"></div>
                                @endif
                            </x-common.table.td>
                            <x-common.table.td>{{ $item->brand !== null ? $item->brand->name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->licenseType !== null ? $item->licenseType->name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->version }}</x-common.table.td>
                            <x-common.table.td>{{ $item->is_kernel ? '+' : '-' }}</x-common.table.td>
                            <x-common.table.td>
                                <x-common.button.group  class="justify-end">
                                    <x-common.button.a href="/admin/os?id={{ $item->id }}&backUrl={{ urlencode($backUrl) }}">
                                        Edit
                                    </x-common.button.a>
                                </x-common.button.group>
                            </x-common.table.td>
                        </x-common.table.tr>
                        <x-common.table.tr x-show="!!showSpoilers[{{ $item->id }}]">
                            <x-common.table.td colspan="14">
                                <x-common.h.h4>
                                    Categories
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    {{ $item->Categories->map(function($item) {
                                        return $item->name;
                                    })->join(', ') }}
                                </div>
                                <x-common.h.h4>
                                    Description
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    {{ $item->description }}
                                </div>
                                <x-common.h.h4>
                                    Change log
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    <a href="{{ $itetm->change_log_url }}" target="_blank">
                                        {{ $itetm->change_log_url }}
                                    </a>
                                </div>
                            </x-common.table.td>
                        </x-common.table.tr>
                    @endforeach
                </x-common.table.table>
                <script>
                    function tableComponent() {
                        return {
                            showSpoilers: [],
                            checked: false,
                            sort: '{{ $sort }}',
                            order: '{{ $order }}',
                            check($event) {
                                [...document.querySelectorAll("input.selectAllCheckable")].map((el) => {
                                    el.checked = $event.target.checked;
                                });
                            },
                            applySort(targetSort) {
                                let url = new URL(window.location);
                                url.searchParams.delete('order');

                                if (targetSort != this.sort) {
                                    url.searchParams.delete('sort');
                                    url.searchParams.append('sort', targetSort);
                                    url.searchParams.append('order', 'ASC');
                                } else {
                                    if (this.order == 'ASC') {
                                        url.searchParams.append('order', 'DESC');
                                    } else {
                                        url.searchParams.append('order', 'ASC');
                                    }
                                }
                                window.location = url.href;
                            },
                            defaultSort() {
                                let url = new URL(window.location);
                                url.searchParams.delete('sort');
                                url.searchParams.delete('order');
                                window.location = url.href;
                            },
                            showSpoiler(i) {
                                this.showSpoilers[i] = !this.showSpoilers[i];
                            }
                        }
                    }
                </script>
            </form>
        </div>
        {{ $items->withQueryString()->links('vendor.pagination.custom-tailwind', ['allowedPerPages' => $allowedPerPages]) }}

    </x-list>

    @push('modals')
        <div style="display: none;" x-data="deleteItemsModal()" x-show="show" x-on:show-delete-items-modal.window="showModal" class="deleteItemsModal fixed w-full h-full z-20 bg-black bg-opacity-50 top-0 left-0 p-4 sm:py-28">
            <div @click.away="show = false" class="relative bg-white w-full sm:w-3/4 md:w-1/2 mx-auto">
                <span @click="show = false" class="absolute top-0 right-0 text-2xl mx-3 font-bold cursor-pointer">&times;</span>
                <div class="p-6 pb-4">
                    <p>Delete selected items? </p>
                    <x-common.button.group class="justify-end">
                        <x-common.a.a href="#" class="text-red-500" @click.prevent="submitDelete">
                            Delete
                        </x-common.a.a>
                        <x-common.a.a href="#" @click.prevent="show = false">
                            Cancel
                        </x-common.a.a>
                    </x-common.button.group>
                </div>
            </div>
        </div>
        <script>
            function deleteItemsModal() {
                return {
                    show: false,
                    submitDelete() {
                        document.querySelector('form.deleteItemsForm').submit()
                    },
                    showModal() {
                        if ([...document.querySelectorAll('form.deleteItemsForm input[type=checkbox][name]:checked')].length > 0) {
                            this.show = true;
                        }
                    }
                }
            }
        </script>
    @endpush

</x-custom-layout>
