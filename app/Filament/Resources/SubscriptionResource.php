<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationGroup = 'Aktivitas Pengguna';
    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Langganan';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $activeNavigationIcon = 'heroicon-s-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Paket')
                    ->options([
                        'bulanan' => 'Bulanan',
                        'tahunan' => 'Tahunan',
                    ])
                    ->native(false)
                    ->default('bulanan')
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => static::setEndDate($state, $set))
                    ->required(),

                Forms\Components\DateTimePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->default(now())
                    ->readOnly()
                    ->helperText('Langganan berlaku mulai dari tanggal ini.')
                    ->seconds(false)
                    ->required(),

                Forms\Components\DateTimePicker::make('end_date')
                    ->label('Tanggal Berakhir')
                    ->readOnly()
                    ->default(now()->addMonth())
                    ->helperText('Otomatis diperbarui setiap perubahan paket langganan.')
                    ->seconds(false)
                    ->required(),
            ]);
    }

    protected static function setEndDate($type, callable $set): void
    {
        if (!$type) return;

        $endDate = $type === 'bulanan'
            ? now()->addMonth()
            : now()->addYear();

        $set('end_date', $endDate->format('Y-m-d H:i'));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->limit(30)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Paket')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Tidak Aktif')
                    ->badge()
                    ->colors([
                        'primary' => fn($state) => $state,
                        'gray' => fn($state) => !$state,
                    ]),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->dateTime()
                    ->sinceTooltip()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Berakhir')
                    ->dateTime()
                    ->sinceTooltip()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->sinceTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Paket Langganan')
                    ->options([
                        'bulanan' => 'Bulanan',
                        'tahunan' => 'Tahunan',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Langganan')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('extend')
                        ->label(fn($record) => $record->type === 'bulanan' ? 'Perpanjang 1 Bulan' : 'Perpanjang 1 Tahun')
                        ->icon('heroicon-o-arrow-path')
                        ->visible(fn($record) => $record->is_active)
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $type = $record->type;
                            $endDate = $record->end_date instanceof \Carbon\Carbon
                                ? $record->end_date
                                : \Carbon\Carbon::parse($record->end_date);

                            $newEndDate = match ($type) {
                                'bulanan' => $endDate->copy()->addMonth(),
                                'tahunan' => $endDate->copy()->addYear(),
                                default => throw new \Exception('Paket langganan tidak valid.')
                            };

                            $record->update(['end_date' => $newEndDate->format('Y-m-d H:i:s')]);

                            return Notification::make()
                                ->title('Berhasil memperpanjang langganan')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('set_inactive')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn($record) => $record->is_active)
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['is_active' => false]);
                            return Notification::make()->title('Berhasil menonaktifkan langganan')->success()->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // View Bagian 1 - Pengguna
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
                    ->headerActions([
                        Action::make('user_view')
                            ->label('Lihat')
                            ->icon('heroicon-o-eye')
                            ->color('gray')
                            ->url(fn($record) => route('filament.admin.resources.users.view', $record->user)),
                    ])
                    ->columns(3)
                    ->collapsible(),

//                View Bagian 2 - Payment
                Section::make('Pembayaran')
                    ->schema([
                        TextEntry::make('payments.count')
                            ->label('Jumlah Pembayaran')
                            ->state(fn($record) => $record->payments()->count())
                            ->suffix(' pembayaran'),

                        TextEntry::make('amount')
                            ->label('Total Pembayaran')
                            ->state(fn($record) => $record->payments()->sum('amount'))
                            ->money(),
                    ])
                    ->visible(fn($record) => $record->payments()->count() > 0)
                    ->columns(2)
                    ->collapsible(),

                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('type')
                    ->label('Paket Langganan')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                TextEntry::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Tidak Aktif')
                    ->colors([
                        'primary' => fn($state) => $state,
                        'gray' => fn($state) => !$state,
                    ]),
                TextEntry::make('start_date')
                    ->label('Tanggal Mulai')
                    ->dateTime()
                    ->sinceTooltip(),
                TextEntry::make('end_date')
                    ->label('Tanggal Berakhir')
                    ->dateTime()
                    ->sinceTooltip(),
                TextEntry::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime()
                    ->sinceTooltip(),
            ])
            ->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSubscriptions::route('/'),
        ];
    }
}
