<?php

namespace VCComponent\Laravel\Redirector\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface Repository.
 */
interface RedirectRepository extends RepositoryInterface
{

    public function getEntity();
}
