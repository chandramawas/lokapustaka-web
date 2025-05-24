<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use Mokhosh\FilamentRating\RatingTheme;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $activeNavigationIcon = 'heroicon-s-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpan(fn($livewire) => $livewire instanceof EditRecord ? 'full' : null),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->maxLength(255)
                    ->revealable()
                    ->visibleOn('create')
                    ->required(),

                Forms\Components\Toggle::make('email_verified')
                    ->label('Verifikasi Email')
                    ->default(function ($record) {
                        return !empty($record?->email_verified_at);
                    })
                    ->afterStateHydrated(function ($component, $state, $record) {
                        $component->state(!is_null($record?->email_verified_at));
                    })
                    ->dehydrated()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Nama')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ])
                    ->default('user')
                    ->required(),

                Forms\Components\Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-Laki' => 'Laki-Laki',
                        'Perempuan' => 'Perempuan',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->nullable(),

                Forms\Components\DatePicker::make('birthdate')
                    ->label('Tanggal Lahir')
                    ->placeholder('Pilih tanggal lahir')
                    ->nullable(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->color(fn($record) => $record->is_banned ? 'danger' : ($record->role === 'admin' ? 'secondary' : null))
                    ->tooltip(fn($record) => $record->is_banned ? 'Banned' : ($record->role === 'admin' ? 'Admin' : false))
                    ->icon(fn($record) => $record->role === 'admin' ? 'heroicon-m-building-library' : null)
                    ->iconPosition(IconPosition::After)
                    ->iconColor(fn($record) => $record->is_banned ? 'danger' : ($record->role === 'admin' ? 'secondary' : null))
                    ->weight(FontWeight::SemiBold)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verif')
                    ->alignCenter()
                    ->toggleable()
                    ->getStateUsing(fn($record) => !is_null($record->email_verified_at))
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->tooltip(fn($state) => $state ? 'Email sudah diverifikasi' : 'Email belum diverifikasi'),
                Tables\Columns\TextColumn::make('age')
                    ->label('Umur')
                    ->tooltip(fn($record) => $record->birthdate?->format('d M Y') ?? false)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reading_progress_count')
                    ->label('Jumlah Baca')
                    ->counts('readingProgress')
                    ->suffix(' buku')
                    ->tooltip(fn($record) => $record->readingProgress->count() !== 0 ? $record->readingProgress->where('progress_percent', '>=', 99)->count() . ' selesai' : false)
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('reading_progress_avg_progress_percent')
                    ->label('Rata-Rata Baca')
                    ->avg('readingProgress', 'progress_percent')
                    ->numeric(maxDecimalPlaces: 1)
                    ->default(0)
                    ->toggleable()
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state <= 20,
                        'warning' => fn($state) => $state > 20 && $state <= 60,
                        'info' => fn($state) => $state > 60 && $state < 99,
                        'success' => fn($state) => $state >= 99,
                    ])
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_subscribed')
                    ->label('Langganan')
                    ->state(fn($record) => $record->is_subscribed === true ? 'Aktif' : 'Tidak Aktif')
                    ->badge()
                    ->color(fn($record) => $record->is_subscribed === true ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('active_subscription.end_date')
                    ->label('Langganan Berakhir')
                    ->state(fn($record) => $record->activeSubscription()->end_date ?? null)
                    ->placeholder('-')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->defaultSort('reading_progress_count', 'desc')
            ->filters([
                // Role Filter
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ]),

                // Email belum diverifikasi
                Tables\Filters\TernaryFilter::make('email_verified')
                    ->label('Verifikasi Email')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Verifikasi')
                    ->falseLabel('Belum Verifikasi')
                    ->queries(
                        true: fn($query) => $query->whereNotNull('email_verified_at'),
                        false: fn($query) => $query->whereNull('email_verified_at'),
                    ),

                // Status Langganan
                Tables\Filters\TernaryFilter::make('is_subscribed')
                    ->label('Langganan')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->queries(
                        true: fn($query) => $query->whereHas('subscriptions', fn($q) => $q->where('is_active', true)),
                        false: fn($query) => $query->whereDoesntHave('subscriptions', fn($q) => $q->where('is_active', true))
                    ),

                // Jenis Kelamin
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-Laki' => 'Laki-Laki',
                        'Perempuan' => 'Perempuan',
                        'Lainnya' => 'Lainnya',
                    ]),

                //Kelompok Umur
                Tables\Filters\SelectFilter::make('age_group')
                    ->label('Kelompok Umur')
                    ->placeholder('Semua')
                    ->options([
                        'under_18' => 'Di bawah 18 tahun',
                        '18_25' => '18 - 25 tahun',
                        '26_35' => '26 - 35 tahun',
                        '36_50' => '36 - 50 tahun',
                        'above_50' => 'Di atas 50 tahun',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value']) {
                            'under_18' => $query->whereDate('birthdate', '>', now()->subYears(18)),
                            '18_25' => $query->whereBetween('birthdate', [
                                now()->subYears(25),
                                now()->subYears(18),
                            ]),
                            '26_35' => $query->whereBetween('birthdate', [
                                now()->subYears(35),
                                now()->subYears(26),
                            ]),
                            '36_50' => $query->whereBetween('birthdate', [
                                now()->subYears(50),
                                now()->subYears(36),
                            ]),
                            'above_50' => $query->whereDate('birthdate', '<=', now()->subYears(50)),
                            default => $query,
                        };
                    }),

                //Banned
                Tables\Filters\TernaryFilter::make('is_banned')
                    ->label('User Banned')
                    ->placeholder('Semua')
                    ->trueLabel('Dibanned')
                    ->falseLabel('Tidak Dibanned')
                    ->default(0),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('banned')
                        ->label('Banned')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(
                            function (User $record) {
                                $record->update([
                                    'is_banned' => true,
                                ]);

                                return Notification::make()
                                    ->title('Pengguna ' . $record->name . ' berhasil dibanned')
                                    ->success()
                                    ->send();
                            }
                        )
                        ->visible(fn($record) => !$record->is_banned),
                    Tables\Actions\Action::make('unbanned')
                        ->label('Unbanned')
                        ->icon('heroicon-o-lock-open')
                        ->color('tertiary')
                        ->requiresConfirmation()
                        ->action(
                            function (User $record) {
                                $record->update([
                                    'is_banned' => false,
                                ]);

                                return Notification::make()
                                    ->title('Pengguna ' . $record->name . ' berhasil diaktifkan kembali')
                                    ->success()
                                    ->send();
                            }
                        )
                        ->visible(fn($record) => $record->is_banned),
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
//                View Bagian 1 - Detail
                Section::make('Detail Pengguna')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama')
                            ->color(fn($record) => $record->is_banned ? 'danger' : ($record->role === 'admin' ? 'secondary' : null))
                            ->tooltip(fn($record) => $record->is_banned ? 'Banned' : ($record->role === 'admin' ? 'Admin' : false))
                            ->icon(fn($record) => $record->role === 'admin' ? 'heroicon-m-building-library' : null)
                            ->iconPosition(IconPosition::After)
                            ->iconColor(fn($record) => $record->is_banned ? 'danger' : ($record->role === 'admin' ? 'secondary' : null)),
                        TextEntry::make('is_subscribed')
                            ->label('Status Langganan')
                            ->formatStateUsing(fn(bool $state) => $state ? 'Aktif' : 'Tidak Aktif')
                            ->badge()
                            ->color(fn(bool $state) => $state ? 'primary' : 'gray'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->icon(fn($record) => $record->email_verified_at !== null ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->iconColor(fn($record) => $record->email_verified_at !== null ? 'success' : 'danger'),
                        TextEntry::make('email_verified_at')
                            ->label('Tanggal Verifikasi Email')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('gender')
                            ->label('Jenis Kelamin')
                            ->placeholder('-'),
                        TextEntry::make('birthdate_age')
                            ->label('Tanggal Lahir')
                            ->state(fn($record) => $record->birthdate !== null ? $record->birthdate->format('d F Y') . ' - ' . $record->age . ' tahun' : null)
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Tanggal Didaftar')
                            ->dateTime()
                            ->sinceTooltip(),
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime()
                            ->sinceTooltip(),
                    ])
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('user_detail')
                    ->columns(2),

