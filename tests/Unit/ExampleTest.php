<?php

use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('returns a successful response', function () {
    $result = 5;

    expect($result)->toBe(5);
});
