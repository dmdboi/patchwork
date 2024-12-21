<?php

namespace App\Services;

use App\Services\Contracts\Section;
use Exception;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;

class FilamentCMSThemes
{
    private static array $sections = [];

    public static function register(Section|array $section)
    {
        if (is_array($section)) {
            foreach ($section as $item) {
                self::register($item);
            }

            return;
        } else {
            self::$sections[] = $section;
        }
    }

    public static function getSections(): Collection
    {
        return collect(self::$sections);
    }

    public static function parseSectionJSON(string $json): array
    {

        $jsonArray = json_decode($json, true);

        // If the input is a single section, wrap it in an array
        if (isset($jsonArray['name'])) {
            $jsonArray = [$jsonArray];
        }

        return array_map(function ($json) {
            return Section::make($json['name'])
                ->label($json['label'] ?? '')
                ->view($json['view'] ?? '')
                ->form(
                    self::resolveFormArrayToObjects($json['form'] ?? [])
                );
        }, $jsonArray);
    }

    public static function resolveFormArrayToObjects(array $form): array
    {
        return array_map(function ($field) {
            switch ($field['type']) {
                case 'TextInput':
                    $textInput = TextInput::make($field['name']);

                    if (!empty($field['label'])) {
                        $textInput = $textInput->label($field['label']);
                    }

                    if (!empty($field['required']) && $field['required'] === true) {
                        $textInput = $textInput->required();
                    }

                    return $textInput;

                case 'Repeater':
                    $repeater = Repeater::make($field['name'])->label($field['label']);

                    if (!empty($field['collapsible']) && $field['collapsible'] === true) {
                        $repeater = $repeater->collapsible();
                    }

                    if (!empty($field['schema'])) {
                        $repeater = $repeater->schema(resolveFormArrayToObjects($field['schema']));
                    }

                    return $repeater;

                case 'Select':
                    $select = Select::make($field['name'])->label($field['label']);

                    if (!empty($field['options'])) {
                        $select = $select->options($field['options']);
                    }

                    return $select;

                default:
                    throw new Exception("Unsupported field type: {$field['type']}");
            }
        }, $form);
    }


}
