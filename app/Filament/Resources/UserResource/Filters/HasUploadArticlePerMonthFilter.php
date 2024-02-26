<?php

use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class HasUploadArticlePerMonthFilter extends Filter
{

    public static function getDefaultName(): ?string
    {
        return 'has_upload_filter';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->form([
            Select::make('has_upload')
                ->options([
                    'DONE' => 'Done',
                    'UNDONE' => 'Undone'
                ])
        ]);
        $this->query(function (Builder $query, array $data): Builder {
            $dateS = Carbon::now()->startOfMonth();
            $dateE = Carbon::now()->endOfMonth();
            switch ($data['has_upload']) {
                case 'DONE':
                    $query->whereHas('articles', function (Builder $articleQuery) use ($dateE, $dateS) {
                        $articleQuery
                            ->whereBetween('created_at', [$dateS, $dateE]);
                    });
                    break;
                case 'UNDONE':
                    $query->whereDoesntHave('articles', function (Builder $articleQuery) use ($dateE, $dateS) {
                        $articleQuery
                            ->whereBetween('created_at', [$dateS, $dateE]);
                    });
            }

            return $query;
        });

        $this->indicateUsing(function ($data) {
            if (is_null($data['has_upload']) && $data['has_upload'] != 'DONE' && $data['has_upload'] != 'UNDONE') {
                return null;
            }

            return 'status upload : ' . $data['has_upload'];
        });
    }
}
