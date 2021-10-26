<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Create user -->
                <div class="py-2 text-right flex justify-end items-center mr-3">
                    <a href="{{ route('users.create') }}"
                       class="border text-sm hover:text-white hover:bg-blue-700 rounded border-1 border-blue-700 py-1 text-blue-700 px-4 border-blue-500">
                        {{ __('Create') }}
                    </a>
                </div>

                <!-- Users table -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <x-table-th>{{ __('Name') }}</x-table-th>
                        <x-table-th>{{ __('Email') }}</x-table-th>
                        <x-table-th>{{ __('Role') }}</x-table-th>
                        <x-table-th></x-table-th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                   {{ $user->role->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-end">
                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="text-yellow-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                {{ __('No data available yet') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <!-- Users pagination -->
                <div class="px-4 pb-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
