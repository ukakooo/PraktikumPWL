<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                ColorColumn::make('color')
                    ->label('Color')
                    ->toggleable(),

                ImageColumn::make('image')
                    ->disk('public')
                    ->toggleable(),

                TextColumn::make('tags')
                    ->label('Tags')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                IconColumn::make('published')
                    ->label('Published Status')
                    ->boolean()
                    ->toggleable(),
                    
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])->defaultSort('created_at', 'desc')

            ->filters([
                //
                Filter::make('created_at')
                    ->label('Creation Date')
                    ->schema([
                        DatePicker::make('created_at')
                            ->label('Select Date: '),
                    ])
                    ->query(function ($query, $data) {
                        return $query
                            ->when(
                                $data['created_at'],
                                fn($query, $date) => $query->whereDate('created_at', $date)
                            );
                    }),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->preload(),
            ])
            ->recordActions([
                ReplicateAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('status')
                    ->label('Status Change')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        Checkbox::make('published')
                            ->default(fn($record): bool => $record->published)
                            ->required()
                    ])
                    ->action(function ($record, $data) {
                        $record->update(['published' => $data['published']]);
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
