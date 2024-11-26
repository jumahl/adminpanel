<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pedido creado')
            ->body('El pedido ha sido creado exitosamente.');
    }

    protected function handleRecordCreation(array $data): Order
    {
        $order = Order::create([
            'user_id' => $data['user_id'],
            'order_date' => $data['order_date'],
            'total' => $data['total'],
            'status' => $data['status'],
        ]);

        $errors = [];

        if (isset($data['orderDetails']) && is_array($data['orderDetails'])) {
            foreach ($data['orderDetails'] as $detail) {
                $productModel = Product::find($detail['product_id']);
                if ($productModel) {
                    if ($productModel->quantity_in_stock >= $detail['quantity']) {
                        $productModel->decrement('quantity_in_stock', $detail['quantity']);
                        OrderDetail::create([
                            'order_id' => $order->id,
                            'product_id' => $detail['product_id'],
                            'quantity' => $detail['quantity'],
                            'unit_price' => $productModel->price,
                        ]);
                    } else {
                        $errors[] = "No hay suficiente cantidad en existencia para el producto: {$productModel->name}";
                    }
                } else {
                    $errors[] = "Producto no encontrado: ID {$detail['product_id']}";
                }
            }
        }

        if (!empty($errors)) {
            $order->delete();
            throw new \Exception(implode("\n", $errors));
        }

        return $order;
    }
}