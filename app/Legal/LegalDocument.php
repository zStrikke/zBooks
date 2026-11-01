<?php

namespace App\Legal;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Un texto legal escrito en Markdown, un fichero por idioma:
 * `resources/markdown/legal/{locale}/{page}.md`.
 *
 * El contenido vive en el Markdown y la presentación en la vista `legal.page`.
 * Los marcadores `{name}`, `{email}`, `{country}`, `{version}` y `{privacy_url}`
 * se rellenan desde `config/legal.php` y las rutas.
 */
class LegalDocument
{
    /** Idioma de reserva cuando no hay versión en el idioma activo */
    private const FALLBACK_LOCALE = 'es';

    private function __construct(private readonly string $markdown) {}

    public static function load(string $page, string $locale): self
    {
        foreach ([$locale, self::FALLBACK_LOCALE] as $candidate) {
            $path = resource_path("markdown/legal/{$candidate}/{$page}.md");

            if (is_file($path)) {
                return new self((string) file_get_contents($path));
            }
        }

        throw new NotFoundHttpException("No existe el texto legal «{$page}».");
    }

    /** El título es el encabezado `# …` de la primera línea. */
    public function title(): string
    {
        return trim(Str::of($this->markdown)->before("\n")->after('#'));
    }

    /** La descripción es el primer párrafo tras el título. */
    public function description(): string
    {
        return trim(Str::of($this->markdown)->after("\n")->trim()->before("\n"));
    }

    public function html(): HtmlString
    {
        // El HTML escrito dentro del Markdown se muestra como texto, nunca se interpreta
        return new HtmlString(Str::markdown($this->withValues(), [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]));
    }

    private function withValues(): string
    {
        $controller = (array) config('legal.controller');

        return strtr($this->markdown, [
            '{name}' => (string) ($controller['name'] ?? ''),
            '{email}' => (string) ($controller['email'] ?? ''),
            '{country}' => (string) ($controller['country'] ?? ''),
            '{version}' => PrivacyPolicy::currentVersion(),
            '{privacy_url}' => route('legal.privacy'),
        ]);
    }
}
