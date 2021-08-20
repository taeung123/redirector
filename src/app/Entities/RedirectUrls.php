<?php

namespace VCComponent\Laravel\Redirector\Entities;

use Illuminate\Database\Eloquent\Model;

class RedirectUrls extends Model
{
    protected $fillable = [
        'from_url',
        'to_url',

    ];

}
