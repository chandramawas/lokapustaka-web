<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Mokhosh\FilamentRating\Components\Rating;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use Mokhosh\FilamentRating\RatingTheme;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Ulasan';
    protected static ?string $label = 'Ulasan';
    protected static ?string $icon = 'heroicon-o-chat-bubble-bottom-center-text';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(components: [
                // View Bagian 1 - Buku 
                Section::make('Buku')
                    ->schema([
                        TextEntry::make('book.title')
                            ->label('Judul'),
                        TextEntry::make('book.author')
                            ->label('Penulis'),
                        TextEntry::make('review_count')
                            ->label('Jumlah Ulasan')
                            ->state(fn($record) => $record->book->reviews->count())
                            ->suffix(' ulasan'),
                        RatingEntry::make('avg_rating')
                            ->label('Rating')
                            ->theme(RatingTheme::HalfStars)
                            ->state(fn($record) => round($record->book->reviews->avg('rating'), 1))
                            ->tooltip(fn($state) => $state),
                    ])
                    ->icon('heroicon-o-book-open')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),

                // View Bagian 2 - Pengguna 
                Section::make('Pengguna')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama'),
                        TextEntry::make('user_book_progress')
                            ->label('Progress Buku Ini')
                            ->state(function ($record) {
                                return optional(
                                    $record->user
                                        ->readingProgress()
                                        ->where('book_id', $record->book_id)
                                        ->first(),
                                )->progress_percent ?? 0;
                            })
                            ->badge()
                            ->colors([
                                'danger' => fn($state) => $state <= 20,
                                'warning' => fn($state) => $state > 20 && $state <= 75,
                                'info' => fn($state) => $state > 75 && $state < 99,
                                'success' => fn($state) => $state >= 99,
                            ])
                            ->suffix('%'),
                        TextEntry::make('user_reading_count')
                            ->label('Jumlah Baca')
                            ->state(fn($record) => $record->user->readingProgress()->count())
                            ->suffix(' baca'),
                        TextEntry::make('user_reviews_count')
                            ->label('Jumlah Ulasan')
                            ->state(fn($record) => $record->user->reviews()->count())
                            ->suffix(' ulasan'),
                    ])
                    ->icon('heroicon-o-user')
                    ->columns(4)
                    ->collapsible(),

                RatingEntry::make('rating')
                    ->label('Rating'),

                TextEntry::make('review')
                    ->label('Ulasan')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->limit(30)
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                TextColumn::make('user_book_progress')
                    ->label('Progress Buku Ini')
                    ->state(function ($record) {
                        return optional(
                            $record->user
                                ->readingProgress()
                                ->where('book_id', $record->book_id)
                                ->first(),
                        )->progress_percent ?? 0;
                    })
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state <= 20,
                        'warning' => fn($state) => $state > 20 && $state <= 75,
                        'info' => fn($state) => $state > 75 && $state < 99,
                        'success' => fn($state) => $state >= 99,
                    ])
                    ->suffix('%'),
                TextColumn::make('review')
                    ->label('Ulasan')
                    ->placeholder('-')
                    ->limit(200)
                    ->wrap(),
                RatingColumn::make('rating')
                    ->label('Rating')
                    ->size('sm')
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
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        5 => '5 Bintang',
                        4 => '4 Bintang',
                        3 => '3 Bintang',
                        2 => '2 Bintang',
                        1 => '1 Bintang',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
