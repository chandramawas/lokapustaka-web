<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Hash;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),

            Actions\Action::make('banned')
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
            Actions\Action::make('unbanned')
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

            Actions\Action::make('reset_password')
                ->label('Reset Password')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->requiresConfirmation()
                ->action(
                    function (User $record) {
                        $record->update([
                            'password' => Hash::make('Lokapustaka2025'),
                        ]);

                        return Notification::make()
                            ->title('Password berhasil direset')
                            ->success()
                            ->send();
                    }
                ),
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square'),
        ];
    }
}
