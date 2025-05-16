<?php

namespace App\Filament\Widgets;

use Kenepa\MultiWidget\MultiWidget;

class AdminMultiWidget extends MultiWidget
{
    public array $widgets = [
        StatsOverview::class,
    ];
}
