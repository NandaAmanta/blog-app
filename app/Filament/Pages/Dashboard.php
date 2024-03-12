<?php

namespace App\Filament\Pages;

use App\Const\Action;
use App\Const\Module;
use App\Filament\Resources\ArticleResource\Widgets\ArticleOverview;
use App\Filament\Resources\UserResource\Widgets\UserOverview;
use App\Models\User;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public static function getWidgets(): array
    {
        $widgets = [];
        $user = auth()->user();
        if ($user->hasPermissionTo(Action::READ . '.' . Module::USER)) {
            $widgets[] = UserOverview::class;
        }

        if ($user->hasPermissionTo(Action::READ . '.' . Module::ARTICLE)) {
            $widgets[] = ArticleOverview::class;
        }

        return $widgets;
    }

    protected function getHeaderWidgets(): array
    {
        return $this::getWidgets();
    }
}
