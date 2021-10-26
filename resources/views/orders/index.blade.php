<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="flex justify-between items-center">
                    <div class="text-sm pl-3">
                        {{ "Total records: " . $orders->total() }}
                    </div>
                    <div class="flex items-center">
                        <form action="" class="my-3 mr-3 flex" id="search-orders-form">
                            <x-input class="block text-sm mr-3" type="text" name="search"
                                     :value="$searchValue" placeholder="order id"/>
                            <x-transparent-button class="hover:bg-blue-500 text-blue-700 px-4 border-blue-500">
                                {{ __('Search') }}
                            </x-transparent-button>
                        </form>
                        @can('export-order')
                            <form action="{{ route('orders.export') }}" class="my-3 mr-3 flex" method="POST"
                                  id="export-orders-form">
                                @csrf
                                <input type="hidden" value="{{ $searchValue }}" name="search">
                                <x-transparent-button class="hover:bg-green-500 text-green-700 px-3 border-green-500">
                                    {{ __('Export') }} <i class="fas fa-file-export"></i>
                                </x-transparent-button>
                            </form>
                        @endcan
                        <form action="{{ route('orders.fetch') }}" class="my-3 mr-3 flex" method="POST"
                              id="fetch-orders-form">
                            @csrf
                            <x-transparent-button id="fetch-orders-button" class="hover:bg-yellow-500 text-yellow-700 px-3 border-yellow-500">
                                {{ __('Refresh table') }}<i class="fas fa-sync ml-1"></i>
                            </x-transparent-button>
                        </form>
                    </div>
                </div>

                <!-- Orders table -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <x-table-th>{{ __('Order ID') }}</x-table-th>
                        <x-table-th>{{ __('Status') }}</x-table-th>
                        <x-table-th></x-table-th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order->order_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                   {{ $order->status->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-end">
                                <a href="{{ route('orders.show', $order->id) }}"
                                   class="text-blue-700 hover:text-indigo-900">{{ __('Show') }}</a>
                                @can('update-order')
                                    <span class="mx-1">|</span>
                                    <a href="{{ route('orders.edit', $order->id) }}"
                                       class="text-yellow-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                @endcan
                                @can('cancel-order')
                                    <span class="mx-1">|</span>
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                                          id="cancel-order-form">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="text-red-600">{{ __('Cancel') }}</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                {{ __('No data available yet') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <!-- Orders pagination -->
                <div class="px-4 pb-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
