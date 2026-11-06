<?php

namespace App\Users;

use App\Models\User;

/**
 * Cifras del perfil (#16). Solo números, nunca qué libros (ADR-0010).
 *
 * Las tablas de libros y de liberaciones llegan en la v0.3.0 (#20) y la v0.4.0 (#21):
 * hasta entonces todos los miembros tienen 0, que es su valor real. Cada épica
 * sustituye aquí su cero por la consulta.
 */
readonly class ProfileStats
{
    public function __construct(
        public int $registered,
        public int $released,
        public int $caught,
    ) {}

    public static function for(User $user): self
    {
        return new self(registered: 0, released: 0, caught: 0);
    }
}
