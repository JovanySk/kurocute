<?php

namespace Tests;

use Database\Seeders\CatalogosSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** Con RefreshDatabase se cargan los catalogos base (perfiles, estados, formas de pago). */
    protected bool $seed = true;

    protected string $seeder = CatalogosSeeder::class;
}
