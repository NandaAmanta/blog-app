<?php

use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CreatedByMeTableFilter extends Filter
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->form([
            Select::make('created_by_me')->options([
                '1' => 'yes',
                '0' => 'all',
            ])
            ->default('0')
        ]);
        $this->query(function (Builder $query, array $data): Builder {
            if ($data['created_by_me'] === '1') {
                $userId = auth()->user()->id;
                $query->where('creator_id', $userId);
            }
            return $query;
        });
    }
}
