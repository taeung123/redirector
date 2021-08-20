<?php

namespace VCComponent\Laravel\Redirector\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use VCComponent\Laravel\Redirector\Repositories\RedirectRepository;
use VCComponent\Laravel\Redirector\Transformers\RedirectTransformer;
use VCComponent\Laravel\Redirector\Validators\RedirectValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

class RedirectController extends ApiController
{
    protected $repository;
    protected $validator;
    public function __construct(RedirectRepository $repository, RedirectValidator $validator)
    {
        $this->repository  = $repository;
        $this->entity      = $repository->getEntity();
        $this->validator   = $validator;
        $this->transformer = RedirectTransformer::class;
    }
    public function index(Request $request)
    {
        $query = $this->entity;

        $query    = $this->applyConstraintsFromRequest($query, $request);
        $query    = $this->applySearchFromRequest($query, ['name'], $request);
        $query    = $this->applyOrderByFromRequest($query, $request);
        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $tags     = $query->paginate($per_page);

        return $this->response->paginator($tags, new $this->transformer());
    }
    public function store(Request $request)
    {
        $this->validator->isValid($request, 'RULE_CREATE');
        $data = $request->all();
        $tag  = $this->repository->create($data);

        return $this->response->item($tag, new $this->transformer());
    }
    public function show(Request $request, $id)
    {
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        $tag = $this->repository->findById($request, $id);

        return $this->response->item($tag, $transformer);
    }
    function list(Request $request) {
        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $tags = $query->get();

        return $this->response->collection($tags, new $this->transformer());
    }

    public function update(Request $request, $id)
    {
        $this->validator->isValid($request, 'RULE_UPDATE');
        $data = $request->all();
        $tag  = $this->repository->update($data, $id);

        return $this->response->item($tag, new $this->transformer());
    }

    public function destroy($id)
    {
        $tag = $this->entity->find($id);
        if (!$tag) {
            throw new NotFoundException('tag');
        }

        $this->repository->delete($id);

        return $this->success();
    }
}
