<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Redirecter\Entities\RedirectUrls;

$factory->define(RedirectUrls::class, function (Faker $faker) {
    return [
        'from_url'  => $faker->url(),
        'to_url'    => $faker->url()
    ];
});