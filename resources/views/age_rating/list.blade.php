<x-custom-layout>

    <x-slot name="title">
        Age ratings
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Age ratings</x-common.h.h1>
            <x-common.button.group>
                <x-common.a.a x-data="deleteItemsButton()" @click.prevent="$dispatch('show-delete-items-modal');" class="text-red-500" href="#">Delete</x-common.a.a>
                <script>
                    function deleteItemsButton() {
                        return {
                            show: false
                        }
                    }
                </script>
                <x-common.button.a href="/admin/age_rating?backUrl={{ urlencode($backUrl) }}">New</x-common.button.a>
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
                <label>Minimal age</label>
                <input type="number" name="age_from" value="{{ Request()->age_from }}" class="border px-2 py-0.5">
            </div>
        </x-slot>

        {{ $items->withQueryString()->links('vendor.pagination.custom-tailwind', ['allowedPerPages' => $allowedPerPages]) }}
        <form class="deleteItemsForm" action="delete_age_ratings" method="post">
            @csrf
            <input type="hidden" name="backUrl" value="{{ $backUrl }}">
            <x-common.table.table x-data="tableComponent()">
                <x-slot name="thead">
                    <x-common.table.th><input type="checkbox" @change="check" x-bind:checked="checked"></x-common.table.th>
                    <x-common.table.th>
                        <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="name" />
                    </x-common.table.th>
                    <x-common.table.th>
                        <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="age_from">
                            Minimal age
                        </x-common.sortable>
                    </x-common.table.th>
                    <x-common.table.th></x-common.table.th>
                </x-slot>
                @foreach ($items as $item)
                    <x-common.table.tr>
                        <x-common.table.td><input class="selectAllCheckable" type="checkbox" name="items[]" value="{{ $item->id }}"></x-common.table.td>
                        <x-common.table.td>{{ $item->name }}</x-common.table.td>
                        <x-common.table.td>{{ $item->age_from }}</x-common.table.td>
                        <x-common.table.td>
                            <x-common.button.group  class="justify-end">
                                <x-common.button.a href="/admin/age_rating?id={{ $item->id }}&backUrl={{ urlencode($backUrl) }}">
                                    Edit
                                </x-common.button.a>
                            </x-common.button.group>
                        </x-common.table.td>
                    </x-common.table.tr>
                @endforeach
            </x-common.table.table>
            <script>
                function tableComponent() {
                    return {
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
                        }
                    }
                }
            </script>
        </form>
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
