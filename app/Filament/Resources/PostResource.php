<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Blogs';
    // protected static ?string $navigationParentItem = 'Posts';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'All Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Create New Post')->tabs([
                    Tab::make('Post Info')
                        ->icon('heroicon-m-information-circle')
                        ->schema([
                            Group::make()->schema([
                                TextInput::make('title')->required()->columnSpan(2)->unique(ignoreRecord: true)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, string $state, Forms\Set $set) {
                                    if ($operation === 'edit') {
                                        return;
                                    }
                                    $set('slug', Str::slug($state));
                                }),
                                TextInput::make('slug')->required()->unique(ignoreRecord: true)->columnSpan(2),
                                ColorPicker::make('color')->required()->columnSpan(2), 
                                Select::make('category_id')
                                ->label('Category')
                                // ->options(Category::all()->pluck('name', 'id'))
                                ->relationship('category', 'name')
                                ->searchable()->columnSpan(2),
                        ]) ->columns(4)
                    ]),
                    Tab::make('Content')
                        ->icon('heroicon-m-chat-bubble-bottom-center-text')
                        ->schema([
                        MarkdownEditor::make('content')->required()->columnSpan(2),
                    ]),
                    Tab::make('Image/Meta')
                        ->icon('heroicon-m-identification')
                        ->schema([
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
                                // Checkbox::make('published')->required(), 
                            ])->columnSpan(2),
                        ]) ->columns(3)
                    ]),
                ])->columnSpanFull()->activeTab(2)->persistTabInQueryString(),              
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')->toggleable(),
                TextColumn::make('title')->sortable()->searchable()->toggleable(),
                TextColumn::make('slug')->sortable()->searchable()->toggleable(),
                ColorColumn::make('color')->sortable()->searchable()->toggleable(),
                TextColumn::make('category.name')->sortable()->searchable()->toggleable(),
                TextColumn::make('authors.name')->sortable()->searchable()->toggleable(),
                // CheckboxColumn::make('published')->sortable()->searchable()->toggleable(),
                TextColumn::make('created_at')->sortable()->searchable()->label('Published On')
                // ->formatStateUsing(fn (string $state) => Carbon::parse($state)->format('M d, Y'))
                ->formatStateUsing(fn (string $state) => Carbon::parse($state)->diffForHumans())
                // ->date()
                ->toggleable(),
            ])
            ->filters([
                // Filter::make('Published Posts')->query(
                //     function ($query) {
                //         return $query->where('published', true);
                //     }
                // ),
                // TernaryFilter::make('published'),
                SelectFilter::make('category_id')
                ->label('Category')
                // ->options(Category::all()->pluck('name','id'))
                ->relationship('category','name')
                ->preload()
                ->searchable()
                ->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->toggleColumnsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Toggle columns'),
            );
    }

    public static function getRelations(): array
    {
        return [
            AuthorsRelationManager::class,
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
