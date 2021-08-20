<?php

namespace VCComponent\Laravel\Redirector\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Redirector\Entities\RedirectUrls;
use VCComponent\Laravel\Redirector\Repositories\RedirectRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class AccountantRepositoryEloquent.
 */
class RedirectRepositoryEloquent extends BaseRepository implements RedirectRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RedirectUrls::class;
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function findById($request, $id)
    {
        if (!$this->getEntity()->find($id)) {
            throw new NotFoundException('Riderrect_urls');
        }
        $tag = $this->find($id);
        return $tag;
    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
