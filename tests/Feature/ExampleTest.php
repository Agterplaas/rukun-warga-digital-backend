<?php

use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(404);
});
