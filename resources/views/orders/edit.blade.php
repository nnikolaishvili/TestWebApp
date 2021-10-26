<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order') }}
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
                <form action="{{ route('orders.update', $order->id) }}" id="edit-order-form" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-2 gap-4">
                        <div class="pt-3 px-4">
                            <div class="mb-3">
                                <x-label for="order_id" :value="__('Order ID')"/>
                                <x-input id="order_id" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                         name="order_id" :value="$order->order_id" disabled readonly/>
                            </div>
                            <div>
                                <x-label for="date" :value="__('Date')"/>
                                <x-input id="date" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                         name="date" :value="$order->date" disabled readonly/>
                            </div>
                        </div>
                        <div class="pt-3 px-3">
                            <div class="mb-3">
                                <x-label for="total" :value="__('Total')"/>
                                <x-input id="total" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                         name="total" :value="$order->total" disabled readonly/>
                            </div>
                            <div>
                                <x-label for="status_id" :value="__('Status')"/>
                                <select name="status_id" id="status_id" class="block mt-1 w-full text-sm rounded-md shadow-sm
                                border-gray-300focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($statuses as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $order->status_id ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-5">
                        <x-button>{{ __('Save') }}</x-button>

                        <!-- Cancel -->
                        <a href="{{ url()->current() == url()->previous() ? route('orders.index') : url()->previous() }}"
                           class="text-indigo-600 pl-3 hover:text-indigo-900 text-sm font-medium">{{ __('Go back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
