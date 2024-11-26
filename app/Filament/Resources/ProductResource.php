<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-s-tag';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(20)
                    ->disabled(fn ($record) => $record !== null),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->disabled(fn ($record) => $record !== null)
                    ->maxLength(60),
                TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity_in_stock')
                    ->label('Cantidad en Existencia')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                FileUpload::make('photo')
                    ->label('Imagen del producto')
                    ->image()
                    ->directory('productos')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')->sortable()->searchable()->label('Nombre'),
            TextColumn::make('description')->sortable()->label('Descripción'),
            TextColumn::make('price')->sortable()->label('Precio'),
            TextColumn::make('quantity_in_stock')->sortable()->label('Cantidad en Existencia'),
            ImageColumn::make('photo')->label('Imagen')->sortable(),
        ])
            ->filters([
                // Puedes añadir filtros aquí si es necesario
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}