<x-custom-layout>

    <x-slot name="title">
        OS
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>OS</x-common.h.h1>
            <x-common.button.group>
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

    <x-common.table.table>
        <x-slot name="thead">
            <x-common.table.th>name</x-common.table.th>
            <x-common.table.th>parent</x-common.table.th>
            <x-common.table.th>Logo</x-common.table.th>
            <x-common.table.th>brand</x-common.table.th>
            <x-common.table.th>license</x-common.table.th>
            <x-common.table.th>version</x-common.table.th>
            <x-common.table.th>Is&nbsp;kernel</x-common.table.th>
            <x-common.table.th></x-common.table.th>
        </x-slot>
        @foreach ($items as $item)
            <x-os.tree :item=$item backUrl="{{ $backUrl }}" />
        @endforeach
    </x-common.table.table>

    @push('modals')
        <div style="display: none;" x-data="deleteItemModal()" x-show="show" x-on:show-delete-item-modal.window="showModal" class="deleteItemsModal fixed w-full h-full z-20 bg-black bg-opacity-50 top-0 left-0 p-4 sm:py-28">
            <div @click.away="show = false" class="relative bg-white w-full sm:w-3/4 md:w-1/2 mx-auto">
                <span @click="show = false" class="absolute top-0 right-0 text-2xl mx-3 font-bold cursor-pointer">&times;</span>
                <div class="p-6 pb-4">
                    <p>Delete this OS? </p>
                    <x-common.button.group class="justify-end">
                        <x-common.a.a href="#" class="text-red-500" @click.prevent="submitDelete">
                            Delete
                        </x-common.a.a>
                        <x-common.a.a href="#" @click.prevent="show = false">
                            Cancel
                        </x-common.a.a>
                    </x-common.button.group>
                </div>
                <form class="deleteItemsForm" style="display: none;" action="delete_oss" method="post">
                    @csrf
                    <input type="hidden" name="backUrl" value="{{ $backUrl }}">
                    <input type="hidden" name="items[]" x-bind:value="item">
                </form>
            </div>
        </div>
        <script>
            function deleteItemModal() {
                return {
                    show: false,
                    item: 0,
                    submitDelete() {
                        document.querySelector('form.deleteItemsForm').submit()
                    },
                    showModal($event) {
                        this.item = $event.detail;
                        this.show = true;
                    }
                }
            }
        </script>
    @endpush

</x-custom-layout>
