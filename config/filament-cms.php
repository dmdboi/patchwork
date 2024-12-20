<?php

return [
    /*
     * ---------------------------------------------------
     * Allow Features
     * ---------------------------------------------------
     */
    'features' => [
        'category' => true,
        'posts' => true,
        'comments' => false,
        'theme-manager' => false,
        'forms' => true,
        'form_requests' => true,
        'tickets' => false,
        'apis' => false,
    ],

    'theme' => 'egon',

    'types' => [
        'post',
        'page',
    ],

    'editor' => [
        'options' => [
            'attachFiles',
            'blockquote',
            'bold',
            'bulletList',
            'codeBlock',
            'heading',
            'italic',
            'link',
            'orderedList',
            'redo',
            'strike',
            'table',
            'undo',
        ],
    ],

    /*
     * ---------------------------------------------------
     * Youtube Integration For Posts Meta
     * ---------------------------------------------------
     */
    'youtube_key' => env('YOUTUBE_KEY', null),

    /*
     * ---------------------------------------------------
     * Supported Lanuages For Content
     * ---------------------------------------------------
     */
    'lang' => [
        'en' => 'English',
    ],

    'themes' => [
        'scan' => true,
        'sections' => [
            '/vendor/tomatophp/filament-cms/src/Sections',
        ],
    ],
];
