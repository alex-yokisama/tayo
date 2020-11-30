<x-custom-layout>

    <x-slot name="title">
        Users
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Users</x-common.h.h1>
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
                <label>Email</label>
                <input type="text" name="email" value="{{ Request()->email }}" class="border px-2 py-0.5">
            </div>
            <div class="space-x-2">
                <label>Roles</label>
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($roles as $role)
                        <label>
                            <input type="checkbox" {{ collect(Request()->roles)->contains($role->id) ? 'checked' : '' }} name="roles[]" value="{{ $role->id }}">
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        </x-slot>

        {{ $items->withQueryString()->links('vendor.pagination.custom-tailwind', ['allowedPerPages' => $allowedPerPages]) }}
            <x-common.table.table x-data="tableComponent()">
                <x-slot name="thead">
                    <x-common.table.th>
                        <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="name" />
                    </x-common.table.th>
                    <x-common.table.th>
                        <x-common.sortable sort="{{ $sort }}" order="{{ $order }}" name="email" />
                    </x-common.table.th>
                    <x-common.table.th>roles</x-common.table.th>
                    <x-common.table.th></x-common.table.th>
                </x-slot>
                @foreach ($items as $item)
                    <x-common.table.tr>
                        <x-common.table.td>{{ $item->name }}</x-common.table.td>
                        <x-common.table.td>{{ $item->email }}</x-common.table.td>
                        <x-common.table.td>
                            <x-common.badge.container>
                                @foreach ($item->roles as $role)
                                    <x-common.badge.badge class="bg-gray-500 text-white">{{ $role->name }}</x-common.badge.badge>
                                @endforeach
                            </x-common.badge.container>
                        </x-common.table.td>
                        <x-common.table.td>
                            <x-common.button.group  class="justify-end">
                                <x-common.button.a href="/admin/user?id={{ $item->id }}&backUrl={{ urlencode($backUrl) }}">
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
                        sort: '{{ $sort }}',
                        order: '{{ $order }}',
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
        {{ $items->withQueryString()->links('vendor.pagination.custom-tailwind', ['allowedPerPages' => $allowedPerPages]) }}
    </x-list>
</x-custom-layout>
