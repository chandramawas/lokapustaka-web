<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ReadingBooksTable extends BaseWidget
{
    protected static ?int $sort = 6;

    public string $filter = 'all';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Buku Terpopuler')
            ->description('Berdasarkan total progress terbanyak')
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
                Book::query()
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
                Tables\Columns\ImageColumn::make('cover_url')
                    ->label(false)
                    ->square()
                    ->height(30)
                    ->defaultImageUrl('https://placehold.co/150x220?text=Cover+not+available'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->lineClamp(1)
                    ->wrap(),
                Tables\Columns\TextColumn::make('reading_progress_sum_progress_percent')
                    ->label('Progress')
                    ->placeholder('0%')
                    ->suffix('%')
                    ->badge()
                    ->tooltip(fn($record) => 'Dari ' . $record->reading_progress_count . ' pembaca')
                    ->color('success'),
                Tables\Columns\TextColumn::make('reading_progress_avg_progress_percent')
                    ->label('Rata-Rata')
                    ->placeholder('0%')
                    ->formatStateUsing(fn($state, $record) => $record->reading_progress_count ? round($state, 1) : null)
                    ->suffix('%')
                    ->badge()
                    ->color('tertiary'),
            ])
            ->recordUrl(fn($record) => route('filament.admin.resources.books.view', $record))
            ->paginated(false);
    }
}
