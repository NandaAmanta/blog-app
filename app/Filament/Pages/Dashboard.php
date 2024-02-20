<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ArticleResource\Widgets\ArticleOverview;
use App\Filament\Resources\UserResource\Widgets\UserOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public static function getWidgets(): array
    {
        return [
            ArticleOverview::class,
            UserOverview::class,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ArticleOverview::class,
            UserOverview::class,
        ];
    }
}
