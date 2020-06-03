<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use VCComponent\Laravel\Redirecter\Entities\RedirectUrls;

// $badurls = RedirectUrls::get();
// foreach ($badurls as $badurl) {
//     Route::get($badurl->from_url, function () use ($badurl) {
//         return Redirect::to($badurl->to_url, 301);
//     });
// }
