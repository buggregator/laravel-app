<?php

declare(strict_types=1);

namespace Modules\Sentry\Interfaces\Http\Controllers;

use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Post;

class EnvelopeAction extends Controller
{
    #[Post(uri: 'api/{projectId}/envelope', name: 'sentry.event.envelope')]
    public function __invoke(): void
    {
        // Fake action
    }
}
