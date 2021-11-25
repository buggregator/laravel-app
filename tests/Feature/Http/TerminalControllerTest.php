<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Tests\DatabaseTestCase;

class TerminalControllerTest extends DatabaseTestCase
{
    public function testGetListAction()
    {
        $this->getJson(route('terminal'), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'component' => 'Terminal/Index',
            ]);
    }
}
