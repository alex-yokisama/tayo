<x-custom-layout>

    <x-slot name="title">
        Products
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Products</x-common.h.h1>
            <x-common.button.group>
                <x-common.a.a x-data="deleteItemsButton()" @click.prevent="$dispatch('show-delete-items-modal');" class="text-red-500" href="#">Delete</x-common.a.a>
                <script>
                    function deleteItemsButton() {
                        return {
                            show: false
                        }
                    }
                </script>
                <x-common.button.a href="/admin/product?backUrl={{ urlencode($backUrl) }}">New</x-common.button.a>
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
                <label>Text</label>
                <input type="text" name="text" value="{{ Request()->text }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Price</label>
                <span>from </span>
                <input type="number" name="price_from" value="{{ Request()->price_from }}" class="border px-2 py-0.5">
                <span>to </span>
                <input type="number" name="price_to" value="{{ Request()->price_to }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Date created</label>
                <span>from </span>
                <input type="date" name="created_at_from" value="{{ Request()->created_at_from }}" class="border px-2 py-0.5">
                <span>to </span>
                <input type="date" name="created_at_to" value="{{ Request()->created_at_to }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Date published</label>
                <span>from </span>
                <input type="date" name="date_publish_from" value="{{ Request()->date_publish_from }}" class="border px-2 py-0.5">
                <span>to </span>
                <input type="date" name="date_publish_to" value="{{ Request()->date_publish_to }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Categories</label>
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($categories as $category)
                        <label>
                            <input type="checkbox" {{ collect(Request()->categories)->contains($category->id) ? 'checked' : '' }} name="categories[]" value="{{ $category->id }}">
                            {{ $category->name }}
                        </label>
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
                <label>Countries</label>
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($countries as $country)
                        <label>
                            <input type="checkbox" {{ collect(Request()->countries)->contains($country->id) ? 'checked' : '' }} name="countries[]" value="{{ $country->id }}">
                            {{ $country->name }}
                        </label>
                    @endforeach
                </div>
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
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="sku" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="model" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="model_family">Model family</x-common.sortable>
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="category" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="brand" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="country" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="created_at">Created</x-common.sortable>
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="date_publish">Published</x-common.sortable>
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="price_msrp">MSRP</x-common.sortable>
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="price_current">Price</x-common.sortable>
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="is_promote">is&nbsp;promote</x-common.sortable>
                        </x-common.table.th>
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
                            <x-common.table.td>{{ $item->sku }}</x-common.table.td>
                            <x-common.table.td>{{ $item->model }}</x-common.table.td>
                            <x-common.table.td>{{ $item->model_family }}</x-common.table.td>
                            <x-common.table.td>{{ $item->category !== null ? $item->category->name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->brand !== null ? $item->brand->name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->country !== null ? $item->country->name : '' }}</x-common.table.td>
                            <x-common.table.td class="whitespace-no-wrap">{{ $item->created_at }}</x-common.table.td>
                            <x-common.table.td class="whitespace-no-wrap">{{ $item->date_publish }}</x-common.table.td>
                            <x-common.table.td>{{ $item->price_msrp }}</x-common.table.td>
                            <x-common.table.td>{{ $item->price_current }}</x-common.table.td>
                            <x-common.table.td>{{ $item->is_promote ? '+' : '-' }}</x-common.table.td>
                            <x-common.table.td>
                                <x-common.button.group  class="justify-end">
                                    <x-common.button.a href="/admin/product?id={{ $item->id }}&backUrl={{ urlencode($backUrl) }}">
                                        Edit
                                    </x-common.button.a>
                                </x-common.button.group>
                            </x-common.table.td>
                        </x-common.table.tr>
                        <x-common.table.tr x-show="!!showSpoilers[{{ $item->id }}]">
                            <x-common.table.td colspan="14">
                                <x-common.h.h4>
                                    Target countries
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    {{ $item->targetCountries->map(function($item) {
                                        return $item->name;
                                    })->join(', ') }}
                                </div>
                                <x-common.h.h4>
                                    Attributes
                                </x-common.h.h4>
                                <div class="p-2 mb-2">
                                    <table>
                                        @foreach ($item->attributes as $attribute)
                                            <tr>
                                                <td class="px-2 py-0.5">
                                                    {{ $attribute->name }}
                                                </td>
                                                <td class="px-2 py-0.5">
                                                    @if ($attribute->type == 4)
                                                        {{ $attribute->valueForProduct($item->id)->name }}
                                                    @elseif ($attribute->type == 5)
                                                        {{ $attribute->valueForProduct($item->id)->map(function($item) {
                                                            return $item->name;
                                                        })->join(', ') }}
                                                    @else
                                                        {{ $attribute->valueForProduct($item->id) }}
                                                    @endif

                                                    {{ $attribute->measure !== null ? $attribute->measure->name : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <table>
                                    <tr>
                                        <td>
                                            <x-form.container>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Length
                                                    </x-slot>
                                                    {{ $item->size_length }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Width
                                                    </x-slot>
                                                    {{ $item->size_width }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Height
                                                    </x-slot>
                                                    {{ $item->size_height }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Color
                                                    </x-slot>
                                                    {{ $item->color }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Weight
                                                    </x-slot>
                                                    {{ $item->weight }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Battery size
                                                    </x-slot>
                                                    {{ $item->battery_size }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Battery life
                                                    </x-slot>
                                                    {{ $item->battery_life }}
                                                </x-form.input>
                                            </x-form.container>
                                        </td>
                                        <td>
                                            <x-form.container>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Excerpt
                                                    </x-slot>
                                                    {{ $item->excerpt }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Summary
                                                    </x-slot>
                                                    {{ $item->summary_main }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Reasons to buy
                                                    </x-slot>
                                                    {{ $item->summary_value }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Full overview
                                                    </x-slot>
                                                    {{ $item->full_overview }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        SEO keywords
                                                    </x-slot>
                                                    {{ $item->seo_keywords }}
                                                </x-form.input>
                                                <x-form.input>
                                                    <x-slot name="label">
                                                        Tags
                                                    </x-slot>
                                                    {{ str_replace(',', ', ', $item->tags) }}
                                                </x-form.input>
                                            </x-form.container>
                                        </td>
                                    </tr>
                                </table>

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