//                View Bagian 2 - Langganan Terkini / Terakhir
                Section::make('Langganan Terkini')
                    ->schema([
                        TextEntry::make('subscription_type')
                            ->label('Paket')
                            ->state(function ($record) {
                                $latestSubscription = $record->subscriptions->sortByDesc('created_at')->first();
                                return ucfirst($latestSubscription->type);
                            }),
                        TextEntry::make('subscription_status')
                            ->label('Status')
                            ->state(function ($record) {
                                $latestSubscription = $record->subscriptions->sortByDesc('created_at')->first();
                                return $latestSubscription->is_active ? 'Aktif' : 'Tidak Aktif';
                            })
                            ->badge(),
                        TextEntry::make('subscription_start')
                            ->label('Tanggal Mulai')
                            ->state(function ($record) {
                                $latestSubscription = $record->subscriptions->sortByDesc('created_at')->first();
                                return $latestSubscription->start_date;
                            })
                            ->dateTime(),
                        TextEntry::make('subscription_end')
                            ->label('Tanggal Berakhir')
                            ->state(function ($record) {
                                $latestSubscription = $record->subscriptions->sortByDesc('created_at')->first();
                                return $latestSubscription->end_date;
                            })
                            ->dateTime(),
                    ])
                    ->icon('heroicon-o-identification')
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('user_subscription')
                    ->visible(fn($record) => $record->subscriptions->count() > 0)
                    ->columns(4),

