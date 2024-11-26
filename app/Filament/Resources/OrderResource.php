<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('address'),
                        TextInput::make('contact'),
                    ]),
                DatePicker::make('order_date')
                    ->required(),
                Repeater::make('orderDetails')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->label('Producto')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        $set('unit_price', $product->price);
                                    }
                                }
                            }),
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(function (callable $get) {
                                $product = Product::find($get('product_id'));
                                return $product ? $product->quantity_in_stock : null;
                            })
                            ->label('Cantidad')
                            ->required()
                            ->reactive(),
                        TextInput::make('unit_price')
                            ->label('Precio Unitario')
                            ->disabled()
                            ->dehydrated(true)
                            ->numeric()
                            ->required(),
                    ])
                    ->minItems(1)
                    ->maxItems(50)
                    ->label('Productos')
                    ->columns(2)
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $orderDetails = $get('orderDetails') ?? [];
                        $total = 0;
                        foreach ($orderDetails as $index => $orderDetail) {
                            if (isset($orderDetail['product_id']) && isset($orderDetail['quantity'])) {
                                $productInfo = Product::find($orderDetail['product_id']);
                                if ($productInfo) {
                                    $quantity = (float) $orderDetail['quantity'];
                                    $price = (float) $productInfo->price;
                                    $total += $price * $quantity;
                                }
                            }
                        }
                        $set('total', number_format($total, 2, '.', ''));
                    }),
                TextInput::make('total')
                    ->label('Total Pedido')
                    ->readonly()
                    ->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(number_format($state, 2, '.', ''));
                    }),
                Select::make('status')
                    ->label('Estado del Pedido')
                    ->options([
                        'en_proceso' => 'En Proceso',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                    ])
                    ->default('en_proceso')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('user.name')->label('Usuario')->searchable()->weight(FontWeight::Bold),
                    TextColumn::make('user.contact')->label('Contacto'),
                    TextColumn::make('order_date')->label('Fecha del Pedido')->dateTime('d/m/y')->sortable(),
                    TextColumn::make('orderDetails.product.name')->label('Producto'),
                    TextColumn::make('orderDetails.quantity')->label('Cantidad'),
                    TextColumn::make('total')->label('Total'),
                    TextColumn::make('status')->label('Estado')->searchable()
                        ->badge()
                        ->color(fn (Order $record) => match ($record->status) {
                            'en_proceso' => 'warning',
                            'entregado' => 'success',
                            'cancelado' => 'danger',
                            'pendiente' => 'primary',
                            'carrito' => 'secondary',
                        })
                        ->icons(fn (Order $record) => match ($record->status) {
                            'en_proceso' => ['heroicon-o-clock'],
                            'entregado' => ['heroicon-o-check-circle'],
                            'cancelado' => ['heroicon-o-x-circle'],
                            'pendiente' => ['heroicon-o-exclamation-circle'],
                            'carrito' => ['heroicon-o-shopping-cart'],
                        }),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado del Pedido')
                    ->options([
                        'en_proceso' => 'En Proceso',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                        'pendiente' => 'Pendiente',
                        'carrito' => 'Carrito',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}