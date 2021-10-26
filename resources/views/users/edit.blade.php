<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Validation Errors -->
                <div class="pt-3 px-4">
                    <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                </div>

                <!-- Editing form -->
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="pt-3 px-4">
                        <div class="mb-3">
                            <x-label for="name" :value="__('Name')"/>
                            <x-input id="name" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                     name="name" :value="$user->name" readonly disabled/>
                        </div>
                        <div class="mb-3">
                            <x-label for="email" :value="__('Email')"/>
                            <x-input id="email" class="block mt-1 w-full text-gray-300 text-sm" type="email"
                                     name="email" :value="$user->email" readonly disabled/>
                        </div>
                        <div>
                            <x-label for="role_id" :value="__('Role')"/>
                            <select name="role_id" id="role_id"
                                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full text-sm rounded-md shadow-sm">
                                @foreach($roles as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $user->role_id ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="px-4 py-5">
                        <x-button>{{ __('Save') }}</x-button>

                        <!-- Cancel -->
                        <a href="{{ url()->current() == url()->previous() ? route('users.index') : url()->previous() }}"
                           class="text-indigo-600 pl-3 hover:text-indigo-900 text-sm font-medium">{{ __('Go back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
