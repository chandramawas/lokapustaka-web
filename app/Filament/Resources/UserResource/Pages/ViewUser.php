<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

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
            Actions\Action::make('ban')
                ->label('Ban')
                ->icon('heroicon-o-no-symbol')
                ->color('danger'),
            Actions\Action::make('reset_password')
                ->label('Reset Password')
                ->icon('heroicon-o-lock-closed')
                ->color('gray'),
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square'),
        ];
    }
}
