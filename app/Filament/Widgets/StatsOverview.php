<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Payment;
use App\Models\ReadingProgress;
use App\Models\Subscription;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Statistik Overview';

    protected ?string $description = 'Ringkasan informasi statistik aktivitas dalam bulan ini.';

    protected function getStats(): array
    {
        return [
            $this->bookStat(),
            $this->userStat(),
            $this->activeSubscriptionStat(),
            $this->readingStat(),
            $this->newUserStat(),
            $this->incomeStat(),
        ];
    }

    private function bookStat(): Stat
    {
        $totalBooks = Book::count();
        $newBooksThisMonth = Book::where('created_at', '>=', now()->subMonth())->count();

        $trend = $this->getTrend('buku', $newBooksThisMonth);

        return Stat::make('Buku', $totalBooks)
            ->description($trend['description'])
            ->descriptionIcon($trend['icon'])
            ->color($trend['color']);
    }

    private function readingStat(): Stat
    {
        $thisMonth = ReadingProgress::where('updated_at', '>=', now()->subMonth())->count();
        $lastMonth = ReadingProgress::whereBetween('updated_at', [now()->subMonths(2), now()->subMonth()])->count();

        $trend = $this->getTrend('aktivitas baca', $thisMonth, $lastMonth);

        return Stat::make('Baca Bulan Ini', $thisMonth)
            ->description($trend['description'])
            ->descriptionIcon($trend['icon'])
            ->color($trend['color']);
    }

    private function userStat(): Stat
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->subMonth())->count();

        $trend = $this->getTrend('pengguna', $newUsersThisMonth);

        return Stat::make('Pengguna', $totalUsers)
            ->description($trend['description'])
            ->descriptionIcon($trend['icon'])
            ->color($trend['color']);
    }

    private function newUserStat(): Stat
    {
        $newUsersThisMonth = User::where('created_at', '>=', now()->subMonth())->count();
        $newUsersLastMonth = User::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        $trend = $this->getTrend('pengguna', $newUsersThisMonth, $newUsersLastMonth);
        return Stat::make('Pengguna Baru Bulan Ini', $newUsersThisMonth)
            ->description($trend['description'])
            ->descriptionIcon($trend['icon'])
            ->color($trend['color']);
    }

    private function activeSubscriptionStat(): Stat
    {
        $activeSubscription = Subscription::where('is_active', true)->count();
        $totalUsers = User::count();
        $percentage = $totalUsers > 0 ? number_format(($activeSubscription / $totalUsers) * 100, 1) : 0;

        $color = match (true) {
            $percentage <= 20 => 'danger',
            $percentage <= 50 => 'warning',
            $percentage <= 100 => 'success',
            default => 'gray'
        };

        return Stat::make('Langganan Aktif', $activeSubscription)
            ->description("{$percentage}% dari pengguna")
            ->color($color);
    }

    private function incomeStat(): Stat
    {
        $incomeThisMonth = Payment::where('paid_at', '>=', now()->subMonth())->sum('amount');
        $incomeLastMonth = Payment::whereBetween('paid_at', [now()->subMonths(2), now()->subMonth()])->sum('amount');

        $formattedIncome = 'Rp ' . number_format($incomeThisMonth, 0, ',', '.');

        $trend = $this->getTrend('pendapatan', $incomeThisMonth, $incomeLastMonth);
        return Stat::make('Pendapatan Bulan Ini', $formattedIncome)
            ->description($trend['description'])
            ->descriptionIcon($trend['icon'])
            ->color($trend['color']);
    }

    private function getTrend(string $label, int $current, int $previous = null): array
    {
        $attribute = [
            'up' => ['icon' => 'heroicon-m-arrow-trending-up', 'color' => 'success'],
            'down' => ['icon' => 'heroicon-m-arrow-trending-down', 'color' => 'danger'],
            'stay' => ['icon' => 'heroicon-m-minus', 'color' => 'gray'],
            'new' => ['icon' => 'heroicon-m-arrow-up', 'color' => 'success'],
        ];

        if ($previous === null) {
            if ($current > 0) {
                return [
                    'icon' => $attribute['new']['icon'],
                    'color' => $attribute['new']['color'],
                    'description' => "{$current} {$label} baru bulan ini.",
                ];
            } else {
                return [
                    'icon' => $attribute['stay']['icon'],
                    'color' => $attribute['stay']['color'],
                    'description' => "Tidak ada {$label} baru bulan ini.",
                ];
            }
        }

        if ($current > $previous) {
            $percentage = $previous > 0 ? number_format((($current - $previous) / $previous) * 100, 1) : 100;
            return [
                'icon' => $attribute['up']['icon'],
                'color' => $attribute['up']['color'],
                'description' => "Meningkat {$percentage}%",
            ];
        } elseif ($current < $previous) {
            $percentage = $previous > 0 ? number_format((($previous - $current) / $previous) * 100, 1) : 0;
            return [
                'icon' => $attribute['down']['icon'],
                'color' => $attribute['down']['color'],
                'description' => "Menurun {$percentage}%",
            ];
        } elseif ($current === 0) {
            return [
                'icon' => $attribute['stay']['icon'],
                'color' => $attribute['stay']['color'],
                'description' => "Tidak ada $label bulan ini.",
            ];
        } else {
            return [
                'icon' => $attribute['stay']['icon'],
                'color' => $attribute['stay']['color'],
                'description' => "Sama seperti bulan lalu",
            ];
        }
    }
}
