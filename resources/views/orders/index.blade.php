<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Pedidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Carrito de Compras -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                            {{ __('Carrito de Compras') }}
                        </h3>
                        @foreach ($orders as $order)
                            @if ($order->status == 'carrito')
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                    <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach ($order->orderDetails as $item)
                                            <li class="py-4 first:pt-0 last:pb-0">
                                                <div class="flex items-center space-x-4">
                                                    <img src="{{ $item->product->photo_url }}" alt="{{ $item->product->name }}" 
                                                         class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                                    <div class="flex-1">
                                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                            {{ $item->product->name }}
                                                        </h4>
                                                        <p class="text-gray-600 dark:text-gray-400">
                                                            {{ __('Cantidad:') }} {{ $item->quantity }}
                                                        </p>
                                                        <p class="text-gray-800 dark:text-gray-200 font-bold">
                                                            {{ __('Precio:') }} ${{ number_format($item->product->price * $item->quantity, 2) }}
                                                        </p>
                                                    </div>
                                                    <form action="{{ route('orders.removeFromCart', $item) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-primary-button class="bg-red-600 hover:bg-red-700">
                                                            {{ __('Eliminar') }}
                                                        </x-primary-button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-6 flex justify-between items-center border-t pt-4 border-gray-200 dark:border-gray-600">
                                        <p class="text-xl font-bold text-gray-800 dark:text-gray-200">
                                            Total: ${{ number_format($order->total, 2) }}
                                        </p>
                                        <form action="{{ route('orders.checkout', $order) }}" method="POST">
                                            @csrf
                                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                                {{ __('Finalizar Compra') }}
                                            </x-primary-button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Pedidos Anteriores -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                            {{ __('Pedidos Anteriores') }}
                        </h3>
                        <div class="grid gap-6">
                            @foreach ($orders as $order)
                                @if ($order->status != 'carrito')
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <div>
                                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                                    {{ __('Pedido #:') }} {{ $order->id }}
                                                </h4>
                                                <p class="text-gray-600 dark:text-gray-400">
                                                    {{ __('Fecha de Creacion:') }} {{ $order->created_at }}
                                                </p>
                                                <p class="text-gray-600 dark:text-gray-400">
                                                    {{ __('Fecha de entrega:') }} {{ $order->order_date }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xl font-bold text-gray-800 dark:text-gray-200">
                                                    ${{ number_format($order->total, 2) }}
                                                </p>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    {{ $order->status == 'entregado' ? 'bg-green-100 text-green-800' : 
                                                       ($order->status == 'en_proceso' ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach ($order->orderDetails as $item)
                                                <li class="py-4 first:pt-0 last:pb-0">
                                                    <div class="flex items-center space-x-4">
                                                        <img src="{{ $item->product->photo_url }}" alt="{{ $item->product->name }}" 
                                                             class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                                        <div>
                                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                                {{ $item->product->name }}
                                                            </h4>
                                                            <p class="text-gray-600 dark:text-gray-400">
                                                                {{ __('Cantidad:') }} {{ $item->quantity }}
                                                            </p>
                                                            <p class="text-gray-800 dark:text-gray-200 font-bold">
                                                                {{ __('Precio:') }} ${{ number_format($item->product->price * $item->quantity, 2) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>