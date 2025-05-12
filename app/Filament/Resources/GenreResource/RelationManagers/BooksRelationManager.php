<?php

namespace App\Filament\Resources\GenreResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Mokhosh\FilamentRating\RatingTheme;

class BooksRelationManager extends RelationManager
{
    protected static string $relationship = 'books';

    protected static ?string $title = 'Buku';
    protected static ?string $label = 'Buku';
    protected static ?string $icon = 'heroicon-o-book-open';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('cover_url')
                    ->label('Sampul')
                    ->square()
                    ->size(60)
                    ->defaultImageUrl('https://placehold.co/150x220?text=Cover+not+available'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->wrap()
                    ->weight(FontWeight::SemiBold)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('author')
                    ->label('Penulis')
                    ->limit(20)
                    ->searchable(),

                BadgeColumn::make('genres.name')
                    ->label('Genre'),

                TextColumn::make('reading_progress_count')
                    ->label('Jumlah Baca')
                    ->counts('readingProgress')
                    ->tooltip(fn($record) => $record->readingProgress()->where('progress_percent', '>=', 99)->count() . ' selesai')
                    ->suffix(' baca')
                    ->sortable(),

                TextColumn::make('reading_progress_avg_progress_percent')
                    ->label('Rata-Rata Baca')
                    ->avg('readingProgress', 'progress_percent')
                    ->numeric(1)
                    ->default(0)
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state <= 20,
                        'warning' => fn($state) => $state > 20 && $state <= 75,
                        'info' => fn($state) => $state > 75 && $state < 99,
                        'success' => fn($state) => $state >= 99,
                    ])
                    ->suffix('%')
                    ->sortable(),

                RatingColumn::make('reviews_avg_rating')
                    ->label('Rating')
                    ->avg('reviews', 'rating')
                    ->size('xs')
                    ->theme(RatingTheme::HalfStars)
                    ->tooltip(function ($state, $record) {
                        $formatted = number_format($state, 1);
                        $count = $record->reviews()->count();
                        return "★ {$formatted} dari {$count} reviews";
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Ditambahkan')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->defaultSort('reading_progress_count', 'desc')
            ->filters([
                MultiSelectFilter::make('genre')
                    ->label('Genre')
                    ->relationship('genres', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->url(fn($record) => route('filament.admin.resources.books.edit', $record)),
                    ViewAction::make()
                        ->url(fn($record) => route('filament.admin.resources.books.view', $record)),
                ]),
            ]);
    }
}