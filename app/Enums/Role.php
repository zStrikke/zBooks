<?php

namespace App\Enums;

/**
 * Roles base de la aplicación (#15). Sus valores son los nombres en la tabla
 * `roles` de spatie/laravel-permission, que acepta el enum directamente:
 * `$user->assignRole(Role::Member)`, `$user->hasRole(Role::Admin)`.
 */
enum Role: string
{
    case Member = 'member';
    case Moderator = 'moderator';
    case Admin = 'admin';
}
