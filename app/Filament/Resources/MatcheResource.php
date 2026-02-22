<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatcheResource\Pages;
use App\Filament\Resources\MatcheResource\RelationManagers;
use App\Models\Matche;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatcheResource extends Resource
{
    protected static ?string $model = Matche::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('home_team_id')
                ->label('Local')
                ->relationship('homeTeam', 'name')
                ->searchable()
                ->nullable(),

            Select::make('away_team_id')
                ->label('Visitante')
                ->relationship('awayTeam', 'name')
                ->searchable()
                ->nullable(),

            Select::make('status')
                ->options([
                    'pendiente'  => 'Pendiente',
                    'en_juego'   => 'En juego',
                    'finalizado' => 'Finalizado',
                ])
                ->required(),

            TextInput::make('home_score')->numeric()->nullable(),
            TextInput::make('away_score')->numeric()->nullable(),
            TextInput::make('home_extra_score')->numeric()->nullable(),
            TextInput::make('away_extra_score')->numeric()->nullable(),

            Select::make('penalty_winner_id')
                ->label('Ganador por penales')
                ->relationship('penaltyWinner', 'name')
                ->searchable()
                ->nullable(),

            DateTimePicker::make('match_date')->label('Fecha y hora'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('match_number')->label('#')->sortable(),
                TextColumn::make('stage')->label('Fase'),
                TextColumn::make('homeTeam.name')->label('Local'),
                TextColumn::make('awayTeam.name')->label('Visitante'),
                TextColumn::make('home_score')->label('G'),
                TextColumn::make('away_score')->label('G'),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'en_juego',
                        'success' => 'finalizado',
                        'gray'    => 'pendiente',
                    ]),
                TextColumn::make('match_date')->label('Fecha')->dateTime(),
            ])
            ->defaultSort('match_number');
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
            'index' => Pages\ListMatches::route('/'),
            'create' => Pages\CreateMatche::route('/create'),
            'edit' => Pages\EditMatche::route('/{record}/edit'),
        ];
    }
}
