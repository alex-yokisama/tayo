<x-custom-layout>

    <x-slot name="title">
        Product
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Product</x-common.h.h1>
            <x-common.button.group>
                <x-common.a.a :href="$backUrl">Cancel</x-common.a.a>
                @if (!$is_copy && $item !== null)
                    <x-common.a.a href="product?copy_id={{ $item->id }}&backUrl={{ urlencode($backUrl) }}" class="text-blue-700">Copy</x-common.a.a>
                @endif
                <x-common.button.a href="#" x-data="{}" @click.prevent="$dispatch('submit-save-form')">Save</x-common.button.a>
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

    <form class="editItemForm overflow-x-auto overflow-y-visible" action="" method="post" x-data="{}" @submit-save-form.window="document.querySelector('form.editItemForm').submit()">
        @csrf
        <input type="hidden" name="id" value="{{ (!$is_copy && $item !== null) ? $item->id : ''}}">
        <input type="hidden" name="backUrl" value="{{ $backUrl }}">
        <x-common.tabs>
            <x-slot name="General">
                <x-form.container>
                    <x-form.input>
                        <x-slot name="label">
                            Name
                        </x-slot>
                        <x-common.input.input type="text" name="name"
                        value="{{ (old('name') !== null) ? (old('name')) : (($item != null) ? ($item->name) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Sku
                        </x-slot>
                        <x-common.input.input type="text" name="sku"
                        value="{{ (old('sku') !== null) ? (old('sku')) : (($item != null) ? ($item->sku) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Model
                        </x-slot>
                        <x-common.input.input type="text" name="model"
                        value="{{ (old('model') !== null) ? (old('model')) : (($item != null) ? ($item->model) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Model family
                        </x-slot>
                        <x-common.input.input type="text" name="model_family"
                        value="{{ (old('model_family') !== null) ? (old('model_family')) : (($item != null) ? ($item->model_family) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            MSRP
                        </x-slot>
                        <x-common.input.input type="number" min="0" name="price_msrp"
                        value="{{ (old('price_msrp') !== null) ? (old('price_msrp')) : (($item != null) ? ($item->price_msrp) : '') }}" />
                        <x-common.input.select
                            name="currency_msrp"
                            :required="true"
                            :selected="$errors->any() ? old('currency_msrp') : ($item !== null ? $item->currency_msrp : null)"
                            :options="($currencies->map(function($item) {
                                return (object)['key' => $item->id, 'value' => $item->symbol];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Price
                        </x-slot>
                        <x-common.input.input type="number" min="0" name="price_current"
                        value="{{ (old('price_current') !== null) ? (old('price_current')) : (($item != null) ? ($item->price_current) : '') }}" />
                        <x-common.input.select
                            name="currency_current"
                            :required="true"
                            :selected="$errors->any() ? old('currency_current') : ($item !== null ? $item->currency_current : null)"
                            :options="($currencies->map(function($item) {
                                return (object)['key' => $item->id, 'value' => $item->symbol];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Length (mm)
                        </x-slot>
                        <x-common.input.input type="number" min="0" step="1" name="size_length"
                        value="{{ (old('size_length') !== null) ? (old('size_length')) : (($item != null) ? ($item->size_length) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Width (mm)
                        </x-slot>
                        <x-common.input.input type="number" min="0" step="1" name="size_width"
                        value="{{ (old('size_width') !== null) ? (old('size_width')) : (($item != null) ? ($item->size_width) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Height (mm)
                        </x-slot>
                        <x-common.input.input type="number" min="0" step="1" name="size_height"
                        value="{{ (old('size_height') !== null) ? (old('size_height')) : (($item != null) ? ($item->size_height) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Weight (g)
                        </x-slot>
                        <x-common.input.input type="number" min="0" step="1" name="weight"
                        value="{{ (old('weight') !== null) ? (old('weight')) : (($item != null) ? ($item->weight) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Publication date
                        </x-slot>
                        <x-common.input.input type="date" name="date_publish"
                        value="{{ (old('date_publish') !== null) ? (old('date_publish')) : (($item != null) ? ($item->date_publish) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <label class="space-x-1">
                            <span>Is promoted</span>
                            <input type="checkbox" name="is_promote" value="1"
                            {{ (old('name') !== null) ? ((old('is_promote')) ? 'checked' : '') : (($item !== null && $item->is_promote) ? 'checked' : '') }} />
                        </label>
                    </x-form.input>
                </x-form.container>
            </x-slot>

            <x-slot name="Description">
                <x-form.container>
                    <x-form.input>
                        <x-slot name="label">
                            Excerpt
                        </x-slot>
                        <x-common.input.textarea-limited name="excerpt" limit="100" value="{{ (old('excerpt') !== null) ? (old('excerpt')) : (($item != null) ? ($item->excerpt) : '') }}"/>
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Summary
                        </x-slot>
                        <x-common.input.textarea-limited name="summary_main" limit="500" value="{{ (old('summary_main') !== null) ? (old('summary_main')) : (($item != null) ? ($item->summary_main) : '') }}"/>
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Reasons to buy
                        </x-slot>
                        <x-common.input.textarea-limited name="summary_value" limit="500" value="{{ (old('summary_value') !== null) ? (old('summary_value')) : (($item != null) ? ($item->summary_value) : '') }}"/>
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Full overview
                        </x-slot>
                        <x-common.input.textarea name="full_overview" value="{{ (old('full_overview') !== null) ? (old('full_overview')) : (($item != null) ? ($item->full_overview) : '') }}"/>
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            SEO keywords
                        </x-slot>
                        <x-common.input.textarea-limited name="seo_keywords" limit="255" value="{{ (old('seo_keywords') !== null) ? (old('seo_keywords')) : (($item != null) ? ($item->seo_keywords) : '') }}"/>
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Tags
                        </x-slot>
                        <x-common.tags name="tags[]" :items="(old('name') !== null ? old('tags') : ($item !== null && $item->tags !== null ? explode(',', $item->tags) : []))" />
                    </x-form.input>
                </x-form.container>
            </x-slot>

            <x-slot name="Images">
                <x-form.container>
                    <x-form.input>
                        @livewire('item-images', [
                            'name' => 'images[]',
                            'multiple' => true,
                            'images' => ($errors->any() ?
                                            (old('images') ? old('images') : []) :
                                            ($item !== null && $item->images !== null ? $item->images->map(function($item) {
                                                return $item->path;
                                            }) : []))])
                    </x-form.input>
                </x-form.container>
            </x-slot>

            <x-slot name="Relations">
                <x-form.container>
                    <x-form.input>
                        <x-slot name="label">
                            Category
                        </x-slot>
                        <x-common.input.select x-data="categorySelect()" x-ref='categorySelect' x-on:change="categoryChanged($refs)" x-init="categoryChanged($refs)"
                            name="category"
                            :required="true"
                            :selected="old('category') !== null ? old('category') : ($item !== null && $item->category !== null ? $item->category->id : null)"
                            :options="($categories->map(function($item) {
                                return (object)['key' => $item->id, 'value' => $item->name];
                            })->toArray())"
                        />
                        @once
                            @push('footerScripts')
                                <script>
                                    function categorySelect() {
                                        return {
                                            categoryChanged($refs) {
                                                Livewire.emit('categoryChanged', $refs.categorySelect.value);
                                            }
                                        }
                                    }
                                </script>
                            @endpush
                        @endonce
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Brand
                        </x-slot>
                        <x-common.input.select
                            name="brand"
                            :required="true"
                            :selected="old('brand') !== null ? old('brand') : ($item !== null && $item->brand !== null ? $item->brand->id : null)"
                            :options="($brands->map(function($item) {
                                return (object)['key' => $item->id, 'value' => $item->name];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Country
                        </x-slot>
                        <x-common.input.select
                            name="country"
                            :required="true"
                            :selected="old('country') !== null ? old('country') : ($item !== null && $item->country !== null ? $item->country->id : null)"
                            :options="($countries->map(function($item) {
                                return (object)['key' => $item->id, 'value' => $item->name];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Target countries
                        </x-slot>
                        @livewire('country-autocomplete-multiple', ['name' => 'countries[]', 'items' => ($errors->any() ? old('countries') : ($item !== null ? $item->targetCountries : []))])
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Similar products
                        </x-slot>
                        @livewire('similar-products-autocomplete', [
                            'name' => 'similar[]',
                            'ownId' => ($item !== null && !$is_copy ? $item->id : 0),
                            'items' => ($errors->any() ? old('similar') : ($item !== null ? $item->similarProducts : []))])
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Released with OS
                        </x-slot>
                        @livewire('os-autocomplete', [
                            'name' => 'released_with_os',
                            'item' => (
                                $errors->any() ? old('released_with_os') :
                                ($item !== null && $item->releasedWithOS !== null ? $item->releasedWithOS->id : null)
                            )
                        ])
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Updatable to OS
                        </x-slot>
                        @livewire('os-autocomplete-multiple', [
                            'name' => 'updatable_to_os[]',
                            'items' => ($errors->any() ? old('updatable_to_os') : ($item !== null ? $item->updatableToOS : []))])
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Websites
                        </x-slot>
                        @livewire('website-autocomplete-multiple', [
                            'name' => 'websites[]',
                            'items' => ($errors->any() ? old('websites') : ($item !== null ? $item->websites : []))])
                    </x-form.input>
                </x-form.container>
                <div class="spacer h-40"></div>
            </x-slot>

            @foreach ($attributeKinds as $kind_id => $kind_name)
                <x-slot :name="$kind_name">
                    @livewire('product-attributes', [
                        'productId' => ($item !== null ? $item->id : null),
                        'old' => old('product_attributes'),
                        'kind_id' => $kind_id
                    ], key('product_attribute_'.$kind_id))
                </x-slot>
            @endforeach

            <x-slot name="Content">
                <div x-data='productContents(
                    @json(
                        $errors->any() ?
                        old('contents') :
                        ($item !== null ? $item->contents : [])
                    )
                )'>
                    <x-common.table.table>
                        <x-slot name="thead">
                            <x-common.table.th>Type</x-common.table.th>
                            <x-common.table.th>Title</x-common.table.th>
                            <x-common.table.th>Description</x-common.table.th>
                            <x-common.table.th>URL</x-common.table.th>
                            <x-common.table.th></x-common.table.th>
                        </x-slot>
                        <template x-for="(item, index) in items" :key="item">
                            <x-common.table.tr>
                                <x-common.table.td class="align-top">
                                    <x-common.input.select
                                    name=""
                                    x-bind:name="`contents[${index}][type_id]`"
                                    x-model="items[index].type_id"
                                    :required="true"
                                    :options="($contentTypes->map(function($item, $index) {
                                        return (object)['key' => $index, 'value' => $item];
                                    })->toArray())"/>
                                </x-common.table.td>
                                <x-common.table.td class="align-top">
                                    <x-common.input.input type="text" x-bind:name="`contents[${index}][title]`" x-model="items[index].title" />
                                </x-common.table.td>
                                <x-common.table.td class="align-top">
                                    <textarea
                                    class="block border resize-none px-2 py-0.5"
                                    x-model="items[index].description"
                                    x-bind:name="`contents[${index}][description]`"
                                    cols="50"></textarea>
                                </x-common.table.td>
                                <x-common.table.td class="align-top">
                                    <x-common.input.input type="text" x-bind:name="`contents[${index}][url]`" x-model="items[index].url" />
                                </x-common.table.td>
                                <x-common.table.td class="align-top">
                                    <x-common.a.a href="#" class="text-red-500" x-on:click.prevent="remove(index)">remove</x-common.a.a>
                                </x-common.table.td>
                            </x-common.table.tr>
                        </template>
                    </x-common.table.table>
                    <x-common.button.group class="my-2">
                        <x-common.button.a href="#" x-on:click.prevent="add">Add</x-common.button.a>
                    </x-common.button.group>
                </div>
                <script>
                    function productContents(items) {
                        return {
                            items: items,
                            add() {
                                this.items.push({});
                            },
                            remove(index) {
                                this.items.splice(index, 1);
                            }
                        }
                    }
                </script>
            </x-slot>

            <x-slot name="Retailer links">
                @if ($item !== null)
                    @livewire('product-links', ['links' => $item->links->map(function($item) {
                        return [
                            'agent' => $item->agent->id,
                            'price_old' => $item->price_old,
                            'price_new' => $item->price_new,
                            'currency' => $item->currency->id,
                            'link' => $item->link,
                            'description' => $item->description,
                            'is_primary' => $item->is_primary
                        ];
                    })->toArray()])
                @else
                    @livewire('product-links', ['links' => []])
                @endif
            </x-slot>

            @if ($item != null)
                <x-slot name="Price changes">
                    <x-common.table.table>
                        <x-slot name="thead">
                            <x-common.table.th>Date</x-common.table.th>
                            <x-common.table.th>Price type</x-common.table.th>
                            <x-common.table.th>Old price</x-common.table.th>
                            <x-common.table.th>New price</x-common.table.th>
                            <x-common.table.th>Reason</x-common.table.th>
                        </x-slot>
                        @foreach ($item->priceChanges as $change)
                            <x-common.table.tr>
                                <x-common.table.td>{{ $change->created_at }}</x-common.table.td>
                                <x-common.table.td>{{ $change->price_type }}</x-common.table.td>
                                <x-common.table.td>{{ $change->price_old }} {{ $change->oldCurrency->symbol }}</x-common.table.td>
                                <x-common.table.td>{{ $change->price_new }} {{ $change->newCurrency->symbol }}</x-common.table.td>
                                <x-common.table.td>{{ $change->reason }}</x-common.table.td>
                            </x-common.table.tr>
                        @endforeach
                    </x-common.table.table>
                </x-slot>
            @endif
        </x-common.tabs>
    </form>
</x-custom-layout>
