<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActiveUsersTable extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public string $filter = 'all';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pengguna Paling Aktif')
            ->description(sprintf('Top 5 pengguna dengan aktivitas membaca tertinggi %s.', $this->filter === 'month' ? 'bulan ini' : 'sepanjang waktu'))
            ->headerActions([
                Tables\Actions\Action::make('all')
                    ->label('Semua Waktu')
                    ->button()
                    ->color($this->filter === 'all' ? 'secondary' : 'gray')
                    ->action(function () {
                        $this->filter = 'all';
                        $this->resetTable();
                    }),
                Tables\Actions\Action::make('month')
                    ->label('Bulan Ini')
                    ->button()
                    ->color($this->filter === 'month' ? 'secondary' : 'gray')
                    ->action(function () {
                        $this->filter = 'month';
                        $this->resetTable();
                    }),
            ])
            ->query(
                User::query()
                    ->withSum(['readingProgress' => fn($query) => $this->filter === 'month'
                        ? $query->whereMonth('updated_at', now()->month)
                        : $query
                    ], 'progress_percent')
                    ->withCount(['readingProgress' => fn($query) => $this->filter === 'month'
                        ? $query->whereMonth('updated_at', now()->month)
                        : $query
                    ])
                    ->withAvg(['readingProgress' => fn($query) => $this->filter === 'month'
                        ? $query->whereMonth('updated_at', now()->month)
                        : $query
                    ], 'progress_percent')
                    ->orderBy('reading_progress_sum_progress_percent', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->state(fn($rowLoop): string => (string)($rowLoop->iteration)),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->wrap()
                    ->weight(FontWeight::SemiBold)
                    ->lineClamp(1),
                Tables\Columns\TextColumn::make('reading_progress_count')
                    ->label('Jumlah')
                    ->suffix(' buku'),
                Tables\Columns\TextColumn::make('reading_progress_sum_progress_percent')
                    ->label('Total Progress')
                    ->placeholder('0%')
                    ->suffix('%')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('reading_progress_avg_progress_percent')
                    ->label('Rata-Rata Progress')
                    ->placeholder('0%')
                    ->formatStateUsing(fn($state, $record) => $record->reading_progress_count ? round($state, 1) : null)
                    ->suffix('%')
                    ->badge()
                    ->color('tertiary'),
            ])
            ->recordUrl(fn($record) => route('filament.admin.resources.users.view', $record))
            ->paginated(false);
    }
}
