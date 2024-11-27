<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Volt\Volt;

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Volt::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class);
});



test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Volt::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $component = Volt::test('pages.auth.reset-password', ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('resetPassword');

        $component
            ->assertRedirect('/login')
            ->assertHasNoErrors();

        return true;
    });
});
