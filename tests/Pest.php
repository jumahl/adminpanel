<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class)
    ->in('Feature', 'Unit');

uses(RefreshDatabase::class)
    ->in('Feature', 'Unit');
