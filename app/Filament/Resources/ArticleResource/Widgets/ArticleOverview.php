<?php

namespace App\Filament\Resources\ArticleResource\Widgets;

use App\Models\Article;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ArticleOverview extends ChartWidget
{
    protected static ?string $heading = 'Total Articles per Month'; 

    protected function getData(): array
    {
        $data = Trend::model(Article::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
        
        return [
            'datasets' => [
                [
                    'label' => 'Articles',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    
}
