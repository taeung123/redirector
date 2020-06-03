<?php

namespace VCComponent\Laravel\Redirecter\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface Repository.
 */
interface RedirectRepository extends RepositoryInterface
{

    public function getEntity();
}
