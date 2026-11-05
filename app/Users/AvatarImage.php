<?php

namespace App\Users;

use GdImage;
use Illuminate\Http\UploadedFile;
use RuntimeException;

/**
 * Avatar de un miembro (#17): recorta la foto al cuadrado central, la reduce a
 * 256 px y la guarda como WebP. Con GD, sin dependencias nuevas.
 *
 * Volver a codificar la imagen también descarta lo que traiga el fichero
 * además de los píxeles (metadatos EXIF con la ubicación, por ejemplo).
 */
class AvatarImage
{
    public const SIZE = 256;

    private const QUALITY = 85;

    /** Giro según la etiqueta Orientation del EXIF */
    private const ROTATION = [3 => 180, 6 => -90, 8 => 90];

    /**
     * @return string Bytes de la imagen WebP
     */
    public static function fromUpload(UploadedFile $file): string
    {
        $original = imagecreatefromstring((string) file_get_contents($file->getRealPath()));

        if ($original === false) {
            throw new RuntimeException('The uploaded file is not a readable image.');
        }

        $avatar = self::square(self::orient($original, $file));

        ob_start();
        imagewebp($avatar, null, self::QUALITY);

        return (string) ob_get_clean();
    }

    /**
     * El cuadrado central de la imagen, reducido a SIZE × SIZE.
     */
    private static function square(GdImage $original): GdImage
    {
        $lado = min(imagesx($original), imagesy($original));

        $avatar = imagecreatetruecolor(self::SIZE, self::SIZE);
        imagealphablending($avatar, false);
        imagesavealpha($avatar, true);
        imagecopyresampled(
            $avatar,
            $original,
            0,
            0,
            intdiv(imagesx($original) - $lado, 2),
            intdiv(imagesy($original) - $lado, 2),
            self::SIZE,
            self::SIZE,
            $lado,
            $lado,
        );

        return $avatar;
    }

    /**
     * Las fotos del móvil guardan el giro en el EXIF en vez de en los píxeles.
     */
    private static function orient(GdImage $image, UploadedFile $file): GdImage
    {
        $grados = self::ROTATION[self::exifOrientation($file)] ?? 0;
        $girada = $grados === 0 ? false : imagerotate($image, $grados, 0);

        return $girada === false ? $image : $girada;
    }

    private static function exifOrientation(UploadedFile $file): int
    {
        if ($file->getMimeType() !== 'image/jpeg') {
            return 1;
        }

        // Un JPEG sin EXIF o con el EXIF roto no es un error: se deja como está
        set_error_handler(static fn (): bool => true);
        $exif = exif_read_data($file->getRealPath());
        restore_error_handler();

        return is_array($exif) ? (int) ($exif['Orientation'] ?? 1) : 1;
    }
}
