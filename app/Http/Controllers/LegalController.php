<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Aviso legal y política de privacidad.
 *
 * Cada texto es una vista por idioma (`legal/{locale}/…`), porque un texto
 * legal no se traduce frase a frase. Si falta el idioma activo, se usa el español.
 */
class LegalController extends Controller
{
    public function notice(): View
    {
        return $this->localized('notice');
    }

    public function privacy(): View
    {
        return $this->localized('privacy');
    }

    private function localized(string $page): View
    {
        return view()->first([
            'legal.'.app()->getLocale().'.'.$page,
            'legal.es.'.$page,
        ]);
    }
}
