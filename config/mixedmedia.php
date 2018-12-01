<?php

return [
	/*
    |--------------------------------------------------------------------------
    | Configuracion para los tipos de recurso en el app movil
    |--------------------------------------------------------------------------
    */

    'resource_type' => 'quote,video,image,audio',

    /*
    |--------------------------------------------------------------------------
    | Configuracion para las migraciones de laravel generadas por el admin
    |--------------------------------------------------------------------------
    */

    'migration' => [

        /*
        |--------------------------------------------------------------------------
        | Available Column Types
        |--------------------------------------------------------------------------
        |
        | Se establecen cuales de los tipos de datos establecidos en la 
        | documentacion de laravel se aceptaran.
        |
        */

        'column_types' => [
            'integer'             => 'Integer',
            'bigInteger'          => 'Big Integer',
            'unsignedInteger'     => 'Unsigned Integer',
            'unsignedBigInteger'  => 'Unsigned Big Integer',
            'date'                => 'Date',
            'dateTime'            => 'DateTime',
            'time'                => 'Time',
            'string'              => 'String',
            'text'                => 'Text',
            'json'                => 'Json'
        ],

        /*
        |--------------------------------------------------------------------------
        | Available Column Modifiers
        |--------------------------------------------------------------------------
        |
        | Se establecen cuales de los tipos de datos que se encuentran en la 
        | documentacion de laravel se aceptaran.
        |
        */

        'column_modifiers' => [

            'unique' => 'Unique',
            'nullable' => 'Nullable',           
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Configuracion los componentes html usados en las vistas
    |--------------------------------------------------------------------------
    */

    'html_components'=>[
        'date_picker'     => 'Date Picker',
        'checkbox_inline' => 'Checkbox Inline',
        'images_upload'   => 'Gallery',
        'images_upload_info'   => 'Gallery Info',
        'image_upload'    => 'Image',
        'input_email'     => 'Input Email',
        'input_file'      => 'Input File',
        'input_number'    => 'Input Number',
        'input_range'     => 'Input Range',
        'input_text'      => 'Input Text',
        'input_date'      => 'Input Date',
        'json'            => 'Json Interface',
        'select_simple'   => 'Select Simple',
        'select_multiple' => 'Select Multiple',
        'sortable_list'   => 'Sortable List',
        'textarea'        => 'Text Area',
        'text_editor'     => 'Text Editor'

    ],

    /*
    |--------------------------------------------------------------------------
    | Configuracion para los modelos eloquent generados por el admin
    |--------------------------------------------------------------------------
    */

    'model'=>[
        'relationships'=>[
            'belongsTo'      => 'Belongs To',
            'belongsToMany'  => 'Belongs To Many',
            'hasOne'         => 'Has One',
            'hasMany'        => 'Has Many',
            'hasManyThrough' => 'Has Many Through'
        ]
    ],

    'buttons'=> [ 'btn-primary','btn-success','btn-danger','btn-warning','btn-default','btn-info','bg-navy','bg-teal','bg-purple','bg-orange','bg-maroon','bg-black','bg-ligth-blue','bg-aqua','bg-green','bg-yellow','bg-red','bg-gray','bg-olive']
];
