<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\Widgets\ArticleOverview;
use App\Models\Article;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-s-pencil';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name',  fn (Builder $query) => $query->where('is_active', true))
                    ->preload()
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_public')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('creator.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('Visibility')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_public')
                    ->options([
                        '1' => 'Public',
                        '0' => 'Private',
                    ])
                    ->label('Visibility')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function (Tables\Actions\DeleteAction $action, Article $record) {
                    $record->categories()->detach();
                }),
                Action::make('public')
                    ->name('Public')
                    ->icon('heroicon-s-eye')
                    ->color('success')
                    ->hidden(fn (Article $record) => $record->is_public)
                    ->requiresConfirmation()
                    ->action(fn (Article $record) => $record->update(['is_public' => true])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->before(function (Tables\Actions\DeleteBulkAction $action, array $records) {
                        foreach ($records as $record) {
                            $record->categories()->detach();
                        }
                    }),
                    BulkAction::make('bulk-public')
                        ->name('Make Public')
                        ->icon('heroicon-s-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (EloquentCollection $records) {
                            // get array ids 
                            $ids = $records->pluck('id')->toArray();
                            // update is_public to true
                            Article::whereIn('id', $ids)->update(['is_public' => true]);
                        }),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
            'view' => Pages\ViewArticle::route('/{record}'),
        ];
    }
}
