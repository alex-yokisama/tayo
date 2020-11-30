<x-custom-layout>

    <x-slot name="title">
        Categories
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Categories</x-common.h.h1>
            <x-common.button.group>
                <x-common.button.a href="/admin/category?backUrl={{ urlencode($backUrl) }}">New</x-common.button.a>
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

    @foreach ($items as $item)
        <x-category.tree :category=$item backUrl="{{ $backUrl }}" />
    @endforeach

    @push('modals')
        <div style="display: none;" x-data="deleteCategoryModal()" x-show="show" x-on:show-delete-category-modal.window="showModal" class="deleteItemsModal fixed w-full h-full z-20 bg-black bg-opacity-50 top-0 left-0 p-4 sm:py-28">
            <div @click.away="show = false" class="relative bg-white w-full sm:w-3/4 md:w-1/2 mx-auto">
                <span @click="show = false" class="absolute top-0 right-0 text-2xl mx-3 font-bold cursor-pointer">&times;</span>
                <div class="p-6 pb-4">
                    <p>Delete this category? </p>
                    <x-common.button.group class="justify-end">
                        <x-common.a.a href="#" class="text-red-500" @click.prevent="submitDelete">
                            Delete
                        </x-common.a.a>
                        <x-common.a.a href="#" @click.prevent="show = false">
                            Cancel
                        </x-common.a.a>
                    </x-common.button.group>
                </div>
                <form class="deleteItemsForm" style="display: none;" action="delete_categories" method="post">
                    @csrf
                    <input type="hidden" name="backUrl" value="{{ $backUrl }}">
                    <input type="hidden" name="items[]" x-bind:value="category">
                </form>
            </div>
        </div>
        <script>
            function deleteCategoryModal() {
                return {
                    show: false,
                    category: 0,
                    submitDelete() {
                        document.querySelector('form.deleteItemsForm').submit()
                    },
                    showModal($event) {
                        this.category = $event.detail;
                        this.show = true;
                    }
                }
            }
        </script>
    @endpush

</x-custom-layout>
