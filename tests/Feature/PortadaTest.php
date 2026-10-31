<?php

it('muestra la portada', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Deja un libro donde alguien lo encuentre.')
        ->assertSee('Cómo funciona');
});

it('muestra la versión de la aplicación en el pie', function () {
    $this->get('/')->assertSee(config('app.version'));
});

it('normaliza el código buscado a mayúsculas', function () {
    $this->get(route('zbid.buscar', ['codigo' => '  zb-4k7q-92xm ']))
        ->assertOk()
        ->assertSee('ZB-4K7Q-92XM');
});

it('recorta el código buscado a 20 caracteres', function () {
    $this->get(route('zbid.buscar', ['codigo' => str_repeat('a', 50)]))
        ->assertOk()
        ->assertSee(str_repeat('A', 20))
        ->assertDontSee(str_repeat('A', 21));
});

it('escapa el código buscado', function () {
    $this->get(route('zbid.buscar', ['codigo' => '<script>']))
        ->assertOk()
        ->assertDontSee('<SCRIPT>', escape: false);
});
