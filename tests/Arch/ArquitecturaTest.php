<?php

use Illuminate\Database\Eloquent\Model;

/*
| Tests de arquitectura: reglas sobre el código, no sobre su comportamiento.
| Fallan si alguien deja un dd() olvidado o usa env() fuera de config/.
*/

arch('no quedan funciones de depuración')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'die'])
    ->not->toBeUsed();

arch('env() no se usa en el código de la app (solo en config/)')
    ->expect('env')
    ->not->toBeUsedIn(['App', 'Database']);

arch('los modelos extienden de Eloquent')
    ->expect('App\Models')
    ->toExtend(Model::class);

arch()->preset()->php();
arch()->preset()->security();
