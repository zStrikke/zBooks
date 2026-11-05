<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Users\AvatarImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Editar el perfil público (#17): avatar y bio. La usa la web y, más adelante,
 * la API (#73): una sola lógica para los dos accesos (ADR-0012).
 */
class UpdateProfile
{
    public const DISK = 'public';

    public const BIO_MAX = 500;

    /**
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        /** @var array{bio?: string|null, avatar?: UploadedFile|null} $data */
        $data = Validator::make($input, self::rules())->validateWithBag('updateProfile');

        $bio = trim((string) ($data['bio'] ?? ''));
        $user->forceFill(['bio' => $bio === '' ? null : $bio]);

        if (($data['avatar'] ?? null) instanceof UploadedFile) {
            $this->replaceAvatar($user, $data['avatar']);
        }

        $user->save();
    }

    public function removeAvatar(User $user): void
    {
        $this->deleteStoredAvatar($user);
        $user->forceFill(['avatar' => null])->save();
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'bio' => ['nullable', 'string', 'max:'.self::BIO_MAX],
            // Tope de dimensiones: una foto enorme agotaría la memoria al descomprimirla
            'avatar' => [
                'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048',
                'dimensions:max_width=6000,max_height=6000',
            ],
        ];
    }

    private function replaceAvatar(User $user, UploadedFile $file): void
    {
        $ruta = 'avatars/'.Str::random(40).'.webp';
        Storage::disk(self::DISK)->put($ruta, AvatarImage::fromUpload($file));

        $this->deleteStoredAvatar($user);
        $user->forceFill(['avatar' => $ruta]);
    }

    private function deleteStoredAvatar(User $user): void
    {
        if ($user->avatar) {
            Storage::disk(self::DISK)->delete($user->avatar);
        }
    }
}
