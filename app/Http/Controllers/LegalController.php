<?php

namespace App\Http\Controllers;

use App\Legal\LegalDocument;
use Illuminate\Contracts\View\View;

/**
 * Aviso legal y política de privacidad.
 *
 * El texto de cada página está en Markdown, un fichero por idioma
 * (`resources/markdown/legal/{locale}/`); si falta el idioma activo, se usa el español.
 */
class LegalController extends Controller
{
    public function notice(): View
    {
        return $this->show('notice');
    }

    public function privacy(): View
    {
        return $this->show('privacy');
    }

    private function show(string $page): View
    {
        return view('legal.page', ['document' => LegalDocument::load($page, app()->getLocale())]);
    }
}
