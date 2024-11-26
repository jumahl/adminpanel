<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Catálogo de Productos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                        {{ __('Catálogo de Productos') }}
                    </h3>
                    <!-- Contenedor principal -->
                    <div class="flex flex-wrap -mx-4">
                        @foreach ($products as $product)
                            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-4 mb-8">
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105">
                                    <div class="relative h-40">
                                        <img src="{{ $product->photo_url }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover">
                                        <div class="absolute top-0 right-0 m-2">
                                            <span class="px-2 py-1 text-sm font-semibold {{ $product->quantity_in_stock > 0 ? 'bg-green-500' : 'bg-red-500' }} text-white rounded-full">
                                                {{ $product->quantity_in_stock > 0 ? 'En Stock' : 'Agotado' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 truncate">
                                            {{ $product->name }}
                                        </h4>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2 h-12 line-clamp-2">
                                            {{ $product->description }}
                                        </p>
                                        <div class="flex justify-between items-center mb-4">
                                            <p class="text-gray-600 dark:text-gray-400">
                                                Stock: {{ $product->quantity_in_stock }}
                                            </p>
                                            <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                                ${{ number_format($product->price, 2) }}
                                            </p>
                                        </div>
                                        @auth
                                            <form action="{{ route('orders.addToCart', $product) }}" method="POST">
                                                @csrf
                                                <div class="flex items-center gap-2">
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="1" 
                                                           min="1"
                                                           max="{{ $product->quantity_in_stock }}"
                                                           class="w-20 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                                    <button type="submit" 
                                                            {{ $product->quantity_in_stock <= 0 ? 'disabled' : '' }}
                                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
                                                        {{ __('Agregar al Carrito') }}
                                                    </button>
                                                </div>
                                            </form>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación con diseño mejorado -->
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white dark:bg-gray-800 px-4 py-3 rounded-lg shadow-sm">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>