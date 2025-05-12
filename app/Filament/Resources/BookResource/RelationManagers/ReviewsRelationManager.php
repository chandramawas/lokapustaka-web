<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Ulasan';
    protected static ?string $label = 'Ulasan';
    protected static ?string $icon = 'heroicon-o-chat-bubble-bottom-center-text';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Pengguna')
                    ->placeholder('Pilih pengguna...')
                    ->relationship('user', 'name')
                    ->options(function ($livewire) {
                        $book = $livewire->ownerRecord;

                        return \App\Models\User::whereDoesntHave('reviews', function ($query) use ($book) {
                            $query->where('book_id', $book->id);
                        })->pluck('name', 'id');
                    })
                    ->disabled(fn($context) => $context === 'edit')
                    ->default(fn($livewire) => $livewire->record->user_id ?? null)
                    ->searchable()
                    ->preload()
                    ->required(),

                Rating::make('rating')
                    ->label('Rating')
                    ->default(5)
                    ->required(),

                Textarea::make('review')
                    ->label('Ulasan')
                    ->maxLength(1000)
                    ->placeholder('-')
                    ->autosize()
                    ->nullable()
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
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                TextColumn::make('review')
                    ->label('Ulasan')
                    ->placeholder('-')
                    ->limit(200)
                    ->wrap(),
                RatingColumn::make('rating')
                    ->label('Rating')
                    ->size('sm')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-m-plus'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
