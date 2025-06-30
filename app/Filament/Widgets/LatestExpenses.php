<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class LatestExpenses extends BaseWidget
{
    protected static ?string $heading = 'Pengeluaran Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Expense::query()
                    ->latest('date_shopping')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_shopping')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Total')
                    ->money('idr')
                    ->sortable(),
                ImageColumn::make('receipt_image')
                    ->label('Struk')
                    ->circular(),
            ])
            ->paginated(false);
    }
} 