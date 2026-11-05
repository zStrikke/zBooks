<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Reglas del nombre de usuario (#10).
 *
 * - De 3 a 20 letras (sin acentos), números o guion bajo.
 * - Se guarda tal como se escribe (zStrikke), pero es único sin distinguir
 *   mayúsculas: zstrikke y ZSTRIKKE son el mismo usuario.
 * - No admite los nombres reservados de config('zbooks.reserved_usernames').
 */
class Username implements ValidationRule
{
    public const MIN = 3;

    public const MAX = 20;

    /**
     * @return list<string|ValidationRule>
     */
    public static function rules(): array
    {
        return ['required', 'string', 'min:'.self::MIN, 'max:'.self::MAX, 'regex:/^[A-Za-z0-9_]+$/', new self];
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lower = mb_strtolower((string) $value);

        /** @var list<string> $reserved */
        $reserved = config('zbooks.reserved_usernames', []);

        if (in_array($lower, $reserved, true)) {
            $fail('validation.username_reserved')->translate();

            return;
        }

        if (User::whereRaw('lower(username) = ?', [$lower])->exists()) {
            $fail('validation.unique')->translate();
        }
    }
}
