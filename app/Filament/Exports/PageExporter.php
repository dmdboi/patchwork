<?php

namespace App\Filament\Exports;

use App\Models\Page;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PageExporter extends Exporter
{
    protected static ?string $model = Page::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('author.id')
                ->label('Author'),
            ExportColumn::make('title')
                ->label('Title'),
            ExportColumn::make('slug')
                ->label('Slug'),
            ExportColumn::make('short_description')
                ->label('Short Description'),
            ExportColumn::make('body')
                ->label('Body'),
            ExportColumn::make('type')
                ->label('Type'),
            ExportColumn::make('is_published')
                ->label('Is Published'),
            ExportColumn::make('published_at')
                ->label('Published At'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('collection.id')
                ->label('Collection'),
            ExportColumn::make('category.id')
                ->label('Category'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your page export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
