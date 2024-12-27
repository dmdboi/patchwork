<?php

namespace App\Filament\Exports;

use App\Models\Menu;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MenuExporter extends Exporter
{
    protected static ?string $model = Menu::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make("id"),
            ExportColumn::make("key"),
            ExportColumn::make("location"),
            ExportColumn::make("title"),
            ExportColumn::make("items"),
            ExportColumn::make("is_active"),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your menu export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
