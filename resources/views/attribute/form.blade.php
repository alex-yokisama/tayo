<x-custom-layout>

    <x-slot name="title">
        Attribute
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Attribute</x-common.h.h1>
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

    <form class="editItemForm" action="" method="post" x-data="{}" @submit-save-form.window="document.querySelector('form.editItemForm').submit()">
        @csrf
        <input type="hidden" name="id" value="{{ isset($item) ? $item->id : ''}}">
        <input type="hidden" name="backUrl" value="{{ $backUrl }}">
        <x-form.container x-data="attributeForm()" x-init="typeChange">
            <x-form.input>
                <x-slot name="label">
                    Name
                </x-slot>
                <x-common.input.input type="text" name="name"
                value="{{ (old('name') !== null) ? (old('name')) : (($item != null) ? ($item->name) : '')}}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Measure unit
                </x-slot>
                <select class="border-2 px-2 py-0.5" name="measure">
                    <option>-- select --</option>
                    @foreach ($measures as $measure)
                        <option value="{{ $measure->id }}"
                            {{
                                (old('measure') !== null) ?
                                ((old('measure') == $measure->id) ? 'selected' : '') :
                                (($item !== null && $item->measure !== null && $item->measure->id == $measure->id) ? 'selected' : '')
                            }}
                        >
                            {{ $measure->name }}
                        </option>
                    @endforeach
                </select>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Type
                </x-slot>
                <select class="border-2 px-2 py-0.5" name="type" x-model="type" @change="typeChange">
                    @foreach ($types as $type_id => $type_name)
                        <option value="{{ $type_id }}"
                            {{
                                (old('type') !== null) ?
                                ((old('type') == $type_id) ? 'selected' : '') :
                                (($item !== null &&  $item->type == $type_id) ? 'selected' : '')
                            }}
                        >
                            {{ $type_name }}
                        </option>
                    @endforeach
                </select>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Kind
                </x-slot>
                @foreach ($kinds as $kind_id => $kind_name)
                    <label class="block">
                        <input type="radio" name="kind" value="{{ $kind_id }}"
                            {{
                                $errors->any() ?
                                (old('kind') == $kind_id ? 'checked' : '') :
                                (($item !== null && $item->kind == $kind_id) ? 'checked' : '')
                            }}
                        >
                        {{ $kind_name }}
                    </label>
                @endforeach
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Group
                </x-slot>
                <x-common.input.select
                    name="group"
                    :required="true"
                    :selected="$errors->any() ? old('group') : ($item !== null && $item->group !== null ? $item->group->id : null)"
                    :options="($groups->map(function($item) {
                        return (object)['key' => $item->id, 'value' => $item->name];
                    })->toArray())"
                />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Sort order
                </x-slot>
                <x-common.input.input type="number" name="sort_order"
                value="{{ $errors->any() ? (old('sort_order')) : (($item != null) ? ($item->sort_order) : 0) }}" />
            </x-form.input>
            <x-form.input x-show="showOptions">
                <x-slot name="label">
                    Options
                </x-slot>
                <div class="optionsBlock pb-2 space-y-2">
                    @if (old('options') !== null)
                        @foreach (old('options') as $key => $option)
                            <x-common.button.group>
                                <x-common.input.input type="text" name="options[{{ preg_match('/^id_[0-9]+$/', $key) ? $key : '' }}]" value="{{ $option }}" />
                                <x-common.a.a href="#" class="text-red-500" @click="removeOption">Delete</x-common.a.a>
                            </x-common.button.group>
                        @endforeach
                    @elseif ($item !== null)
                        @foreach ($item->options as $option)
                            <x-common.button.group>
                                <x-common.input.input type="text" name="options[id_{{ $option->id }}]" value="{{ $option->name }}" />
                                <x-common.a.a href="#" class="text-red-500" @click="removeOption">Delete</x-common.a.a>
                            </x-common.button.group>
                        @endforeach
                    @endif
                </div>
                <x-common.button.a href="#" title="Ctrl + Q" @click="addOption" class="addOptionButton">More</x-common.button.a>
            </x-form.input>
        </x-form.container>
        <script>
            function attributeForm() {
                return {
                    type: "{{
                        old('type') !== null ?
                        old('type') :
                        ($item !== null ? $item->type : 0)
                    }}",
                    showOptions: false,
                    typeChange() {
                        this.showOptions = this.type == 4 || this.type == 5;
                    },
                    removeOption($event) {
                        $event.target.parentElement.remove();
                    },
                    addOption() {
                        document.querySelector(".optionsBlock").insertAdjacentHTML("beforeend", `
                        <x-common.button.group>
                            <x-common.input.input type="text" name="options[]" value="" />
                            <x-common.a.a href="#" class="text-red-500" @click="removeOption">Delete</x-common.a.a>
                        </x-common.button.group>
                        `);
                    }
                }
            }
        </script>
    </form>

    @push('footerScripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.addEventListener("keypress", function(e) {
                    if (e.ctrlKey && e.code == "KeyQ") {
                        document.querySelector(".addOptionButton").click();
                    }
                });
            });
        </script>
    @endpush
</x-custom-layout>
