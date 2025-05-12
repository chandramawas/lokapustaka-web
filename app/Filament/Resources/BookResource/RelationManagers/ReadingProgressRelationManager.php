<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user.name')->label('Pengguna')->disabled(),
                TextInput::make('progress_percent')->label('Progress')->suffix('%')->disabled(),
                TextInput::make('cfi')->label('Lokasi CFI')->columnSpanFull()->disabled(),
                DateTimePicker::make('created_at')->label('Awal Baca')->disabled(),
                DateTimePicker::make('updated_at')->label('Terakhir Baca')->disabled(),
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
                    ->searchable(),
                TextColumn::make('progress_percent')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->state(fn($record) => $record->progress_percent >= 99 ? 'Selesai' : 'Belum Selesai')
                    ->colors([
                        'success' => 'Selesai',
                        'warning' => 'Belum Selesai',
                    ]),
                TextColumn::make('created_at')
                    ->label('Awal Baca')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Baca')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Baca')
                    ->options([
                        'selesai' => 'Selesai',
                        'belum' => 'Belum Selesai',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'selesai') {
                            $query->where('progress_percent', '>=', 99);
                        } elseif ($data['value'] === 'belum') {
                            $query->where('progress_percent', '<', 99);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
