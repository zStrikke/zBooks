<?php

use Illuminate\Support\Facades\Redis;

it('informa de que la API funciona', function () {
    $this->getJson('/api/v1/health')
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'status' => 'ok',
                'version' => config('app.version'),
                'checks' => ['database' => 'ok', 'redis' => 'ok'],
            ],
        ]);
});

it('responde 503 si falla una dependencia', function () {
    config(['database.redis.default.port' => 1]);
    Redis::purge();

    $this->getJson('/api/v1/health')
        ->assertServiceUnavailable()
        ->assertJsonPath('data.status', 'degraded')
        ->assertJsonPath('data.checks.redis', 'fail')
        ->assertJsonPath('data.checks.database', 'ok');
});

it('documenta la API en /docs/api', function () {
    app()['env'] = 'local'; // Scramble solo abre la documentación en local

    $this->get('/docs/api')->assertOk();

    $this->getJson('/docs/api.json')
        ->assertOk()
        ->assertJsonPath('info.title', 'zBooks API')
        ->assertJsonPath('servers.0.url', url('/api/v1'))
        ->assertJsonStructure(['paths' => ['/health' => ['get']]]);
});
