<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Filters\TrashedFilter; 
use Filament\Tables\Actions\Action;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required(),
            TextInput::make('precio')->required()->numeric(),
            TextInput::make('stock')->required()->numeric(),
            Select::make('estado')
                ->options([
                    'Disponible' => 'Disponible',
                    'Agotado' => 'Agotado',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('id'),
            TextColumn::make('nombre'),
            TextColumn::make('precio'),
            TextColumn::make('stock'),
            TextColumn::make('estado'),
            ])
            ->filters([
               TrashedFilter::make(),
                ])
            ->actions([
               Tables\Actions\EditAction::make(),
             Tables\Actions\DeleteAction::make(),
             Action::make('restore')
                 ->label('Restaurar')
                 ->action(fn (Producto $record) => $record->restore())
                 ->visible(fn (Producto $record) => $record->trashed()),
             Action::make('forceDelete')
                 ->label('Eliminar Definitivamente')
                 ->action(fn (Producto $record) => $record->forceDelete())
                 ->visible(fn (Producto $record) => $record->trashed())
                 ->requiresConfirmation()
                 ->color('danger'),
         
             ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