//                 View Bagian 3 - Statistik
                Section::make('Statistik')
                    ->schema([
                        Fieldset::make('Baca')
                            ->schema([
                                TextEntry::make('user_reading_count')
                                    ->label('Total')
                                    ->state(fn($record) => $record->readingProgress()->count())
                                    ->suffix(' buku'),
                                TextEntry::make('user_reading_completed_count')
                                    ->label('Selesai')
                                    ->state(fn($record) => $record->readingProgress()->where('progress_percent', '>=', 99)->count())
                                    ->suffix(' buku'),
                                TextEntry::make('book_avg_reading_progress')
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
                                TextEntry::make('user_reviews_count')
                                    ->label('Total')
                                    ->state(fn($record) => $record->reviews()->count())
                                    ->suffix(' ulasan'),
                                TextEntry::make('user_reviews_with_text_count')
                                    ->label('Dengan Text')
                                    ->state(fn($record) => $record->reviews()->where('review', '!=', null)->count())
                                    ->suffix(' ulasan'),
                                TextEntry::make('user_reviews_rating_count')
                                    ->label('Jumlah Bintang')
                                    ->state(fn($record) => $record->reviews()->sum('rating'))
                                    ->prefix('★ '),
                                RatingEntry::make('user_avg_rating')
                                    ->label('Rata-Rata Rating')
                                    ->tooltip(fn($state) => '★ ' . number_format($state, 1))
                                    ->state(fn($record) => $record->reviews()->avg('rating'))
                                    ->size('sm')
                                    ->theme(RatingTheme::HalfStars),
                            ])
                            ->columns(4),
                        Fieldset::make('Langganan')
                            ->schema([
                                TextEntry::make('longest_subscription')
                                    ->label('Jangka Waktu Terlama')
                                    ->state(function ($record) {
                                        $longest = $record->subscriptions->sortByDesc('span')->first();

                                        if (!$longest) {
                                            return 'Tidak ada data langganan';
                                        }

                                        return "ID #{$longest->id} – {$longest->span} hari";
                                    }),
                                TextEntry::make('total_payment')
                                    ->label('Total Pembayaran')
                                    ->state(fn($record) => $record->payments->where('paid_at', '!=', null)->sum('amount'))
                                    ->money()
                                    ->badge()
                            ])
                            ->visible(fn($record) => $record->subscriptions->count() > 0)
                            ->columns(4),
                    ])
                    ->icon('heroicon-o-chart-bar')
                    ->collapsed()
                    ->collapsible()
                    ->persistCollapsed()
                    ->id('user_statistics'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SubscriptionsRelationManager::class,
            RelationManagers\ReadingProgressRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
            RelationManagers\SavedBooksRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
