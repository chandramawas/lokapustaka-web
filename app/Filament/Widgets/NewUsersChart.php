<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class NewUsersChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected static ?string $heading = 'Pengguna Baru';
    protected static ?string $description = 'Statistik jumlah pengguna baru dalam 12 bulan terakhir';

    protected function getData(): array
    {
        $months = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date);
        }

        $labels = $months->map(fn($date) => $date->format('M y'));
        $data = $months->map(function ($date) {
            return User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        });
        return [
            'datasets' => [
                [
                    'label' => 'Pengguna Baru',
                    'data' => $data->toArray(),
                    'fill' => true,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
