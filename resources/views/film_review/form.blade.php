<x-custom-layout>

    <x-slot name="title">
        Review
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Review</x-common.h.h1>
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
        <input type="hidden" name="id" value="{{ (!$is_copy && $item !== null) ? $item->id : ''}}">
        <input type="hidden" name="backUrl" value="{{ $backUrl }}">
        <x-form.container>
            <x-form.input>
                <x-slot name="label">
                    Film
                </x-slot>
                @livewire('film-autocomplete', [
                    'name' => 'film',
                    'item' => $errors->any() ? old('film') : ($item !== null && $item->film !== null ? $item->film->id : null)
                ])
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Title
                </x-slot>
                <x-common.input.input type="text" name="title"
                value="{{ (old('title') !== null) ? (old('title')) : (($item != null) ? ($item->title) : '') }}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Subtitle
                </x-slot>
                <x-common.input.input type="text" name="subtitle"
                value="{{ (old('subtitle') !== null) ? (old('subtitle')) : (($item != null) ? ($item->subtitle) : '') }}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Rating
                </x-slot>
                <x-common.input.input type="number" min="0" name="rating"
                value="{{ (old('rating') !== null) ? (old('rating')) : (($item != null) ? ($item->rating) : '') }}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Summary
                </x-slot>
                <x-common.input.textarea-limited name="summary" limit="2000" value="{{ (old('summary') !== null) ? (old('summary')) : (($item != null) ? ($item->summary) : '') }}"/>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Positive
                </x-slot>
                <x-common.input.textarea-limited name="positive" limit="2000" value="{{ (old('positive') !== null) ? (old('positive')) : (($item != null) ? ($item->positive) : '') }}"/>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Negative
                </x-slot>
                <x-common.input.textarea-limited name="negative" limit="2000" value="{{ (old('negative') !== null) ? (old('negative')) : (($item != null) ? ($item->negative) : '') }}"/>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Recomendations
                </x-slot>
                @livewire('film-autocomplete-multiple', [
                    'name' => 'recomendations[]',
                    'excludeIds' => ($item !== null ? [$item->id] : []),
                    'items' => ($errors->any() ? old('recomendations') : ($item !== null ? $item->recomendations : []))])
            </x-form.input>
        </x-form.container>
        <div class="spacer h-40"></div>
    </form>
</x-custom-layout>
