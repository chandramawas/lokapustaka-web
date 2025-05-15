<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use Mokhosh\FilamentRating\RatingTheme;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $label = 'Buku';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $activeNavigationIcon = 'heroicon-s-book-open';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Form Bagian 1 - Detail Buku
                Section::make('Detail Buku')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('author')
                            ->label('Penulis')
                            ->required(),

                        TextInput::make('publisher')
                            ->label('Penerbit')
                            ->nullable(),

                        TextInput::make('isbn')
                            ->label('ISBN')
                            ->numeric()
                            ->minLength(10)
                            ->maxLength(13)
                            ->unique(ignoreRecord: true)
                            ->helperText('Masukkan ISBN-10 atau ISBN-13.')
                            ->nullable(),

                        TextInput::make('year')
                            ->label('Tahun Terbit')
                            ->numeric()
                            ->minValue(1800)
                            ->maxValue(date('Y') + 1)
                            ->required(),

                        TextInput::make('pages')
                            ->label('Jumlah Halaman')
                            ->numeric()
                            ->required(),

                        Select::make('language')
                            ->label('Bahasa')
                            ->options([
                                'Bahasa Indonesia' => 'Bahasa Indonesia',
                                'English' => 'English',
                            ])
                            ->default('Bahasa Indonesia')
                            ->required(),

                        Textarea::make('description')
                            ->label('Deskripsi / Sinopsis')
                            ->autosize()
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columns(2),

                // Form Bagian 2 - Genre
                Section::make('Genre(s)')
                    ->schema([
                        MultiSelect::make('genres')
                            ->label(false)
                            ->placeholder('Pilih satu atau lebih genre...')
                            ->relationship('genres', 'name')
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Genre')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(1),

                // Form Bagian 3 - File & Media
                Section::make('File & Media')
                    ->schema([
                        TextInput::make('cover_url')
                            ->label('Link Sampul Buku')
                            ->url()
                            ->required(),

                        FileUpload::make('epub_path')
                            ->label('File EPUB')
                            ->directory('epubs')
                            ->acceptedFileTypes(['application/epub+zip'])
                            ->helperText('Hanya file dengan format .epub yang diperbolekan.')
                            ->required(),
                    ])
                    ->collapsible()
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->tooltip(fn($record) => $record->readingProgress->count() !== 0 ? $record->readingProgress->where('progress_percent', '>=', 99)->count() . ' selesai' : false)
                    ->suffix(' baca')
                    ->sortable(),

                TextColumn::make('reading_progress_avg_progress_percent')
                    ->label('Rata-Rata Baca')
                    ->avg('readingProgress', 'progress_percent')
                    ->numeric(maxDecimalPlaces: 1)
                    ->default(0)
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state <= 20,
                        'warning' => fn($state) => $state > 20 && $state <= 60,
                        'info' => fn($state) => $state > 60 && $state < 99,
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
            ->defaultSort('updated_at', 'desc')
            ->filters([
                MultiSelectFilter::make('genre')
                    ->label('Genre')
                    ->relationship('genres', 'name')
                    ->multiple()
                    ->preload(),
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
                // View Bagian 1 - Detail Buku
                \Filament\Infolists\Components\Section::make('Detail Buku')
                    ->schema([
                        TextEntry::make('slug')
                            ->label('URL')
                            ->state(fn($record) => route('book.detail', $record->slug))
                            ->badge()
                            ->copyable()
                            ->prefixAction(
                                Action::make('view_epub')
                                    ->icon('heroicon-m-eye')
                                    ->color('gray')
                                    ->url(fn($record) => route('book.detail', $record->slug))
                                    ->openUrlInNewTab()
                                    ->tooltip('Lihat'),
                            )
                            ->columnSpanFull(),

                        TextEntry::make('title')
                            ->label('Judul'),

                        TextEntry::make('genres.name')
                            ->label('Genre')
                            ->badge(),

                        TextEntry::make('author')
                            ->label('Penulis'),

                        TextEntry::make('publisher')
                            ->label('Penerbit')
                            ->placeholder('-'),

                        TextEntry::make('isbn')
                            ->label('ISBN')
                            ->placeholder('-'),

                        TextEntry::make('year')
                            ->label('Tahun Terbit'),

                        TextEntry::make('pages')
                            ->label('Jumlah Halaman'),

                        TextEntry::make('language')
                            ->label('Bahasa'),

                        TextEntry::make('description')
                            ->label('Deskripsi / Sinopsis')
                            ->placeholder('-')
                            ->alignJustify()
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->label('Ditambahkan')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime(),
                    ])
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('book_detail')
                    ->columns(2),

                // View Bagian 2 - File & Media
                \Filament\Infolists\Components\Section::make('File & Media')
                    ->schema([
                        TextEntry::make('cover_url')
                            ->label('Sampul Buku')
                            ->badge()
                            ->prefixAction(
                                Action::make('view_cover')
                                    ->icon('heroicon-m-eye')
                                    ->color('gray')
                                    ->url(fn($record) => $record->cover_url)
                                    ->openUrlInNewTab()
                                    ->tooltip('Lihat Gambar'),
                            ),
                        TextEntry::make('epub_path')
                            ->label('EPUB Files')
                            ->badge()
                            ->prefixAction(
                                Action::make('view_epub')
                                    ->icon('heroicon-m-eye')
                                    ->color('gray')
                                    ->url(fn($record) => route('book.read', $record->slug))
                                    ->openUrlInNewTab()
                                    ->tooltip('Baca Buku'),
                            )
                            ->suffixAction(
                                Action::make('download_epub')
                                    ->icon('heroicon-m-arrow-down-tray')
                                    ->url(fn($record) => url(Storage::url($record->epub_path)))
                                    ->tooltip('Download File EPUB')
                                    ->extraAttributes(['download' => '']),
                            ),
                    ])
                    ->icon('heroicon-o-document-duplicate')
                    ->collapsed()
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('book_file'),

                // View Bagian 3 - Statistik
                \Filament\Infolists\Components\Section::make('Statistik')
                    ->schema([
                        Fieldset::make('Baca')
                            ->schema([
                                TextEntry::make('reading_count')
                                    ->label('Total')
                                    ->state(fn($record) => $record->readingProgress()->count())
                                    ->suffix(' pembaca'),
                                TextEntry::make('reading_completed_count')
                                    ->label('Selesai')
                                    ->state(fn($record) => $record->readingProgress()->where('progress_percent', '>=', 99)->count())
                                    ->suffix(' pembaca'),
                                TextEntry::make('avg_reading_progress')
                                    ->label('Rata-Rata Progress')
                                    ->state(fn($record) => round($record->readingProgress()->avg('progress_percent'), 1))
                                    ->badge()
                                    ->colors([
                                        'danger' => fn($state) => $state <= 20,
                                        'warning' => fn($state) => $state > 20 && $state <= 60,
                                        'info' => fn($state) => $state > 60 && $state < 99,
                                        'success' => fn($state) => $state >= 99,
                                    ])
                                    ->suffix('%'),
                            ])
                            ->columns(4),
                        Fieldset::make('Ulasan')
                            ->schema([
                                TextEntry::make('reviews_count')
                                    ->label('Total')
                                    ->state(fn($record) => $record->reviews()->count())
                                    ->suffix(' ulasan'),
                                TextEntry::make('reviews_with_text_count')
                                    ->label('Dengan Text')
                                    ->state(fn($record) => $record->reviews()->where('review', '!=', null)->count())
                                    ->suffix(' ulasan'),
                                TextEntry::make('reviews_rating_count')
                                    ->label('Jumlah Bintang')
                                    ->state(fn($record) => $record->reviews()->sum('rating'))
                                    ->prefix('★ '),
                                RatingEntry::make('avg_rating')
                                    ->label('Rating')
                                    ->tooltip(fn($state) => '★ ' . number_format($state, 1))
                                    ->state(fn($record) => $record->reviews()->avg('rating'))
                                    ->size('sm')
                                    ->theme(RatingTheme::HalfStars),
                            ])
                            ->columns(4),
                        Fieldset::make('Disimpan')
                            ->schema([
                                TextEntry::make('bookmark_count')
                                    ->label('Total')
                                    ->state(fn($record) => $record->bookmarkedBy()->count())
                                    ->suffix(' pengguna'),
                            ])
                            ->columns(4),
                    ])
                    ->icon('heroicon-o-chart-bar')
                    ->collapsed()
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('book_statistics'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReviewsRelationManager::class,
            RelationManagers\ReadingProgressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
