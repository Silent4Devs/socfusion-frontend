<?php

namespace App\Livewire\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Usuarios Registrados', '1,234')
                ->description('↑ 5% respecto a la semana pasada')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-user-group')
                ->chart([100, 120, 130, 125, 140, 150, 160]),

            Card::make('Alertas Activas', '87')
                ->description('↓ 12% respecto a ayer')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle')
                ->chart([90, 85, 80, 78, 75, 70, 68]),

            Card::make('Tiempo Promedio en la Página', '3:12')
                ->description('↑ 3% respecto al mes pasado')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->icon('heroicon-o-clock')
                ->chart([180, 185, 190, 195, 200, 205, 210]),
        ];
    }
}
