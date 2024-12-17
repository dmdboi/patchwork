<?php

namespace App\Providers;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use ProtoneMedia\Splade\Http\SpladeMiddleware;

use App\Facades\FilamentCMS;
use App\Livewire\BuilderToolbar;
use App\Livewire\BuilderToolbarForm;
use App\Livewire\BuilderToolbarHeader;
use App\Sections\TomatoAboutFeaturesSection;
use App\Services\Contracts\CmsFormFieldType;
use App\Services\Contracts\CmsType;
use App\Services\Contracts\Section;
use App\Services\FilamentCMSFormFields;
use App\Services\FilamentCMSServices;
use App\Services\FilamentCMSTypes;
use TomatoPHP\FilamentIcons\Components\IconPicker;

require_once  __DIR__ .'/helpers.php';

class FilamentCmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->loadTranslationsFrom(resource_path('lang'), 'filament-cms');

        $this->app->bind('filament-cms', function() {
            return new FilamentCMSServices();
        });

        $this->loadViewComponentsAs('tomato', [
            \App\Views\BuilderToolbar::class,
        ]);

    }

    public function boot(): void
    {
        Livewire::isDiscoverable(BuilderToolbar::class);

        FilamentCMSTypes::register([
           CmsType::make('post')
                ->label(trans('filament-cms::messages.types.post'))
                ->color('success')
                ->icon('heroicon-o-document')
                ->sub([
                    CmsType::make('category')
                        ->color('info')
                        ->icon('heroicon-o-folder')
                        ->label(trans('filament-cms::messages.types.category')),
                    CmsType::make('tags')
                        ->color('warning')
                        ->icon('heroicon-o-tag')
                        ->label(trans('filament-cms::messages.types.tags')),
                ]),
           CmsType::make('page')
               ->label(trans('filament-cms::messages.types.page'))
               ->color('success')
               ->icon('heroicon-o-bars-3-center-left'),
       ]);

        FilamentCMSFormFields::register([
            CmsFormFieldType::make('text')
                ->label('Text'),
            CmsFormFieldType::make('textarea')
                ->className(Textarea::class)
                ->color('warning')
                ->icon('heroicon-s-document-text')
                ->label('Textarea'),
            CmsFormFieldType::make('select')
                ->className(Select::class)
                ->color('info')
                ->icon('heroicon-s-squares-plus')
                ->label('Select'),
            CmsFormFieldType::make('checkbox')
                ->className(Checkbox::class)
                ->color('danger')
                ->icon('heroicon-s-check')
                ->label('Checkbox'),
            CmsFormFieldType::make('radio')
                ->className(Radio::class)
                ->color('success')
                ->icon('heroicon-s-check-circle')
                ->label('Radio'),
            CmsFormFieldType::make('file')
                ->className(FileUpload::class)
                ->color('info')
                ->icon('heroicon-s-document-arrow-up')
                ->label('File'),
            CmsFormFieldType::make('date')
                ->className(DatePicker::class)
                ->color('success')
                ->icon('heroicon-s-calendar')
                ->label('Date'),
            CmsFormFieldType::make('time')
                ->className(TimePicker::class)
                ->color('info')
                ->icon('heroicon-s-clock')
                ->label('Time'),
            CmsFormFieldType::make('datetime')
                ->className(DateTimePicker::class)
                ->color('warning')
                ->icon('heroicon-s-calendar-days')
                ->label('DateTime'),
            CmsFormFieldType::make('color')
                ->className(ColorPicker::class)
                ->color('success')
                ->icon('heroicon-s-swatch')
                ->label('Color'),
            CmsFormFieldType::make('icon')
                ->className(IconPicker::class)
                ->color('info')
                ->icon('heroicon-s-heart')
                ->label('Icon'),
            CmsFormFieldType::make('toggle')
                ->className(Toggle::class)
                ->color('success')
                ->icon('heroicon-s-adjustments-horizontal')
                ->label('Toggle'),
            CmsFormFieldType::make('password')
                ->color('danger')
                ->icon('heroicon-s-lock-closed')
                ->label('Password'),
            CmsFormFieldType::make('email')
                ->color('info')
                ->icon('heroicon-s-envelope')
                ->label('Email'),
            CmsFormFieldType::make('number')
                ->color('success')
                ->icon('heroicon-s-minus-circle')
                ->label('Number'),
            CmsFormFieldType::make('url')
                ->color('primary')
                ->icon('heroicon-s-globe-alt')
                ->label('URL'),
            CmsFormFieldType::make('tel')
                ->color('warning')
                ->icon('heroicon-s-phone')
                ->label('Tel'),
            CmsFormFieldType::make('markdown')
                ->className(MarkdownEditor::class)
                ->color('warning')
                ->icon('heroicon-s-hashtag')
                ->label('Markdown'),
            CmsFormFieldType::make('rich')
                ->className(RichEditor::class)
                ->color('info')
                ->icon('heroicon-s-document-text')
                ->label('RichText'),
            CmsFormFieldType::make('keyValue')
                ->className(KeyValue::class)
                ->color('danger')
                ->icon('heroicon-s-key')
                ->label('Key/Value'),
            CmsFormFieldType::make('repeater')
                ->className(Repeater::class)
                ->icon('heroicon-s-rectangle-group')
                ->label('Repeater'),
        ]);
    }
}
