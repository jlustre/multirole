<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorsRelationManager extends RelationManager
{
    protected static string $relationship = 'authors';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name')->label('Username')->validationAttribute('Username')->required()
            ->unique(ignoreRecord: true)->minLength(5)->maxLength(20),
            TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
            TextInput::make('order')->numeric()->required(),
            // TextInput::make('password')->required()->password()->confirmed()->autocomplete(false)
            // ->minLength(7)->maxLength(20),
            // TextInput::make('password_confirmation')->password(),
            // Select::make('role')->required()->options([
            //     'user'=>'User',
            //     'admin'=>'Admin'
            //     ])->native(false),
            // FileUpload::make('thumbnail')->disk('public')->directory('images'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                // ImageColumn::make('thumbnail')->toggleable(),
                TextColumn::make('name')->sortable()->searchable()->toggleable(),
                TextColumn::make('email')->sortable()->searchable()->toggleable(),
                TextColumn::make('order')->sortable()->searchable()->toggleable(),
                // TextColumn::make('role')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('created_at')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Forms\Components\TextInput::make('order')->numeric()->required(),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
