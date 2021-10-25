<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center">
                    <div class="text-sm pl-3">
                        {{ "Total records: " . $products->total() }}
                    </div>

                    <!-- Search & Export forms row -->
                    <div class="flex items-center">
                        <form action="" class="my-3 mr-3 flex" id="search-products-form">
                            <x-input class="block text-sm mr-3" type="text" name="search"
                                     :value="$searchValue" placeholder="title"/>
                            <x-transparent-button class="hover:bg-blue-500 text-blue-700 px-4 border-blue-500">
                                {{ __('Search') }}
                            </x-transparent-button>
                        </form>
                        <form action="{{ route('products.export') }}" class="my-3 mr-3 flex" method="POST"
                              id="export-products-form">
                            @csrf
                            <input type="hidden" value="{{ $searchValue }}" name="search">
                            <x-transparent-button class="hover:bg-green-500 text-green-700 px-3 border-green-500">
                                {{ __('Export') }} <i class="fas fa-file-export"></i>
                            </x-transparent-button>
                        </form>
                    </div>
                </div>

                <!-- Products table -->
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                    <tr>
                        <x-table-th>{{ __('UID') }}</x-table-th>
                        <x-table-th>{{ __('title') }}</x-table-th>
                        <x-table-th>{{ __('price') }}</x-table-th>
                        <x-table-th>{{ __('status') }}</x-table-th>
                        <x-table-th></x-table-th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $product->uid }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $product->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $product->price }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                   {{ $product->status_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-end">
                                <a href="{{ route('products.show', $product->id) }}"
                                   class="text-blue-700 hover:text-indigo-900">{{ __('Show') }}</a>
                                <span class="mx-1">|</span>
                                <a href="{{ route('products.edit', $product->id) }}"
                                   class="text-yellow-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                <span class="mx-1">|</span>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      id="delete-product-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-600">{{ __('Delete') }}</button>
                                </form>
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

                <!-- Products pagination -->
                <div class="px-4 pb-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
