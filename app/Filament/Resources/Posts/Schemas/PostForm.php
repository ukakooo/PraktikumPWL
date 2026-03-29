<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;

use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Group;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make("Post Details")
                    ->Description("Fill in the details of the post.")
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Group::make([
                            TextInput::make("title")
                                ->required()
                                ->rules(['min:3', 'max:100'])
                                ->validationMessages([
                                    'min' => 'The title must be at least 3 characters.',
                                    'max' => 'The title must be at most 100 characters.',
                                ]),
                            TextInput::make("slug")
                                ->unique()
                                ->rule('min:3')
                                ->validationMessages([
                                    'unique' => 'The slug must be unique.',
                                    'min' => 'The slug must be at least 3 characters.',
                                ]),
                            Select::make('category_id')
                                ->required()
                                ->relationship('category', 'name')
                                ->preload()
                                ->searchable(),
                            ColorPicker::make('color'),
                        ])->columns(2),

                        MarkdownEditor::make('content'),
                        // RichEditor::make('content'),
                    ])->columnSpan(2),

                Group::make([
                    // Section 2 make Images
                    Section::make("Image Upload")
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('image')
                                ->required()
                                ->disk("public")
                                ->directory("posts"),
                        ]),

                    // Section 3 meta
                    Section::make("Meta Information")
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            TagsInput::make("tags"),
                            Checkbox::make("published"),
                            DateTimePicker::make("published_at"),
                        ]),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
