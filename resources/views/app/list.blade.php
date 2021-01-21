<x-custom-layout>

    <x-slot name="title">
        Apps
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Apps</x-common.h.h1>
            <x-common.button.group>
                <x-common.a.a x-data="deleteItemsButton()" @click.prevent="$dispatch('show-delete-items-modal');" class="text-red-500" href="#">Delete</x-common.a.a>
                <script>
                    function deleteItemsButton() {
                        return {
                            show: false
                        }
                    }
                </script>
                <x-common.button.a href="/admin/app?backUrl={{ urlencode($backUrl) }}">New</x-common.button.a>
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
                <label>Type</label>
                <select class="border px-2 py-0.5" name="type">
                    <option value="any" {{ Request()->type == 'any' ? 'selected' : '' }}>any</option>
                    @foreach ($types as $type_id => $type_name)
                        <option value="{{ $type_id }}" {{ Request()->type === (string)$type_id ? 'selected' : '' }}>{{ $type_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-x-2">
                <label>Price</label>
                <span>from </span>
                <input type="number" name="price_from" value="{{ Request()->price_from }}" class="border px-2 py-0.5">
                <span>to </span>
                <input type="number" name="price_to" value="{{ Request()->price_to }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Brand</label>
                <input type="text" name="brand" value="{{ Request()->brand }}" class="border px-2 py-0.5">
            </div>
        </x-slot>

        {{ $items->withQueryString()->links('vendor.pagination.custom-tailwind', ['allowedPerPages' => $allowedPerPages]) }}
        <div class="overflow-auto">
            <form class="deleteItemsForm" action="delete_products" method="post">
                @csrf
                <input type="hidden" name="backUrl" value="{{ $backUrl }}">
                <x-common.table.table x-data="tableComponent()">
                    <x-slot name="thead">
                        <x-common.table.th><input type="checkbox" @change="check" x-bind:checked="checked"></x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="name" />
                        </x-common.table.th>
                        <x-common.table.th>type</x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="price" />
                        </x-common.table.th>
                        <x-common.table.th>brand</x-common.table.th>
                        <x-common.table.th>os</x-common.table.th>
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
                            <x-common.table.td>{{ $item->type->name }}</x-common.table.td>
                            <x-common.table.td>{{ $item->price }}</x-common.table.td>
                            <x-common.table.td>{{ $item->brand !== null ? $item->brand->name : '' }}</x-common.table.td>
                            <x-common.table.td>
                                <x-common.badge.container>
                                    @foreach ($item->os as $os)
                                        <x-common.badge.badge class="bg-gray-500 text-white">{{ $os->name }}</x-common.badge.badge>
                                    @endforeach
                                </x-common.badge.container>
                            </x-common.table.td>
                            <x-common.table.td>
                                <x-common.button.group  class="justify-end">
                                    <x-common.button.a href="/admin/app?id={{ $item->id }}&backUrl={{ urlencode($backUrl) }}">
                                        Edit
                                    </x-common.button.a>
                                </x-common.button.group>
                            </x-common.table.td>
                        </x-common.table.tr>
                        <x-common.table.tr x-show="!!showSpoilers[{{ $item->id }}]">
                            <x-common.table.td colspan="7">
                                <x-common.h.h4>
                                    Countries
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    {{ $item->countries->map(function($item) {
                                        return $item->name;
                                    })->join(', ') }}
                                </div>
                                <x-common.h.h4>
                                    Images
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    <x-common.button.group>
                                        @foreach ($item->images as $image)
                                            <div class="w-16 h-16 bg-contain bg-no-repeat bg-center" style="background-image: url('{{ $image->url }}');"></div>
                                        @endforeach
                                    </x-common.button.group>
                                </div>
                                <x-common.h.h4>
                                    Links
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    <ul>
                                        @foreach ($item->links as $link)
                                            <li>
                                                <a href="{{ $link->url }}" target="_blank">
                                                    {{ $link->app_store_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <x-common.h.h4>
                                    Change log
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    <a href="{{ $item->change_log_url }}" target="_blank">
                                        {{ $item->change_log_url }}
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
