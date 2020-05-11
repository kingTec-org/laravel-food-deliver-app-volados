<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accept' => 'El :attribute debe ser aceptado.',
    'active_url' => 'El :attribute no es una URL válida.',
    'after' => 'El :attribute debe ser una fecha después de: date.',
    'after_or_equal' => 'El :attribute debe ser una fecha posterior o igual a: date.',
    'alpha' => 'El :attribute solo puede contener letras',
    'alpha_dash' => 'El :attribute solo puede contener letras, números y guiones',
    'alpha_num' => 'El :attribute solo puede contener letras y números',
    'array' => 'El :attribute debe ser una matriz.',
    'before' => 'El :attribute debe ser una fecha anterior a: date.',
    'before_or_equal' => 'El :attribute debe ser una fecha anterior o igual a: date.',
    'between' => [
        'numeric' => 'El :attribute debe estar entre: min y: max.',
        'file' => 'El :attribute debe estar entre: min y: max kilobytes.',
        'string' => 'El :attribute debe estar entre: min y: max caracteres.',
        'array' => 'El :attribute debe tener entre: min y: max items.',
    ],
    'boolean' => 'El :attribute campo de atributo debe ser verdadero o falso',
    'confirm' => 'El :attribute la confirmación del atributo no coincide.',
    'date' => 'El :attribute atributo no es una fecha válida.',
    'date_format' => 'El :attribute no coincide con el formato: formato.',
    'different' => 'El :attribute otro other: deben ser diferentes',
    'digits' => 'El :attribute: debe ser :digits dígitos.',
    'digits_between' => 'El :attribute debe estar entre :min y :max dígitos.',
    'dimensions' => 'El :attribute tiene dimensiones de imagen no válidas',
    'distinct' => 'El :attribute atributo tiene un valor duplicado',
    'email' => 'El :attribute debe ser una dirección de correo electrónico válida.',
    'exist' => 'El atributo :attribute: no es válido',
    'file' => 'El :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tener un valor.',
    'image' => 'El :attribute debe ser una imagen.',
    'in' => 'El seleccionado :attribute no es válido',
    'in_array' => 'El campo :attribute no existe en: otro.',
    'integer' => 'El :attribute debe ser un entero.',
    'ip' => 'El :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El :attribute debe ser una dirección IPv4 válida',
    'ipv6' => 'El :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El :attribute debe ser una cadena JSON válida.',
    'max' => [
        'numeric' => 'El :attribute no puede ser mayor que: max.',
        'file' => 'El :attribute no puede ser mayor que: kilobytes máx.',
        'string' => 'El :attribute no puede ser mayor que: caracteres máximos',
        'array' => 'El :attribute no puede tener más de: max artículos.',
    ],
    'mimes' => 'El :attribute debe ser un archivo de tipo:: valores.',
    'mimetypes' => 'El :attribute debe ser un archivo de tipo:: valores.',
    'min' => [
        'numeric' => 'El :attribute debe ser al menos: min.',
        'file' => 'El :attribute debe ser al menos: min kilobytes',
        'string' => 'El :attribute debe tener al menos: caracteres min.',
        'array' => 'El :attribute debe tener al menos: elementos min.',
    ],
    'not_in' => 'El seleccionado :attribute no es válido',
    'not_regex' => 'El :attribute formato del atributo no es válido',
    'numeric' => 'El :attribute debe ser un número.',
    'present' => 'El campo :attribute debe estar presente',
    'regex' => 'El formato del :attribute no es válido',
    'required' => 'El campo de :attribute es obligatorio',
    'required_if' => 'El campo :attribute es obligatorio cuando: other es: value.',
    'required_unless' => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with' => 'El campo :attribute es obligatorio cuando :values los valores están presentes',
    'required_with_all' => 'El campo :attribute es obligatorio cuando :values los valores están presentes',
    'required_without' => 'El campo :attribute es obligatorio cuando :values los valores no están presentes',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los valores: está presente.',
    'same' => 'El :attribute y :other deben coincidir',
    'size' => [
        'numeric' => 'El :attribute debe ser :size.',
        'file' => 'El :attribute debe ser :size kilobytes.',
        'string' => 'El :attribute debe ser :size caracteres de tamaño.',
        'array' => 'El :attribute debe contener :size elementos de tamaño.',
    ],
    'string' => 'El :attribute debe ser una cadena.',
    'timezone' => 'El :attribute debe ser una zona válida.',
    'unique' => 'El :attribute ya se ha tomado.',
    'uploaded' => 'El :attribute no se pudo cargar',
    'url' => 'El formato del atributo no es válido',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
