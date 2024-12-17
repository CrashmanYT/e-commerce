<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use App\Filament\Resources\Set;
use Filament\Forms\Set as FormsSet;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Produk')
                    ->live(onBlur: true)
                    ->afterStateUpdated(callback: fn (FormsSet $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique()
                    ->readOnly(),
                TextInput::make('price')
                    ->required()
                    ->label('Harga')
                    ->integer()
                    ->placeholder('Isi Harga Produk'),
                TextInput::make('size')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Isi Ukuran Produk')
                    ->label('Ukuran'),
                TextInput::make('weight')
                    ->required()
                    ->integer()
                    ->placeholder('Isi Berat Produk')
                    ->label('Berat Produk (Kg)'),
                TextInput::make('stock')
                    ->required()
                    ->integer()
                    ->label('Stok Produk')
                    ->placeholder('Isi Stok Produk'),
                FileUpload::make('photo')
                    ->required()
                    ->label('Foto Produk')
                    ->directory('product-photos')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1'),
                Textarea::make('description')
                    ->label('Deskripsi Produk')
                    ->placeholder('Isi Deskripsi Produk')
                    ->autosize()

                    
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('photo')
                ->label('Foto'),
                TextColumn::make('name')
                ->label('Nama Produk'),
                TextColumn::make('price')
                ->label('Harga Produk')
                ->money('IDR'),
                TextColumn::make('size')
                ->label('Ukuran'),
                TextColumn::make('weight')
                ->label('Berat')
                ->suffix('kg'),
                TextColumn::make('stock')
                ->label('Stok')
                ->suffix('pcs')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
