<?php

namespace VCComponent\Laravel\Redirecter\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;
use VCComponent\Laravel\Vicoders\Core\Validators\ValidatorInterface;

class RedirectValidator extends AbstractValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'from_url' => ['required','unique:redirect_urls'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'from_url' => 'required',
        ],

    ];
}
