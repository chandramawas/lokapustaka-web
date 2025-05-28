<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Pembayaran';
    protected static ?string $label = 'Pembayaran';
    protected static ?string $icon = 'heroicon-o-banknotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->label('Order ID')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('subscription_id')
                    ->label('SUB_ID')
                    ->relationship('subscription', 'id', fn($query, $record) => $query->where('user_id', $record->user_id))
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah Pembayaran')
                    ->prefix('Rp')
                    ->suffix(',00')
                    ->step(1000)
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'manual' => 'Manual',
                        'qris' => 'QRIS',
                        'bank_transfer' => 'Virtual Account',
                        'cstore' => 'Alfamart',
                        'unknown' => 'Unknown',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Metode Pembayaran')
                    ->options([
                        'completed' => 'Berhasil',
                        'pending' => 'Pending',
                        'failed' => 'Gagal',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subscription_id')
                    ->label('SUB_ID')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => 'SUB_' . $state),
                Tables\Columns\TextColumn::make('subscription.type')
                    ->label('Paket')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money(),
                Tables\Columns\TextColumn::make('method')
                    ->label('Metode')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'bank_transfer' => 'VA',
                        'cstore' => 'Alfa',
                        'qris' => 'QRIS',
                        'manual' => 'Manual',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Pending',
                        'completed' => 'Berhasil',
                        'failed' => 'Gagal',
                    })
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('method')
                    ->label('Metode Pembayaran')
                    ->native(false)
                    ->options([
                        'bank_transfer' => 'VA',
                        'cstore' => 'Alfamart',
                        'qris' => 'QRIS',
                        'manual' => 'Manual',
                        'unknown' => 'Lainnya',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            return $query->where('method', $data['value']);
                        }
                        return $query;
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->native(false)
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Berhasil',
                        'failed' => 'Gagal',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            return $query->where('status', $data['value']);
                        }
                        return $query;
                    }),
                Tables\Filters\SelectFilter::make('subscription.type')
                    ->label('Paket Langganan')
                    ->native(false)
                    ->options([
                        'bulanan' => 'Bulanan',
                        'tahunan' => 'Tahunan',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            return $query->whereHas('subscription', function ($query) use ($data) {
                                $query->where('type', $data['value']);
                            });
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // View Bagian 1 - Pengguna
                Section::make('Pengguna')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama')
                            ->color(fn($record) => $record->user->is_banned ? 'danger' : ($record->user->role === 'admin' ? 'secondary' : null))
                            ->tooltip(fn($record) => $record->user->is_banned ? 'Banned' : ($record->user->role === 'admin' ? 'Admin' : false))
                            ->icon(fn($record) => $record->user->role === 'admin' ? 'heroicon-m-building-library' : null)
                            ->iconPosition(IconPosition::After)
                            ->iconColor(fn($record) => $record->user->is_banned ? 'danger' : ($record->user->role === 'admin' ? 'secondary' : null)),
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
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),

                //                View Bagian 2 - Subscriptionn
                Section::make('Langganan')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID')
                            ->placeholder('-')
                            ->state(fn($record) => $record->subscription_id),
                        TextEntry::make('type')
                            ->label('Paket Langganan')
                            ->state(fn($record) => ucfirst($record->subscription->type)),
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->state(fn($record) => $record->subscription->is_active)
                            ->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Tidak Aktif')
                            ->colors([
                                'primary' => fn($state) => $state,
                                'gray' => fn($state) => !$state,
                            ]),
                        TextEntry::make('span')
                            ->label('Total Durasi')
                            ->state(fn($record) => $record->subscription->span)
                            ->suffix(' hari'),
                        TextEntry::make('start_date')
                            ->label('Tanggal Mulai')
                            ->state(fn($record) => $record->subscription->start_date)
                            ->date()
                            ->sinceTooltip(),
                        TextEntry::make('end_date')
                            ->label('Tanggal Berakhir')
                            ->state(fn($record) => $record->subscription->end_date)
                            ->date()
                            ->sinceTooltip(),
                        TextEntry::make('updated_at')
                            ->label('Tanggal Diperbarui')
                            ->state(fn($record) => $record->subscription->updated_at)
                            ->dateTime()
                            ->sinceTooltip(),
                    ])
                    ->columns(4)
                    ->visible(fn($record) => $record->subscription)
                    ->collapsed()
                    ->collapsible(),

                TextEntry::make('order_id')
                    ->label('Order ID')
                    ->badge()
                    ->color('gray')
                    ->url(fn($record) => 'https://dashboard.sandbox.midtrans.com/beta/transactions?type=order_id&query=' . $record->order_id)
                    ->openUrlInNewTab(),
                TextEntry::make('amount')
                    ->label('Jumlah Pembayaran')
                    ->money(),
                TextEntry::make('method')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'bank_transfer' => 'Virtual Account',
                        'cstore' => 'Alfamart',
                        'qris' => 'QRIS',
                        'manual' => 'Manual',
                        default => ucfirst($state),
                    }),
                TextEntry::make('status')
                    ->label('Status Pembayaran')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Pending',
                        'completed' => 'Berhasil',
                        'failed' => 'Gagal',
                    })
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                TextEntry::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->placeholder('-')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label('Data Dibuat')
                    ->dateTime()
                    ->sinceTooltip(),
            ]);
    }
}
