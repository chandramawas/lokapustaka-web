<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Mokhosh\FilamentRating\RatingTheme;

class RatingBooksTable extends BaseWidget
{
    protected static ?int $sort = 5;

    public string $filter = 'all';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Buku Terbaik')
            ->description('Berdasarkan bintang terbanyak')
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
                    ->withSum(['reviews' => fn($query) => $this->filter === 'month'
                        ? $query->whereMonth('updated_at', now()->month)
                        : $query
                    ], 'rating')
                    ->withCount(['reviews' => fn($query) => $this->filter === 'month'
                        ? $query->whereMonth('updated_at', now()->month)
                        : $query])
                    ->withAvg(['reviews' => fn($query) => $this->filter === 'month'
                        ? $query->whereMonth('updated_at', now()->month)
                        : $query
                    ], 'rating')
                    ->orderBy('reviews_sum_rating', 'desc')
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
                TextColumn::make('reviews_sum_rating')
                    ->label('Bintang')
                    ->badge()
                    ->color('success')
                    ->prefix('★ ')
                    ->tooltip(fn($record) => 'Dari ' . $record->reviews_count . ' ulasan'),
                RatingColumn::make('reviews_avg_rating')
                    ->label('Rating')
                    ->size('xs')
                    ->theme(RatingTheme::HalfStars)
                    ->tooltip(fn($state) => 'Rating Rata-Rata ★ ' . round($state, 1))
            ])
            ->recordUrl(fn($record) => route('filament.admin.resources.books.view', $record))
            ->paginated(false);
    }
}
