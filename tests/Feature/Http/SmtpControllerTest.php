<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use Tests\DatabaseTestCase;

class SmtpControllerTest extends DatabaseTestCase
{
    public function testGetEventHtmlByUuid()
    {
        $event = $this->createEvent('smtp', ['foo1' => 'bar1', 'html' => '<h1>Hello world</h1>']);

        $this->get(route('smtp.show.html', $event->getUuid()->toString()))
            ->assertOk()
            ->assertSeeText('Hello world');
    }

    public function testGetEventHtmlShouldReturn404IfNotFound()
    {
        $this->getJson(route('smtp.show.html', Uuid::generate()->toString()))
            ->assertNotFound();
    }
}
