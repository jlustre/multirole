<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Post')
            // ->description('This is the description')
            ->schema([
                TextInput::make('title')->required()->columnSpan(2),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->columnSpan(2),
                ColorPicker::make('color')->required(), 
               
                MarkdownEditor::make('content')->required()->columnSpan(2),
            ])->columnSpan(2)->columns(2),
            Group::make()->schema([
                Section::make('Image')
                ->collapsible()
                ->persistCollapsed()
                ->schema([
                    FileUpload::make('image')->disk('public')->directory('images')->columnSpan(1),
                ])->columnSpan(1),
                Section::make('Meta')
                ->schema([
                    TagsInput::make('tags')->required(),
                    DatePicker::make('created_at')->visibleOn('edit')->native(false),
                    Select::make('user_id')
                    ->label('Owner')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),  
                    Checkbox::make('published')->required(), 
                ])->columnSpan(1),
            ]) 
        ])->columns([
            'default' => 3,
            'sm' => 3,
            'md' => 3,
            'lg' => 3,
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                // Tables\Columns\TextColumn::make('title'),
                TextColumn::make('id')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')->toggleable(),
                TextColumn::make('title')->sortable()->searchable()->toggleable(),
                TextColumn::make('slug')->sortable()->searchable()->toggleable(),
                ColorColumn::make('color')->sortable()->searchable()->toggleable(),
                TextColumn::make('category.name')->sortable()->searchable()->toggleable(),
                TextColumn::make('user.name')->sortable()->searchable()->toggleable(),
                CheckboxColumn::make('published')->sortable()->searchable()->toggleable(),
                TextColumn::make('created_at')->sortable()->searchable()->label('Published On')->date()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
