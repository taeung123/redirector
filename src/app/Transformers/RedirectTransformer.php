<?php

namespace VCComponent\Laravel\Redirector\Transformers;

use League\Fractal\TransformerAbstract;

class RedirectTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'         => (int) $model->id,
            'from_url'   => $model->from_url,
            'to_url'     => $model->to_url,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
