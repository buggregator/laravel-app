<?php

namespace Modules\Project\Application\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    public $collects = ProjectResource::class;
}
