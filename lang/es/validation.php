<?php

/*
| Mensajes de validación en español. De momento, los de las reglas que usa la
| web; la #43 completa el resto.
*/
return [
    'accepted' => 'Tienes que aceptar la :attribute.',
    'between' => [
        'numeric' => 'El :attribute tiene que estar entre :min y :max.',
    ],
    'confirmed' => 'Las dos contraseñas no coinciden.',
    'current_password' => 'La contraseña actual no es correcta.',
    'dimensions' => 'La imagen es demasiado grande: como mucho, 6000 × 6000 píxeles.',
    'email' => 'Escribe un email válido.',
    'in' => 'El :attribute no es válido.',
    'image' => 'El :attribute tiene que ser una imagen.',
    'max' => [
        'file' => 'El :attribute no puede pesar más de :max KB.',
        'string' => 'El :attribute no puede tener más de :max caracteres.',
    ],
    'integer' => 'El :attribute tiene que ser un número entero.',
    'multiple_of' => 'El :attribute tiene que ir en pasos de :value.',
    'mimes' => 'El :attribute tiene que ser JPG, PNG o WebP.',
    'min' => [
        'string' => 'El :attribute debe tener al menos :min caracteres.',
    ],
    'regex' => 'El :attribute solo puede tener letras sin acentos, números y _.',
    'required' => 'Falta el :attribute.',
    'numeric' => 'El :attribute tiene que ser un número.',
    'string' => 'El :attribute debe ser un texto.',
    'uploaded' => 'No se ha podido subir el :attribute. Prueba con una imagen de menos de 2 MB.',
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
        'latitude' => [
            'required' => 'Elige un punto en el mapa, busca una dirección o usa tu ubicación.',
        ],
        'avatar' => [
            'max' => 'La foto no puede pesar más de 2 MB.',
        ],
        'bio' => [
            'max' => 'La bio no puede tener más de :max caracteres.',
        ],
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
        'avatar' => 'avatar',
        'bio' => 'bio',
        'current_password' => 'contraseña actual',
        'email' => 'email',
        'latitude' => 'latitud',
        'longitude' => 'longitud',
        'radius_m' => 'radio',
        'source' => 'origen del punto',
        'password' => 'contraseña',
        'privacy_policy' => 'política de privacidad',
        'username' => 'nombre de usuario',
    ],
];
