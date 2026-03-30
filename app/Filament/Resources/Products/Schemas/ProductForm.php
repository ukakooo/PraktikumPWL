<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;

use Filament\Support\Icons\Heroicon;

use Filament\Actions\Action;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Wizard::make([
                    // Step 1
                    Step::make('Product Info')
                        ->description('Isi Informasi Produk')
                        ->schema([
                            Group::make([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('sku')
                                    ->required()
                                    ->unique(),
                            ])->columns(2),

                            MarkdownEditor::make('description')
                        ])
                        ->icon(Heroicon::DocumentText),

                    // Step 2
                    Step::make('Product Price and Stock')
                        ->description('Isi Harga Produk')
                        ->schema([
                            Group::make([
                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),
                                TextInput::make('stock')
                                    ->numeric()
                                    ->required(),
                            ])->columns(2),

                            MarkdownEditor::make('description')
                        ])
                        ->icon(Heroicon::CurrencyDollar),

                    // Step 3
                    Step::make('Media & Status')
                        ->description('Upload gambar dan atur status')
                        ->schema([
                            FileUpload::make('image')
                                ->disk('public')
                                ->directory('products'),
                            Checkbox::make('is_active'),
                            Checkbox::make('is_featured'),
                        ])
                        ->icon(Heroicon::Photo),
                ])
                ->columnSpanFull()
                ->submitAction(
                    Action::make('save')
                        ->label('Save Product')
                        ->button()
                        ->color('primary')
                        ->submit('save')
                ),
            ]);
    }
}
