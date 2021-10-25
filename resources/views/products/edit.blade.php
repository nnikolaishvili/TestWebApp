<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product') }}
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
                <form action="{{ route('products.update', $product->id) }}" id="edit-product-form" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-3 gap-2">
                        <div class="pt-3 px-4 col-span-2">
                            <div class="mb-3">
                                <x-label for="uid" :value="__('UID')"/>
                                <x-input id="uid" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                         name="uid" :value="$product->uid" disabled readonly/>
                            </div>
                            <div class="mb-3">
                                <x-label for="title" :value="__('Title')"/>
                                <x-input id="title" class="block mt-1 w-full text-sm" type="text"
                                         name="title" :value="$product->title"/>
                            </div>
                            <div class="mb-3">
                                <x-label for="code" :value="__('Code')"/>
                                <x-input id="code" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                         name="code" :value="$product->code" disabled readonly/>
                            </div>
                            <div class="mb-3">
                                <x-label for="price" :value="__('Price')"/>
                                <x-input id="price" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                         name="price" :value="$product->price" disabled readonly/>
                            </div>
                            <div class="mb-3">
                                <x-label for="final_price" :value="__('Final Price')"/>
                                <x-input id="final_price" class="block mt-1 w-full text-gray-300 text-sm" type="text"
                                         name="final_price" :value="$product->final_price" disabled readonly/>
                            </div>
                            <div>
                                <x-label for="status" :value="__('Status')"/>
                                <select name="status" id="status" class="block mt-1 w-full text-sm rounded-md shadow-sm
                                border-gray-300focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($statuses as $key => $value)
                                        <option
                                            value="{{ $key }}" {{ $key == $product->status ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="pt-3 pl-4 pr-8">
                            <div class="mb-4" id="image-preview">
                                <img src="{{ $product->image_url ?? asset('images/no_image.png') }}"
                                     alt="no image image" class="mx-auto" style="max-width: 200px">
                            </div>
                            <div>
                                <label for="image_file"
                                       class="block text-center w-1/2 mx-auto cursor-pointer border border-2 border-blue-700 p-1 hover:text-white hover:bg-blue-700 rounded-xl text-blue-700 ">
                                    {{ __('Upload Image') }}
                                </label>
                                <input type="file" name="image_file" id="image_file" class="opacity-0 absolute" style="z-index: -1"/>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-5">
                        <x-button>{{ __('Save') }}</x-button>

                        <!-- Cancel -->
                        <a href="{{ route('products.index') }}"
                           class="text-indigo-600 pl-3 hover:text-indigo-900 text-sm font-medium">{{ __('Go back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
