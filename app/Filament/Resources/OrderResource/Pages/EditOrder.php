<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pedido editado')
            ->body('El pedido ha sido editado correctamente.');
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $data = $this->record->attributesToArray();
        
        // Cargar explícitamente los detalles del pedido
        $data['orderDetails'] = $this->record->orderDetails()
            ->with('product')
            ->get()
            ->map(function ($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                ];
            })
            ->toArray();

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function handleRecordUpdate($record, array $data): Order
    {
        $record->update([
            'user_id' => $data['user_id'],
            'order_date' => $data['order_date'],
            'total' => $data['total'],
            'status' => $data['status'],
        ]);

        if (isset($data['orderDetails'])) {
            $existingIds = [];
            foreach ($data['orderDetails'] as $detail) {
                $product = Product::find($detail['product_id']);
                $orderDetail = OrderDetail::updateOrCreate(
                    [
                        'order_id' => $record->id,
                        'product_id' => $detail['product_id'],
                    ],
                    [
                        'quantity' => $detail['quantity'],
                        'unit_price' => $product->price
                    ]
                );
                $existingIds[] = $detail['product_id'];
            }

            OrderDetail::where('order_id', $record->id)
                ->whereNotIn('product_id', $existingIds)
                ->delete();
        }

        return $record->fresh();
    }
}