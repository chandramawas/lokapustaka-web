<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use Mokhosh\FilamentRating\RatingTheme;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $label = 'Ulasan';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $activeNavigationIcon = 'heroicon-s-chat-bubble-bottom-center-text';
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
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
                    ->headerActions([
                        Action::make('book_view')
                            ->label('Lihat')
                            ->icon('heroicon-o-eye')
                            ->color('gray')
                            ->url(fn($record) => route('filament.admin.resources.books.view', $record->book)),
                    ])
                    ->columns(2)
                    ->collapsible(),

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
                                'warning' => fn($state) => $state > 20 && $state <= 60,
                                'info' => fn($state) => $state > 60 && $state < 99,
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
                    ->headerActions([
                        Action::make('user_view')
                            ->label('Lihat')
                            ->icon('heroicon-o-eye')
                            ->color('gray')
                            ->url(fn($record) => route('filament.admin.resources.users.view', $record->user)),
                    ])
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->limit(20)
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                ImageColumn::make('book.cover_url')
                    ->label(false)
                    ->square()
                    ->height(30)
                    ->defaultImageUrl('https://placehold.co/150x220?text=Cover+not+available'),
                TextColumn::make('book.title')
                    ->label('Buku')
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('user_book_progress')
                    ->label('Progress')
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
                        'warning' => fn($state) => $state > 20 && $state <= 60,
                        'info' => fn($state) => $state > 60 && $state < 99,
                        'success' => fn($state) => $state >= 99,
                    ])
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('review')
                    ->label('Ulasan')
                    ->placeholder('-')
                    ->limit(200)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                RatingColumn::make('rating')
                    ->label('Rating')
                    ->size('sm')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
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
                Tables\Filters\SelectFilter::make('reading_progress')
                    ->label('Progress Baca')
                    ->options([
                        '0' => '0% (Belum Baca)',
                        '1-20' => '1-20% (Baru mulai)',
                        '21-60' => '21-60% (Masih baca)',
                        '61-98' => '61-98% (Hampir selesai)',
                        '99-100' => '99-100% (Tamat)',
                    ])
                    ->query(function ($query, array $data) {
                        $value = $data['value'] ?? null;

                        if ($value === '0') {
                            return $query->whereDoesntHave('user.readingProgress', function ($q) {
                                $q->whereColumn('book_id', 'reviews.book_id');
                            });
                        }

                        if (!$value || !str_contains($value, '-')) {
                            return $query;
                        }

                        [$min, $max] = explode('-', $value);

                        return $query->whereHas('user.readingProgress', function ($q) use ($min, $max) {
                            $q->whereColumn('book_id', 'reviews.book_id')
                                ->whereBetween('progress_percent', [(int)$min, (int)$max]);
                        });
                    }),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
        ];
    }
}
