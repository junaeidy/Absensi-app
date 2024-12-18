<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make("Download Presensi")
            // ->url(route('attendances.export'))
            // ->color('info')
            // ->icon('heroicon-o-arrow-down-tray'),
            Action::make("Tambah Presensi")
            ->url(route('presensi'))
            ->color('success')
            ->icon('heroicon-o-calendar'),
            Actions\CreateAction::make(),
        ];
    }
}
