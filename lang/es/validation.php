<?php

/*
| Mensajes de validación en español. De momento, los de las reglas que usa la
| web; la #43 completa el resto.
*/
return [
    'accepted' => 'Tienes que aceptar la :attribute.',
    'email' => 'Escribe un email válido.',
    'max' => [
        'string' => 'El :attribute no puede tener más de :max caracteres.',
    ],
    'min' => [
        'string' => 'El :attribute debe tener al menos :min caracteres.',
    ],
    'regex' => 'El :attribute solo puede tener letras sin acentos, números y _.',
    'required' => 'Falta el :attribute.',
    'string' => 'El :attribute debe ser un texto.',
    'unique' => 'Ese :attribute ya está en uso.',
    'username_reserved' => 'Ese :attribute está reservado. Elige otro.',

    'password' => [
        'letters' => 'La contraseña debe tener al menos una letra.',
        'mixed' => 'La contraseña debe tener mayúsculas y minúsculas.',
        'numbers' => 'La contraseña debe tener al menos un número.',
        'symbols' => 'La contraseña debe tener al menos un símbolo.',
        'uncompromised' => 'Esta contraseña ha aparecido en una filtración de datos. Elige otra.',
    ],

    'custom' => [
        'password' => [
            'min' => 'La contraseña debe tener al menos :min caracteres.',
            'required' => 'Falta la contraseña.',
        ],
        'privacy_policy' => [
            'accepted' => 'Tienes que aceptar la política de privacidad para crear la cuenta.',
            'required' => 'Tienes que aceptar la política de privacidad para crear la cuenta.',
        ],
    ],

    'attributes' => [
        'email' => 'email',
        'password' => 'contraseña',
        'privacy_policy' => 'política de privacidad',
        'username' => 'nombre de usuario',
    ],
];
