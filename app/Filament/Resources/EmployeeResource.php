<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug='employees';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')->required(),
            Forms\Components\TextInput::make('middle_name'),
            Forms\Components\TextInput::make('last_name')->required(),
            Forms\Components\TextInput::make('address')->required(),
            Forms\Components\TextInput::make('zipcode')->maxLength(10)->required(),
            Forms\Components\DatePicker::make('date_of_birth')->required()->native(false),
            Forms\Components\DatePicker::make('date_of_hired')->required()->native(false),
    
            // Country Select
            Forms\Components\Select::make('country_id')
                ->relationship('country', 'name')
                ->required()
                ->preload()
                ->searchable()
                ->options(fn () => \App\Models\Country::pluck('name', 'id'))
                ->live()
                ->afterStateUpdated(fn(Set $set) => $set('state_id', null))
                ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
    
            // State Select (Filtered by Country)
            Forms\Components\Select::make('state_id')
                ->relationship('state', 'name')
                ->required()
                ->preload()
                ->searchable()
                ->options(fn (Get $get) => 
                    $get('country_id') 
                        ? \App\Models\State::where('country_id', $get('country_id'))->pluck('name', 'id') 
                        : []
                )
                ->live()
                ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
    
            // City Select (Filtered by State)
            Forms\Components\Select::make('city_id')
                ->relationship('city', 'name')
                ->required()
                ->preload()
                ->searchable()
                ->options(fn (Get $get) => 
                    $get('state_id') 
                        ? \App\Models\City::where('state_id', $get('state_id'))->pluck('name', 'id') 
                        : []
                )
                ->live(),
    
            // Department Select
            Forms\Components\Select::make('department_id')
                ->relationship('department', 'name')
                ->required()
                ->preload()
                ->searchable(),
        ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('middle_name')
                    ->label('Middle Name')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->sortable()
                    ->searchable(),
    
               
    
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('state.name')
                    ->label('State')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('city.name')
                    ->label('City')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
