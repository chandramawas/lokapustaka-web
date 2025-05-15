<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Mokhosh\FilamentRating\RatingTheme;

class SavedBooksRelationManager extends RelationManager
{
    protected static string $relationship = 'savedBooks';

    protected static ?string $title = 'Koleksi Disimpan';
    protected static ?string $label = 'Koleksi Disimpan';
    protected static ?string $icon = 'heroicon-o-bookmark';

    public function getTableQuery(): Builder
    {
        return static::getRelationship()
            ->getQuery()
            ->select('books.*', 'book_user.created_at as bookmarked_at');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('cover_url')
                    ->label(false)
                    ->square()
                    ->size(30)
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

                TextColumn::make('bookmarked_at')
                    ->label('Tanggal Ditambahkan')
                    ->dateTime()
                    ->sinceTooltip()
                    ->sortable(),
            ])
            ->defaultSort('bookmarked_at', 'desc')
            ->filters([
                MultiSelectFilter::make('genre')
                    ->label('Genre')
                    ->relationship('genres', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => route('filament.admin.resources.books.view', $record)),
            ]);
    }
}
