<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationLabel = 'Pendaftar';
    protected static ?string $navigationGroup = 'Magang';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'nama_lengkap')
                    ->searchable()
                    ->label('Peserta')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_mulai')->required(),
                Forms\Components\DatePicker::make('tgl_selesai')->required(),

                Forms\Components\Textarea::make('komentar')
                    ->columnSpanFull()
                    ->label('Komentar dari admin')->disabled(fn() => auth()->user()->role === 'peserta'),

                Forms\Components\Select::make('status_pendaftaran')
                    ->options([
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'revisi' => 'Revisi',
                        'menunggu_keputusan' => 'Menunggu Keputusan',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
                    ->disabled(fn() => ! in_array(auth()->user()->role, ['admin', 'kadin']))
                    ->required(),

                Forms\Components\FileUpload::make('surat_pengantar')
                    ->label('Upload Surat Pengantar')
                    ->disk('public')
                    ->directory('surat_pengantar')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required()
                    ->downloadable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.nama_lengkap')->label('Nama Peserta')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')->date('d M Y'),
                Tables\Columns\TextColumn::make('tgl_mulai')->date('d M Y'),
                Tables\Columns\TextColumn::make('tgl_selesai')->date('d M Y'),
                Tables\Columns\TextColumn::make('status_pendaftaran')->badge()->colors([
                    'warning' => 'menunggu_verifikasi',
                    'info' => 'revisi',
                    'gray' => 'menunggu_keputusan',
                    'success' => 'diterima',
                    'danger' => 'ditolak',
                ])
                    ->label('Status'),
            ])
            ->filters([
                SelectFilter::make('status_pendaftaran')
                    ->options([
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'revisi' => 'Revisi',
                        'menunggu_keputusan' => 'Menunggu Keputusan',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Edit button hanya untuk peserta dan pada pendaftaran miliknya sendiri
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => auth()->user()->role === 'peserta' && auth()->id() === $record->user_id),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->role === 'admin'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
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
            'index' => Pages\ListPendaftarans::route('/'),
            'view' => Pages\ViewPendaftaran::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role !== 'peserta'; // peserta hanya bisa lihat miliknya dia
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->role === 'peserta';
    }

    public static function canDelete($record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}
