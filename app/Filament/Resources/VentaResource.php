<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaResource\Pages;
use App\Filament\Resources\VentaResource\RelationManagers;
use App\Models\Venta;
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
class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cliente_id')
                ->relationship('cliente', 'nombre')->required(),
            Select::make('producto_id')
                ->relationship('producto', 'nombre')->required(),
            TextInput::make('cantidad')->required()->numeric(),
            TextInput::make('precio')->required()->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cliente.nombre'),
                TextColumn::make('producto.nombre'),
                TextColumn::make('cantidad'),
                TextColumn::make('precio'),
                TextColumn::make('created_at')
                 ->label('Fecha y Hora de venta')
                 ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                TrashedFilter::make(),
                ])
            ->actions([
              Tables\Actions\EditAction::make(),
              Tables\Actions\DeleteAction::make(),
              Action::make('restore')
                  ->label('Restaurar')
                  ->action(fn (Venta $record) => $record->restore())
                  ->visible(fn (Venta $record) => $record->trashed()),
              Action::make('forceDelete')
                  ->label('Eliminar Definitivamente')
                  ->action(fn (Venta $record) => $record->forceDelete())
                  ->visible(fn (Venta $record) => $record->trashed())
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
            'index' => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'edit' => Pages\EditVenta::route('/{record}/edit'),
        ];
    }
   
}
