<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReadingProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'readingProgress';

    protected static ?string $title = 'Statistik Baca';
    protected static ?string $label = 'Statistik Baca';
    protected static ?string $icon = 'heroicon-o-chart-bar-square';

    public function infolist(Infolist $infolist): Infolist
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
                        TextEntry::make('book_reading_count')
                            ->label('Jumlah Baca')
                            ->state(fn($record) => $record->book->readingProgress()->count())
                            ->suffix(' baca'),
                        TextEntry::make('book_reading_avg')
                            ->label('Rata-Rata Progress')
                            ->state(fn($record) => round($record->book->readingProgress()->avg('progress_percent'), 1) ?? 0)
                            ->badge()
                            ->colors([
                                'danger' => fn($state) => $state <= 20,
                                'warning' => fn($state) => $state > 20 && $state <= 60,
                                'info' => fn($state) => $state > 60 && $state < 99,
                                'success' => fn($state) => $state >= 99,
                            ])
                            ->suffix('%'),
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
                        TextEntry::make('user_reading_count')
                            ->label('Jumlah Baca')
                            ->state(fn($record) => $record->user->readingProgress()->count())
                            ->suffix(' baca'),
                        TextEntry::make('user_reading_avg')
                            ->label('Rata-Rata Progress')
                            ->state(fn($record) => round($record->user->readingProgress()->avg('progress_percent'), 1) ?? 0)
                            ->badge()
                            ->colors([
                                'danger' => fn($state) => $state <= 20,
                                'warning' => fn($state) => $state > 20 && $state <= 60,
                                'info' => fn($state) => $state > 60 && $state < 99,
                                'success' => fn($state) => $state >= 99,
                            ])
                            ->suffix('%'),
                    ])
                    ->icon('heroicon-o-user')
                    ->columns(3)
                    ->collapsible(),

                TextEntry::make('progress_percent')
                    ->label('Progress Baca')
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state <= 20,
                        'warning' => fn($state) => $state > 20 && $state <= 60,
                        'primary' => fn($state) => $state > 60,
                    ])
                    ->suffix('%'),
                TextEntry::make('cfi')
                    ->label('Lokasi CFI')
                    ->badge()
                    ->color('gray')
                    ->copyable(),
                TextEntry::make('created_at')
                    ->label('Awal Baca')
                    ->dateTime()
                    ->sinceTooltip(),
                TextEntry::make('updated_at')
                    ->label('Terakhir Baca')
                    ->dateTime()
                    ->sinceTooltip(),
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
                TextColumn::make('progress_percent')
                    ->label('Progress')
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state <= 20,
                        'warning' => fn($state) => $state > 20 && $state <= 60,
                        'info' => fn($state) => $state > 60 && $state < 99,
                        'success' => fn($state) => $state >= 99,
                    ])
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Awal Baca')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Tanggal Terakhir Baca')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('progress_percent')
                    ->label('Progress Baca')
                    ->options([
                        'belum' => '0% (Belum Baca)',
                        'baru' => '1-20% (Baru Mulai)',
                        'masih' => '21-60% (Masih Baca)',
                        'hampir' => '61-98% (Hampir Selesai)',
                        'selesai' => '99-100% (Selesai)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;

                        if ($value === 'belum') {
                            $query->where('progress_percent', 0);
                        } elseif ($value === 'baru') {
                            $query->whereBetween('progress_percent', [1, 20]);
                        } elseif ($value === 'masih') {
                            $query->whereBetween('progress_percent', [21, 60]);
                        } elseif ($value === 'hampir') {
                            $query->whereBetween('progress_percent', [61, 98]);
                        } elseif ($value === 'selesai') {
                            $query->where('progress_percent', '>=', 99);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
