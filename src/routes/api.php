<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'admin'], function ($api) {

        $api->get('redirect/all', 'VCComponent\Laravel\Redirecter\Http\Controllers\Api\Admin\RedirectController@list');
        $api->resource('redirect', 'VCComponent\Laravel\Redirecter\Http\Controllers\Api\Admin\RedirectController');
    });
});
