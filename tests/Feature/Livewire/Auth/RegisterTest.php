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
