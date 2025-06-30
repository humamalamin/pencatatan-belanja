<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                FileUpload::make('receipt_image')
                    ->image()
                    ->directory('receipts')
                    ->openable()
                    ->downloadable()
                    ->preserveFilenames()
                    ->label('Foto Struk')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_shopping')
                    ->label('Tanggal Belanja')
                    ->date()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Total')
                    ->money('idr'),
                ImageColumn::make('receipt_image'),
                TextColumn::make('items_count')
                    ->counts('items') // gunakan withCount di query
                    ->label('Jumlah Item')

            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Lihat Items')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Items')
                    ->modalSubmitAction(false)
                    ->color('info')
                    ->modalCancelActionLabel('Tutup')
                    ->action(fn () => null) // Karena cuma view
                    ->modalContent(function ($record) {
                        return view('filament.components.items-list', [
                            'items' => $record->items,
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('items');
    }
}
