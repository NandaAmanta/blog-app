<?php

use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class IsActiveFilter extends Filter
{

    protected function setup(): void
    {
        parent::setUp();
        $this->form([
            Select::make('email_verified')
                ->options([
                    '1' => 'Verified',
                    '0' => 'Not Verified',
                ])
                ->nullable()
        ])->query(function (Builder $query, array $data): Builder {
            if ($data['email_verified'] === '1') {
                $query->whereNotNull('email_verified_at');
            } elseif ($data['email_verified'] === '0') {
                $query->whereNull('email_verified_at');
            }
            return $query;
        });
    }
}
