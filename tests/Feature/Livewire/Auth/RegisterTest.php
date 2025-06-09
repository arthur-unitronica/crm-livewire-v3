<?php

use App\Livewire\Auth\Register;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should be able to register a new user in the system', function () {
    Livewire::test(Register::class)
        ->set('name', 'Arthur')
        ->set('email', 'arthur@email.com')
        ->set('email_confirmation', 'arthur@email.com')
        ->set('password', '123')
        ->call('submit')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'Arthur',
        'email' => 'arthur@email.com'
    ]);

    assertDatabaseCount('users', 1);
});

test('validation rules', function ($f) {
    Livewire::test(Register::class)
        ->set($f->field, $f->value)
        ->call('submit')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required' => (object)['field' => 'name', 'value' => '', 'rule' => ''],
    'name::max:255' => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],

    'email::email' => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => ''],
    'email::max:255' => (object)['field' => 'name', 'value' => str_repeat('*' . '@email.com', 256), 'rule' => 'max'],
    'email::confirmed' => (object)['field' => 'email', 'value' => 'arthur@email.com', 'rule' => 'confirmed'],

    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => '']
]);
