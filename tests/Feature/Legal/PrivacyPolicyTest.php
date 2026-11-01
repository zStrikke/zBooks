<?php

use App\Legal\PrivacyPolicy;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

it('exige aceptar la política en el formulario', function (mixed $valor, bool $pasa) {
    $validator = Validator::make([PrivacyPolicy::FIELD => $valor], PrivacyPolicy::rules());

    expect($validator->passes())->toBe($pasa);
})->with([
    'marcada' => [true, true],
    'marcada desde un formulario' => ['on', true],
    'sin marcar' => [false, false],
    'ausente' => [null, false],
]);

it('guarda cuándo y qué versión aceptó el usuario', function () {
    $this->freezeSecond();
    $user = User::factory()->create();

    PrivacyPolicy::recordAcceptance($user);

    expect($user->fresh())
        ->privacy_accepted_at->toEqual(now())
        ->privacy_version->toBe(PrivacyPolicy::currentVersion());
});

it('deja de dar por aceptada la política cuando cambia de versión', function () {
    $user = User::factory()->create();
    PrivacyPolicy::recordAcceptance($user);

    expect(PrivacyPolicy::hasAccepted($user))->toBeTrue();

    config(['legal.privacy_version' => '2027-01-01']);

    expect(PrivacyPolicy::hasAccepted($user))->toBeFalse();
});

it('no da por aceptada la política a quien nunca la aceptó', function () {
    expect(PrivacyPolicy::hasAccepted(User::factory()->create()))->toBeFalse();
});
