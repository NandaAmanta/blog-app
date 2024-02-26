<?php


namespace App\Filament\Resources\ArticleResource\Actions;

use App\Models\Article;
use Filament\Actions\Action;

class MakePublicAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return 'public';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Make Public');

        $this->color('success');

        $this->requiresConfirmation();

        $this->action(function (Article $record): void {
            $record->update(['is_public' => true]);
        });

        $this->hidden(fn (Article $record) => $record->is_public);
    }
}
