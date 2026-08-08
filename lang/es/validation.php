<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'exists' => 'El :attribute seleccionado no es válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'max' => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'unique' => 'El campo :attribute ya existe.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'title' => [
            'required' => 'El título de la tarea es obligatorio.',
            'max' => 'El título no puede superar los :max caracteres.',
        ],

        'description' => [
            'max' => 'La descripción no puede superar los :max caracteres.',
        ],

        'status' => [
            'required' => 'El estado de la tarea es obligatorio.',
        ],

        'priority' => [
            'required' => 'La prioridad de la tarea es obligatoria.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'title' => 'título',
        'description' => 'descripción',
        'status' => 'estado',
        'priority' => 'prioridad',
        'due_date' => 'fecha de vencimiento',
    ],

];
