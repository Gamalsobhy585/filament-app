<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
        protected static ?string $slug='users';

        public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    Forms\Components\Section::make('User Info')
                        ->description('Put user details in')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
        
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                        ]),
        
                    Forms\Components\Section::make('basic Info')
                        ->schema([
                            Forms\Components\TextInput::make('first_name')
                                ->maxLength(255)
                                ->default(null),
        
                            Forms\Components\TextInput::make('middle_name')
                                ->maxLength(255)
                                ->default(null),
        
                            Forms\Components\TextInput::make('last_name')
                                ->maxLength(255)
                                ->default(null),
                        ])->columns(3),
        
                    Forms\Components\Section::make('Contact & Security')
                        ->schema([
                            Forms\Components\TextInput::make('phone')
                                ->tel()
                                ->maxLength(255)
                                ->default(null),
        
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->required()
                                ->maxLength(255),
        
                            Forms\Components\TextInput::make('username')
                                ->maxLength(255)
                                ->default(null),
                        ]),
                ]);
        }
        

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
               
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
               
              
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
               
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
