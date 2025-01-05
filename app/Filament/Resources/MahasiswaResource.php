<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Filament\Resources\MahasiswaResource\RelationManagers;
use App\Models\Desa;
use App\Models\Kabkota;
use App\Models\Kecamatan;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mahasiswa';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Mahasiswa')
                    ->schema([
                        Forms\Components\TextInput::make('nim')
                            ->label('NIM')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\Select::make('agama_id')
                            ->relationship('agama', 'nama')
                            ->required(),
                        Forms\Components\Select::make('prodi_id')
                            ->relationship('prodi', 'nama')
                            ->required(),
                        Fieldset::make('Domisili sesuai KTP')
                            ->schema([
                                Select::make('provinsi_id')
                                    ->label('Provinsi')
                                    ->relationship('provinsi','name')
                                    ->required()
                                    ->afterStateUpdated(function(Set $set){
                                        $set('kabkota_id','');
                                        $set('kecamatan_id','');
                                        $set('desa_id','');
                                    })
                                    ->preload()
                                    ->live()
                                    ->searchable(),
                                Select::make('kabkota_id')
                                    ->label('Kabupaten / Kota')
                                    ->options(fn(Get $get):Collection =>
                                        Kabkota::query()
                                        ->where('province_id',$get('provinsi_id'))
                                        ->pluck('name','id')
                                    )
                                    ->required()
                                    ->afterStateUpdated(function(Set $set){
                                        $set('kecamatan_id','');
                                        $set('desa_id','');
                                    })
                                    ->live()
                                    ->preload()
                                    ->searchable(),
                                Select::make('kec_id')
                                    ->label('Kecamatan')
                                    ->options(fn(Get $get):Collection =>
                                        Kecamatan::query()
                                        ->where('regency_id',$get('kabkota_id'))
                                        ->pluck('name','id')
                                    )
                                    ->required()
                                    ->afterStateUpdated(function(Set $set){
                                        $set('desa_id','');
                                    })
                                    ->live()
                                    ->preload()
                                    ->searchable(),
                                Select::make('desa_id')
                                    ->label('Desa')
                                    ->options(fn(Get $get):Collection =>
                                        Desa::query()
                                        ->where('district_id',$get('kec_id'))
                                        ->pluck('name','id')
                                    )
                                    ->live()
                                    ->required()
                                    ->preload()
                                    ->searchable(),
                            ])
                    ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agama.nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prodi.nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('provinsi.name')
                ->label('Provinsi')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                SelectFilter::make('agama_id')
                    ->label('Agama')
                    ->relationship('agama', 'nama'),
                SelectFilter::make('prodi_id')
                    ->label('Prodi')
                    ->multiple()
                    ->relationship('prodi', 'nama'),
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
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
