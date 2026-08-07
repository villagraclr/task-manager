<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => 'The :attribute must be accepted.',
    'boolean' => 'The :attribute field must be true or false.',
    'date' => 'The :attribute is not a valid date.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'integer' => 'The :attribute must be an integer.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be a string.',
    'unique' => 'The :attribute has already been taken.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'title' => [
            'required' => 'The task title is required.',
            'max' => 'The task title may not exceed :max characters.',
        ],

        'description' => [
            'max' => 'The description may not exceed :max characters.',
        ],

        'status' => [
            'required' => 'The task status is required.',
        ],

        'priority' => [
            'required' => 'The task priority is required.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'title' => 'title',
        'description' => 'description',
        'status' => 'status',
        'priority' => 'priority',
        'due_date' => 'due date',
    ],

];
