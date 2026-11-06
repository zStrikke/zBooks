<?php

namespace App\Policies;

use App\Models\User;

/**
 * Quién puede ver qué de un miembro (#93, ADR-0010).
 */
class UserPolicy
{
    /**
     * El perfil (/u/{username}) no es público: solo lo ve el propio miembro.
     * Con la #37 lo verán también sus amigos (amistad aceptada).
     */
    public function viewProfile(User $viewer, User $member): bool
    {
        return $viewer->is($member);
    }
}
