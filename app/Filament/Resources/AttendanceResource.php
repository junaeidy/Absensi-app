<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Attendance Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user')
                    ->relationship('user', 'name')
                    ->disabled(),
                Forms\Components\TextInput::make('schedule_latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('schedule_longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('schedule_start_time')
                    ->required(),
                Forms\Components\TextInput::make('schedule_end_time')
                    ->required(),
                Forms\Components\TextInput::make('start_latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('start_longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->required(),
                Forms\Components\TextInput::make('end_latitude')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('end_longitude')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query){
                $is_super_admin = Auth::user()->hasRole('super_admin');

                if (!$is_super_admin) {
                    $query->where('user_id', Auth::user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->searchable()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_late')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->isLate() ? 'Terlambat' : 'Tepat Waktu';
                    })
                    ->color(fn ($record) => $record->isLate() ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Waktu Datang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Waktu Pulang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('work_duration')
                    ->label('Durasi Kerja')
                    ->getStateUsing(function ($record) {
                        return $record->workDuration();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
