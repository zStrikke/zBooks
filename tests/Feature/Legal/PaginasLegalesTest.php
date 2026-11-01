<?php

use App\Legal\PrivacyPolicy;

it('muestra la política de privacidad con su versión', function () {
    $this->get(route('legal.privacy'))
        ->assertOk()
        ->assertSee('Política de privacidad')
        ->assertSee('Versión '.PrivacyPolicy::currentVersion());
});

it('muestra el aviso legal', function () {
    $this->get(route('legal.notice'))
        ->assertOk()
        ->assertSee('Aviso legal')
        ->assertSee('proyecto personal, no comercial');
});

it('muestra huecos mientras no se rellenan los datos del responsable', function () {
    $this->get(route('legal.privacy'))
        ->assertSee('[nombre]')
        ->assertSee('[email de contacto]')
        ->assertSee('[país]');
});

it('usa los datos del responsable de la configuración', function () {
    config([
        'legal.controller.name' => 'Persona Responsable',
        'legal.controller.email' => 'privacidad@zbooks.test',
        'legal.controller.country' => 'España',
    ]);

    $this->get(route('legal.notice'))
        ->assertSee('Persona Responsable')
        ->assertSee('privacidad@zbooks.test')
        ->assertSee('legislación de España')
        ->assertDontSee('[nombre]');
});

it('enlaza los textos legales desde el pie de página', function () {
    $this->get(route('home'))
        ->assertSee(route('legal.privacy'))
        ->assertSee(route('legal.notice'));
});

it('no interpreta como HTML los datos de la configuración', function () {
    config(['legal.controller.name' => '<script>alert(1)</script>']);

    $this->get(route('legal.privacy'))
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;script&gt;', false);
});

it('pone el título y la descripción de la página a partir del Markdown', function () {
    $this->get(route('legal.notice'))
        ->assertSee('<title>Aviso legal | zBooks</title>', false)
        ->assertSee('Quién está detrás de zBooks y qué condiciones aceptas al usarlo.');
});

it('enlaza la política de privacidad desde el aviso legal', function () {
    $this->get(route('legal.notice'))->assertSee('href="'.route('legal.privacy').'"', false);
});

it('cae al español si no hay texto en el idioma activo', function () {
    app()->setLocale('ca');

    $this->get(route('legal.privacy'))->assertOk()->assertSee('Política de privacidad');
});
