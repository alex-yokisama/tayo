<x-custom-layout>

    <x-slot name="title">
        Films
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Films</x-common.h.h1>
            <x-common.button.group>
                <x-common.a.a x-data="deleteItemsButton()" @click.prevent="$dispatch('show-delete-items-modal');" class="text-red-500" href="#">Delete</x-common.a.a>
                <script>
                    function deleteItemsButton() {
                        return {
                            show: false
                        }
                    }
                </script>
                <x-common.button.a href="/admin/film?backUrl={{ urlencode($backUrl) }}">New</x-common.button.a>
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
                <label>Release date</label>
                <span>from </span>
                <input type="date" name="release_date_from" value="{{ Request()->release_date_from }}" class="border px-2 py-0.5">
                <span>to </span>
                <input type="date" name="release_date_to" value="{{ Request()->release_date_to }}" class="border px-2 py-0.5">
            </div>
            <div>
                <label>Age rating</label>
                <x-common.input.select
                    name="age_rating"
                    :required="false"
                    :selected="Request()->age_rating"
                    :options="($ageRatings->map(function($item) {
                        return (object)['key' => $item->id, 'value' => $item->name.' ('.$item->age_from.'+)'];
                    })->toArray())"
                />
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
                <label>Genre</label>
                <input type="text" name="genre" value="{{ Request()->genre }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Director</label>
                <input type="text" name="director" value="{{ Request()->director }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Writer</label>
                <input type="text" name="writer" value="{{ Request()->writer }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Producer</label>
                <input type="text" name="producer" value="{{ Request()->producer }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Production company</label>
                <input type="text" name="production_company" value="{{ Request()->production_company }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Actor</label>
                <input type="text" name="actor" value="{{ Request()->actor }}" class="border px-2 py-0.5">
            </div>
        </x-slot>

        {{ $items->withQueryString()->links('vendor.pagination.custom-tailwind', ['allowedPerPages' => $allowedPerPages]) }}
        <div class="overflow-auto">
            <form class="deleteItemsForm" action="delete_films" method="post">
                @csrf
                <input type="hidden" name="backUrl" value="{{ $backUrl }}">
                <x-common.table.table x-data="tableComponent()">
                    <x-slot name="thead">
                        <x-common.table.th><input type="checkbox" @change="check" x-bind:checked="checked"></x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="name" />
                        </x-common.table.th>
                        <x-common.table.th>
                            <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="release_date">Release date</x-common.sortable>
                        </x-common.table.th>
                        <x-common.table.th>
                            genres
                        </x-common.table.th>
                        <x-common.table.th>
                            type
                        </x-common.table.th>
                        <x-common.table.th>
                            director
                        </x-common.table.th>
                        <x-common.table.th>
                            writer
                        </x-common.table.th>
                        <x-common.table.th>
                            producer
                        </x-common.table.th>
                        <x-common.table.th>
                            production company
                        </x-common.table.th>
                        <x-common.table.th>
                            age rating
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
                            <x-common.table.td>
                                {{ $item->release_date }}
                            </x-common.table.td>
                            <x-common.table.td>
                                {{ $item->genres->map(function($item) {
                                    return $item->name;
                                })->join(', ') }}
                            </x-common.table.td>
                            <x-common.table.td>{{ $item->type->name }}</x-common.table.td>
                            <x-common.table.td>{{ $item->director !== null ? $item->director->full_name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->writer !== null ? $item->writer->full_name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->producer !== null ? $item->producer->full_name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->production_company !== null ? $item->production_company->full_name : '' }}</x-common.table.td>
                            <x-common.table.td>{{ $item->age_rating->name }} ({{ $item->age_rating->age_from }}+)</x-common.table.td>
                            <x-common.table.td>
                                <x-common.button.group  class="justify-end">
                                    <x-common.button.a href="/admin/film?id={{ $item->id }}&backUrl={{ urlencode($backUrl) }}">
                                        Edit
                                    </x-common.button.a>
                                </x-common.button.group>
                            </x-common.table.td>
                        </x-common.table.tr>
                        <x-common.table.tr x-show="!!showSpoilers[{{ $item->id }}]">
                            <x-common.table.td>
                                @if ($item->image != null)
                                    <div class="w-16 h-16 bg-contain bg-no-repeat bg-center" style="background-image: url('{{ $item->imageUrl }}');"></div>
                                @endif
                            </x-common.table.td>
                            <x-common.table.td colspan="2">
                                <span>Actors: </span>
                                <ul>
                                    @foreach ($item->actors as $actor)
                                        <li>{{ $actor->fullname }}</li>
                                    @endforeach
                                </ul>
                            </x-common.table.td>
                            <x-common.table.td colspan="2">
                                <span>Description: </span>
                                <div class="">
                                    {{ $item->description }}
                                </div>
                            </x-common.table.td>
                            <x-common.table.td colspan="2">
                                <span>Trailer: </span>
                                @if ($item->trailer_link != null)
                                    <a href="{{ $item->trailer_link }}" target="_blank">{{ $item->trailer_link }}</a>
                                @endif
                            </x-common.table.td>
                            <x-common.table.td colspan="2">
                                <span>Websites: </span>
                                <ul>
                                    @foreach ($item->websites as $website)
                                        <li><a href="{{ $website->url }}" target="_blank">{{ $website->name }}</a></li>
                                    @endforeach
                                </ul>
                            </x-common.table.td>
                            <x-common.table.td colspan="2">
                                <span>Recomendations: </span>
                                <ul>
                                    @foreach ($item->recomendations as $film)
                                        <li>{{ $film->name }}</li>
                                    @endforeach
                                </ul>
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
