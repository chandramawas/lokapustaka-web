<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationGroup = 'Aktivitas Pengguna';
    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Pembayaran';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $activeNavigationIcon = 'heroicon-s-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->label('ID')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('subscription_id')
                    ->label('SUB_ID')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->disabled()
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
                    ])
                    ->default('manual')
                    ->native(false)
                    ->required(),
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subscription_id')
                    ->label('SUB_ID')
                    ->formatStateUsing(fn($state) => 'SUB_' . $state)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->limit(30)
                    ->wrap()
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                Tables\Columns\TextColumn::make('subscription.type')
                    ->label('Paket')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money(),
                Tables\Columns\TextColumn::make('method')
                    ->label('Metode')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'manual' => 'Manual',
                        'qris' => 'QRIS',
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
                        'manual' => 'Manual',
                        'qris' => 'QRIS',
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
                    ->columns(3)
                    ->headerActions([
                        \Filament\Infolists\Components\Actions\Action::make('user_view')
                            ->label('Lihat')
                            ->icon('heroicon-o-eye')
                            ->color('gray')
                            ->url(fn($record) => route('filament.admin.resources.users.view', $record->user)),
                    ])
                    ->collapsed()
                    ->collapsible(),

//                View Bagian 2 - Subscriptionn
                Section::make('Info Langganan')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID')
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
                        TextEntry::make('start_date')
                            ->label('Tanggal Mulai')
                            ->state(fn($record) => $record->subscription->start_date)
                            ->dateTime()
                            ->sinceTooltip(),
                        TextEntry::make('end_date')
                            ->label('Tanggal Berakhir')
                            ->state(fn($record) => $record->subscription->end_date)
                            ->dateTime()
                            ->sinceTooltip(),
                        TextEntry::make('updated_at')
                            ->label('Tanggal Diperbarui')
                            ->state(fn($record) => $record->subscription->updated_at)
                            ->dateTime()
                            ->sinceTooltip(),
                    ])
                    ->columns(3)
                    ->collapsed()
                    ->collapsible(),

                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('amount')
                    ->label('Jumlah Pembayaran')
                    ->money(),
                TextEntry::make('method')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'manual' => 'Manual',
                        'qris' => 'QRIS',
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
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label('Data Dibuat')
                    ->dateTime()
                    ->sinceTooltip(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePayments::route('/'),
        ];
    }
}
