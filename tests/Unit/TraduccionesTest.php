<?php

/*
| Web traducible (#43, ADR-0011): los textos se escriben en inglés con __() y
| cada uno necesita su traducción al español. Este test falla si alguno no la tiene.
*/

/**
 * Textos de __('…') y @lang('…') en las vistas y el código de la app.
 *
 * @return array<string, list<string>> texto => ficheros donde aparece
 */
function textosTraducibles(): array
{
    $textos = [];
    $patron = '/(?:__|@lang)\(\s*(?:\'((?:[^\'\\\\]|\\\\.)*)\'|"((?:[^"\\\\]|\\\\.)*)")/';
    $raiz = dirname(__DIR__, 2);

    foreach (['resources/views', 'app'] as $carpeta) {
        $ficheros = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($raiz.'/'.$carpeta));
        foreach ($ficheros as $fichero) {
            if ($fichero->getExtension() !== 'php') {
                continue;
            }
            preg_match_all($patron, (string) file_get_contents($fichero->getPathname()), $coincidencias, PREG_SET_ORDER);
            foreach ($coincidencias as $c) {
                $texto = stripslashes($c[1] !== '' ? $c[1] : ($c[2] ?? ''));
                $textos[$texto][] = str_replace($raiz.'/', '', $fichero->getPathname());
            }
        }
    }

    return $textos;
}

it('cada texto de la web tiene su traducción al español', function () {
    $raiz = dirname(__DIR__, 2);
    /** @var array<string, string> $es */
    $es = json_decode((string) file_get_contents($raiz.'/lang/es.json'), true, flags: JSON_THROW_ON_ERROR);

    $sinTraducir = [];
    foreach (textosTraducibles() as $texto => $ficheros) {
        // Claves de fichero (validation.required, auth.failed…): viven en lang/es/<fichero>.php
        if (preg_match('/^([a-z_]+)\.([a-z_.]+)$/', $texto, $m) === 1) {
            $grupo = $raiz.'/lang/es/'.$m[1].'.php';
            $existe = is_file($grupo) && data_get(require $grupo, $m[2]) !== null;
        } else {
            $existe = array_key_exists($texto, $es);
        }

        if (! $existe) {
            $sinTraducir[] = "«{$texto}» (".implode(', ', array_unique($ficheros)).')';
        }
    }

    expect($sinTraducir)->toBe([], "Faltan en lang/es.json:\n".implode("\n", $sinTraducir));
});

it('encuentra los textos de las vistas', function () {
    // Si el patrón dejara de encontrar textos, el test de arriba pasaría sin comprobar nada
    expect(textosTraducibles())->toHaveKeys(['My account', 'How it works', 'Member file']);
});
