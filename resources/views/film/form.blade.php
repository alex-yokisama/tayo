<x-custom-layout>

    <x-slot name="title">
        Film
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Film</x-common.h.h1>
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
                            Type
                        </x-slot>
                        <x-common.input.select
                            name="type"
                            :required="true"
                            :selected="old('type') !== null ? old('type') : ($item !== null ? $item->type->id : null)"
                            :options="($types->map(function($item, $index) {
                                return (object)['key' => $index, 'value' => $item];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Age rating
                        </x-slot>
                        <x-common.input.select
                            name="age_rating"
                            :required="true"
                            :selected="old('age_rating') !== null ? old('age_rating') : ($item !== null && $item->age_rating !== null ? $item->age_rating->id : null)"
                            :options="($ageRatings->map(function($item) {
                                return (object)['key' => $item->id, 'value' => $item->name.' ('.$item->age_from.'+)'];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Genres
                        </x-slot>
                        @livewire('film-genre-autocomplete-multiple', ['name' => 'genres[]', 'items' => ($errors->any() ? old('genres') : ($item !== null ? $item->genres : []))])
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Release date
                        </x-slot>
                        <x-common.input.input type="date" name="release_date"
                        value="{{ (old('release_date') !== null) ? (old('release_date')) : (($item != null) ? ($item->release_date) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Trailer link
                        </x-slot>
                        <x-common.input.input type="text" name="trailer_link"
                        value="{{ (old('trailer_link') !== null) ? (old('trailer_link')) : (($item != null) ? ($item->trailer_link) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Description
                        </x-slot>
                        <x-common.input.textarea-limited name="description" limit="1000" value="{{ (old('description') !== null) ? (old('description')) : (($item != null) ? ($item->description) : '') }}"/>
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Logo
                        </x-slot>
                        @livewire('item-images', [
                            'name' => 'image',
                            'multiple' => false,
                            'images' => ($errors->any() ? (old('image') ? [old('image')] : []) : ($item !== null && $item->image !== null ? [$item->image] : []))])
                    </x-form.input>
                </x-form.container>
            </x-slot>

            <x-slot name="Relations">
                <x-form.container>
                    <x-form.input>
                        <x-slot name="label">
                            Director
                        </x-slot>
                        @livewire(
                            'agent-autocomplete',
                            [
                                'name' => 'director',
                                'type' => 1,
                                'item' => $errors->any() ? old('director') : ($item !== null && $item->director !== null ? $item->director->id : null)
                            ],
                            key('film_director'))
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Writer
                        </x-slot>
                        @livewire(
                            'agent-autocomplete',
                            [
                                'name' => 'writer',
                                'type' => 1,
                                'item' => $errors->any() ? old('writer') : ($item !== null && $item->writer !== null ? $item->writer->id : null)
                            ],
                            key('film_writer'))
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Producer
                        </x-slot>
                        @livewire(
                            'agent-autocomplete',
                            [
                                'name' => 'producer',
                                'type' => 1,
                                'item' => $errors->any() ? old('producer') : ($item !== null && $item->producer !== null ? $item->producer->id : null)
                            ],
                            key('film_producer'))
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Production company
                        </x-slot>
                        @livewire(
                            'agent-autocomplete',
                            [
                                'name' => 'production_company',
                                'type' => 0,
                                'item' => $errors->any() ? old('production_company') : ($item !== null && $item->production_company !== null ? $item->production_company->id : null)
                            ],
                            key('film_production_company'))
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Actors
                        </x-slot>
                        @livewire('agent-autocomplete-multiple', [
                            'name' => 'actors[]',
                            'type' => 1,
                            'items' => ($errors->any() ? old('actors') : ($item !== null ? $item->actors : []))])
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

            @if ($item !== null)
                <x-slot name="Reviews">
                    <x-common.button.group  class="my-2">
                        <x-common.button.a href="/admin/film_review?film={{ $item->id }}" target="_blank">New review</x-common.button.a>
                    </x-common.button.group>
                    <x-common.table.table>
                        <x-slot name="thead">
                            <x-common.table.th>
                                title
                            </x-common.table.th>
                            <x-common.table.th>
                                rating
                            </x-common.table.th>
                            <x-common.table.th>
                                summary
                            </x-common.table.th>
                            <x-common.table.th>
                                recomendations
                            </x-common.table.th>
                            <x-common.table.th></x-common.table.th>
                        </x-slot>
                        @foreach ($item->reviews as $review)
                            <x-common.table.tr>
                                <x-common.table.td>
                                    {{ $review->title }}
                                </x-common.table.td>
                                <x-common.table.td>
                                    {{ $review->rating }}
                                </x-common.table.td>
                                <x-common.table.td>
                                    {{ $review->summary_short }}
                                </x-common.table.td>
                                <x-common.table.td>
                                    {{ $review->recomendations->map(function($item) {
                                        return $item->name;
                                    })->join(', ') }}
                                </x-common.table.td>
                                <x-common.table.td>
                                    <x-common.button.group  class="justify-end">
                                        <x-common.button.a href="/admin/film_review?id={{ $review->id }}" target="_blank">
                                            Edit
                                        </x-common.button.a>
                                    </x-common.button.group>
                                </x-common.table.td>
                            </x-common.table.tr>
                        @endforeach
                    </x-common.table.table>
                </x-slot>
            @endif

        </x-common.tabs>
    </form>
</x-custom-layout>
