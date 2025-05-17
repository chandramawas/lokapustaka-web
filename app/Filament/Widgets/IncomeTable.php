<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class IncomeTable extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pendapatan per Bulan')
            ->description('Tabel rincian pendapatan bulanan beserta tren perubahan')
            ->query(
                Payment::query()
                    ->selectRaw("
                        YEAR(paid_at) AS year,
                        MONTH(paid_at) AS month_num,
                        DATE_FORMAT(MIN(paid_at), '%M %Y') AS month,
                        CASE
                            WHEN LAG(SUM(amount)) OVER (ORDER BY YEAR(paid_at), MONTH(paid_at)) < SUM(amount) THEN 'up'
                            WHEN LAG(SUM(amount)) OVER (ORDER BY YEAR(paid_at), MONTH(paid_at)) > SUM(amount) THEN 'down'
                            ELSE 'stay'
                        END AS trend,
                        ROUND(
                            (SUM(amount) - LAG(SUM(amount)) OVER (ORDER BY YEAR(paid_at), MONTH(paid_at))) /
                            NULLIF(LAG(SUM(amount)) OVER (ORDER BY YEAR(paid_at), MONTH(paid_at)), 0) * 100,
                            1
                        ) AS percentage,
                        SUM(amount) AS total
                    ")
                    ->groupByRaw("YEAR(paid_at), MONTH(paid_at)")
                    ->orderByDesc('year')
                    ->orderByDesc('month_num')
            )
            ->columns([
                TextColumn::make('month')
                    ->label('Bulan')
                    ->weight(fn($record) => $record->year == now()->year && $record->month_num == now()->month ? FontWeight::Bold : null),

                TextColumn::make('total')
                    ->label('Pendapatan')
                    ->money()
                    ->weight(fn($record) => $record->year == now()->year && $record->month_num == now()->month ? FontWeight::Bold : null),

                TextColumn::make('trend')
                    ->label('Tren')
                    ->icon(fn($state) => match ($state) {
                        'up' => 'heroicon-m-arrow-trending-up',
                        'down' => 'heroicon-m-arrow-trending-down',
                        default => 'heroicon-m-minus',
                    })
                    ->iconPosition(IconPosition::Before)
                    ->formatStateUsing(fn($state, $record) => $record->percentage ? ($record->percentage > 0 ? '+' : '') . $record->percentage . '%' : null)
                    ->color(fn($state) => match ($state) {
                        'up' => 'success',
                        'down' => 'danger',
                        default => 'gray',
                    })
                    ->tooltip(fn($state, $record) => "Perubahan dari bulan sebelumnya: " . ($record->percentage > 0 ? '+' : '') . $record->percentage . '%')
                    ->weight(fn($record) => $record->year == now()->year && $record->month_num == now()->month ? FontWeight::Bold : null),
            ])
            ->paginated([6, 12])
            ->defaultPaginationPageOption(6);
    }

    public function getTableRecordKey(mixed $record): string
    {
        return $record->year . '-' . str_pad($record->month_num, 2, '0', STR_PAD_LEFT);
    }
}
