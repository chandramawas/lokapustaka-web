<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenreResource\Pages;
use App\Filament\Resources\GenreResource\RelationManagers;
use App\Models\Genre;
use App\Models\ReadingProgress;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use Mokhosh\FilamentRating\RatingTheme;

class GenreResource extends Resource
{
    protected static ?string $model = Genre::class;

    protected static ?string $navigationGroup = 'Lainnya';

    protected static ?string $label = 'Genre';
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $activeNavigationIcon = 'heroicon-s-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Genre')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Masukkan genre yang belum ada sebelumnya.'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Genre')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('books_count')
                    ->label('Total Buku')
                    ->counts('books')
                    ->badge()
                    ->sortable(),
                TextColumn::make('books_reading_count')
                    ->label('Total Baca')
                    ->state(function ($record) {
                        return ReadingProgress::whereHas('book.genres', function ($query) use ($record) {
                            $query->where('genres.id', $record->id);
                        })->count();
                    })
                    ->suffix(' baca')
                    ->sortable(),
                TextColumn::make('books_max_created_at')
                    ->label('Terakhir Ditambahkan')
                    ->sinceTooltip()
                    ->dateTime()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->defaultSort('books_count', 'desc')
            ->modifyQueryUsing(
                fn($query) => $query->withMax('books', 'created_at')
            )
            ->filters([
                SelectFilter::make('books_count')
                    ->label('Jumlah Buku')
                    ->options([
                        '0' => 'Kosong',
                        '1-5' => '1 - 5 Buku',
                        '6-10' => '6 - 10 Buku',
                        '11+' => 'Lebih dari 10',
                    ])
                    ->query(function ($query, $data) {
                        switch ($data['value']) {
                            case '0':
                                $query->has('books', '=', 0);
                                break;
                            case '1-5':
                                $query->has('books', '>=', 1)->has('books', '<=', 5);
                                break;
                            case '6-10':
                                $query->has('books', '>=', 6)->has('books', '<=', 10);
                                break;
                            case '11+':
                                $query->has('books', '>', 10);
                                break;
                        }
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultPaginationPageOption(25);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // View Bagian 1 - Detail
                Section::make('Detail')
                    ->schema([
                        TextEntry::make('slug')
                            ->label('URL')
                            ->state(fn($record) => route('book.genre.collection', $record->slug))
                            ->badge()
                            ->copyable()
                            ->prefixAction(
                                Action::make('view_epub')
                                    ->icon('heroicon-m-eye')
                                    ->color('gray')
                                    ->url(fn($record) => route('book.genre.collection', $record->slug))
                                    ->openUrlInNewTab()
                                    ->tooltip('Lihat'),
                            )
                            ->columnSpanFull(),

                        TextEntry::make('name')
                            ->label('Genre'),

                        TextEntry::make('books_count')
                            ->label('Total Buku')
                            ->state(fn($record) => $record->books()->count())
                            ->badge(),

                        TextEntry::make('created_at')
                            ->label('Ditambahkan')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime(),
                    ])
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('genre_detail'),

                // View Bagian 2 - Statistik
                Section::make('Statistik')
                    ->schema([
                        // Statistik Bagian 1 - Baca
                        Fieldset::make('Baca')
                            ->schema([
                                TextEntry::make('books_reading_count')
                                    ->label('Total (semua)')
                                    ->state(function ($record) {
                                        return ReadingProgress::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->count();
                                    })
                                    ->suffix(' baca'),

                                TextEntry::make('books_reading_avg')
                                    ->label('Total (per buku)')
                                    ->state(function ($record) {
                                        $totalReaders = ReadingProgress::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->count();

                                        $totalBooks = $record->books()->count();

                                        return $totalBooks > 0 ? round($totalReaders / $totalBooks, 1) : 0;
                                    })
                                    ->suffix(' baca/buku'),

                                TextEntry::make('books_reading_completed_count')
                                    ->label('Selesai (semua)')
                                    ->state(function ($record) {
                                        return ReadingProgress::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->where('progress_percent', '>=', 99)->count();
                                    })
                                    ->suffix(' baca'),

                                TextEntry::make('books_reading_completed_avg')
                                    ->label('Selesai (per buku)')
                                    ->state(function ($record) {
                                        $totalReaders = ReadingProgress::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->where('progress_percent', '>=', 99)->count();

                                        $totalBooks = $record->books()->count();

                                        return $totalBooks > 0 ? round($totalReaders / $totalBooks, 1) : 0;
                                    })
                                    ->suffix(' baca/buku'),

                                TextEntry::make('books_avg_reading_progress')
                                    ->label('Rata-Rata Progress')
                                    ->state(fn($record) => round(
                                        ReadingProgress::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->avg('progress_percent'),
                                        1,
                                    ))
                                    ->badge()
                                    ->colors([
                                        'danger' => fn($state) => $state <= 20,
                                        'warning' => fn($state) => $state > 20 && $state <= 60,
                                        'info' => fn($state) => $state > 60 && $state < 99,
                                        'success' => fn($state) => $state >= 99,
                                    ])
                                    ->suffix('%')
                                    ->helperText('Hanya buku yang sudah dibaca.'),
                            ])
                            ->columns(4),

                        // Statistik Bagian 2 - Ulasan
                        Fieldset::make('Ulasan')
                            ->schema([
                                TextEntry::make('books_reviews_count')
                                    ->label('Total (semua)')
                                    ->state(function ($record) {
                                        return Review::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->count();
                                    })
                                    ->suffix(' ulasan'),

                                TextEntry::make('books_reviews_avg')
                                    ->label('Total (per buku)')
                                    ->state(function ($record) {
                                        $totalReviews = Review::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->count();

                                        $totalBooks = $record->books()->count();

                                        return $totalBooks > 0 ? round($totalReviews / $totalBooks, 1) : 0;
                                    })
                                    ->suffix(' ulasan/buku'),

                                TextEntry::make('books_reviews_with_text_count')
                                    ->label('Dengan Text (semua)')
                                    ->state(function ($record) {
                                        return Review::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->where('review', '!=', null)->count();
                                    })
                                    ->suffix(' ulasan'),

                                TextEntry::make('books_reviews_with_text_avg')
                                    ->label('Dengan Text (per buku)')
                                    ->state(function ($record) {
                                        $totalReviews = Review::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->where('review', '!=', null)->count();

                                        $totalBooks = $record->books()->count();

                                        return $totalBooks > 0 ? round($totalReviews / $totalBooks, 1) : 0;
                                    })
                                    ->suffix(' ulasan/buku'),

                                TextEntry::make('books_reviews_rating_count')
                                    ->label('Jumlah Bintang (semua)')
                                    ->state(function ($record) {
                                        return Review::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->sum('rating');
                                    })
                                    ->prefix('★ '),

                                TextEntry::make('books_reviews_rating_avg')
                                    ->label('Jumlah Bintang (per buku)')
                                    ->state(function ($record) {
                                        $totalReviews = Review::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->sum('rating');

                                        $totalBooks = $record->books()->count();

                                        return $totalBooks > 0 ? round($totalReviews / $totalBooks, 1) : 0;
                                    })
                                    ->prefix('★ ')
                                    ->suffix(' /buku'),

                                RatingEntry::make('books_avg_rating')
                                    ->label('Rata-Rata Rating')
                                    ->tooltip(fn($state) => '★ ' . number_format($state, 1))
                                    ->state(function ($record) {
                                        return Review::whereHas('book.genres', function ($query) use ($record) {
                                            $query->where('genres.id', $record->id);
                                        })->avg('rating');
                                    })
                                    ->size('sm')
                                    ->theme(RatingTheme::HalfStars),
                            ])
                            ->columns(4),

                        // Statistik Bagian 3 - Bookmark
                        Fieldset::make('Disimpan')
                            ->schema([
                                TextEntry::make('books_bookmark_count')
                                    ->label('Total (semua)')
                                    ->state(function ($record) {
                                        return \DB::table('book_user')
                                            ->join('books', 'book_user.book_id', '=', 'books.id')
                                            ->join('book_genre', 'books.id', '=', 'book_genre.book_id')
                                            ->where('book_genre.genre_id', $record->id)
                                            ->count();
                                    })
                                    ->suffix(' simpan'),

                                TextEntry::make('books_bookmark_avg')
                                    ->label('Total (per buku)')
                                    ->state(function ($record) {
                                        $totalReviews = \DB::table('book_user')
                                            ->join('books', 'book_user.book_id', '=', 'books.id')
                                            ->join('book_genre', 'books.id', '=', 'book_genre.book_id')
                                            ->where('book_genre.genre_id', $record->id)
                                            ->count();

                                        $totalBooks = $record->books()->count();

                                        return $totalBooks > 0 ? round($totalReviews / $totalBooks, 1) : 0;
                                    })
                                    ->suffix(' ulasan/buku'),
                            ])
                            ->columns(4),
                    ])
                    ->icon('heroicon-o-chart-bar')
                    ->collapsed()
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('genre_statistics'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BooksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGenres::route('/'),
            'create' => Pages\CreateGenre::route('/create'),
            'view' => Pages\ViewGenre::route('/{record}'),
            'edit' => Pages\EditGenre::route('/{record}/edit'),
        ];
    }
}
