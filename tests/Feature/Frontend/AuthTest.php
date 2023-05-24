<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

it('renders the login page', function () {
    $response = $this->get('/login');

    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Auth/Login');
    });
});

it('renders the signup page', function () {
    $response = $this->get('/signup');

    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Auth/Signup');
    });
});
