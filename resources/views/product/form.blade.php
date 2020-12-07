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
        <input type="hidden" name="id" value="{{ isset($item) ? $item->id : ''}}">
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
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Price
                        </x-slot>
                        <x-common.input.input type="number" min="0" name="price_current"
                        value="{{ (old('price_current') !== null) ? (old('price_current')) : (($item != null) ? ($item->price_current) : '') }}" />
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
                            Color
                        </x-slot>
                        <x-common.input.input type="text" name="color"
                        value="{{ (old('color') !== null) ? (old('color')) : (($item != null) ? ($item->color) : '') }}" />
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
                            Battery size
                        </x-slot>
                        <x-common.input.input type="number" min="0" name="battery_size"
                        value="{{ (old('battery_size') !== null) ? (old('battery_size')) : (($item != null) ? ($item->battery_size) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Battery life
                        </x-slot>
                        <x-common.input.input type="number" min="0" name="battery_life"
                        value="{{ (old('battery_life') !== null) ? (old('battery_life')) : (($item != null) ? ($item->battery_life) : '') }}" />
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

            <x-slot name="Relations">
                <x-form.container>
                    @livewire('category-select', ['name' => 'category', 'itemId' => ($errors->any() ? (old('category')) : ($item !== null ? $item->category->id : null))])
                    <x-form.input>
                        <x-slot name="label">
                            Brand
                        </x-slot>
                        <x-common.input.select
                            name="brand"
                            :required="true"
                            selected="{{ old('brand') !== null ? old('brand') : ($item !== null && $item->brand !== null ? $item->brand->id : '') }}"
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
                            selected="{{ old('country') !== null ? old('country') : ($item !== null && $item->country !== null ? $item->country->id : '') }}"
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
                </x-form.container>
            </x-slot>

            <x-slot name="Attributes">
                @livewire('product-attributes', ['productId' => ($item !== null ? $item->id : null), 'old' => old('product_attributes')])
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
                            'is_primary' => $item->is_primary
                        ];
                    })])
                @endif
            </x-slot>

            @if ($item != null)
                <x-slot name="Price changes">
                    <x-common.table.table>
                        <x-slot name="thead">
                            <x-common.table.th>Date</x-common.table.th>
                            <x-common.table.th>Old price</x-common.table.th>
                            <x-common.table.th>New price</x-common.table.th>
                            <x-common.table.th>Reason</x-common.table.th>
                        </x-slot>
                        @foreach ($item->priceChanges as $change)
                            <x-common.table.tr>
                                <x-common.table.td>{{ $change->created_at }}</x-common.table.td>
                                <x-common.table.td>{{ $change->price_old }}</x-common.table.td>
                                <x-common.table.td>{{ $change->price_new }}</x-common.table.td>
                                <x-common.table.td>{{ $change->reason }}</x-common.table.td>
                            </x-common.table.tr>
                        @endforeach
                    </x-common.table.table>
                </x-slot>
            @endif
        </x-common.tabs>
    </form>
</x-custom-layout>