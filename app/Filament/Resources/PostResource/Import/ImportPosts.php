<?php

namespace App\Filament\Resources\PostResource\Import;

use App\Models\Post;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ImportPosts extends Importer
{
    protected static ?string $model = Post::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('author.id')
                ->label('Author'),
            ImportColumn::make('title')
                ->label('Title'),
            ImportColumn::make('slug')
                ->label('Slug'),
            ImportColumn::make('short_description')
                ->label('Short Description'),
            ImportColumn::make('body')
                ->label('Body'),
            ImportColumn::make('type')
                ->label('Type'),
            ImportColumn::make('is_published')
                ->label('Is Published'),
            ImportColumn::make('published_at')
                ->label('Published At'),
            ImportColumn::make('created_at')
                ->label('Created At'),
            ImportColumn::make('collection.id')
                ->label('Collection'),
            ImportColumn::make('category.id')
                ->label('Category'),
        ];
    }

    public function resolveRecord(): ?Post
    {
        return Post::firstOrNew([
            'title' => $this->data['title'],
            'short_description' => $this->data['short_description'],
            'slug' => $this->data['slug'],
            'type' => $this->data['type'],
            'body' => $this->data['body'],
            'is_published' => $this->data['is_published'],
            'published_at' => $this->data['published_at'],
            'created_at' => $this->data['created_at'],
            'author_id' => $this->data['author.id'],
            'collection_id' => $this->data['collection.id'],
            'category_id' => $this->data['category.id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your post import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
