<?php

use App\Models\Article;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class MakePublicTableBulkAction extends BulkAction
{

    public static function getDefaultName(): ?string
    {
        return 'Make Public';
    }

    protected function setUp(): void
    {
        $this->icon('heroicon-s-eye')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (EloquentCollection $records) {
                // get array ids 
                $ids = $records->pluck('id')->toArray();
                // update is_public to true
                Article::whereIn('id', $ids)->update(['is_public' => true]);
            })
            ->disabled(function (EloquentCollection|null $records) {
                return is_null($records) ? [] : ($records->filter(fn (Article $article) => $article->is_public)->isEmpty());
            });
    }
}
