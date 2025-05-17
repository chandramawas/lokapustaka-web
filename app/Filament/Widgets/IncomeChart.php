<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class IncomeChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Pendapatan';
    protected static ?string $description = 'Statistik pendapatan dalam 12 bulan terakhir';

    protected function getData(): array
    {
        $months = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date);
        }

        $labels = $months->map(fn($date) => $date->format('M y'));
        $data = $months->map(function ($date) {
            return Payment::whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('amount');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Ribu Rupiah)',
                    'data' => $data->map(fn($amount) => $amount / 1000)->toArray(),
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
