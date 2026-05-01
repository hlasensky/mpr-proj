<?php

/**
 * These tests are for enforcing architectural rules and conventions.
 */

test('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

test('models are using HasFactory trait')
    ->expect('App\Models')
    ->toUse('Illuminate\Database\Eloquent\Factories\HasFactory');

test('enums are backed')
    ->expect('App\Enums')
    ->toBeEnums()
    ->toBeBacked();