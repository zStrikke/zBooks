<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Email de verificación propio: en cola y con los textos de zBooks (#10).
     * Se fija el idioma de la petición: el worker de la cola no lo conoce.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify((new VerifyEmail)->locale(app()->getLocale()));
    }

    /**
     * Email para restablecer la contraseña (#11), en cola y en el idioma de la petición.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify((new ResetPassword($token))->locale(app()->getLocale()));
    }

    /**
     * Iniciales para el avatar por defecto (#16): la primera letra y la siguiente
     * mayúscula (zStrikke → ZS) o, si no hay, las dos primeras (lector_1984 → LE).
     */
    public function initials(): string
    {
        $resto = mb_substr($this->username, 1);
        $segunda = preg_match('/[A-Z]/', $resto, $m) === 1 ? $m[0] : mb_substr($resto, 0, 1);

        return mb_strtoupper(mb_substr($this->username, 0, 1).$segunda);
    }

    /**
     * Número de socio para la ficha del perfil: el id con ceros delante (42 → 000042).
     */
    public function memberNumber(): string
    {
        return str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'privacy_accepted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
