<?php

declare(strict_types=1);

namespace Application\Domain\ValueObjects;

use App\Domain\ValueObjects\Uuid;
use Tests\TestCase;

class UuidTest extends TestCase
{
    public function testGeneratesUuid()
    {
        $uuid = Uuid::generate();

        $this->assertTrue(\Ramsey\Uuid\Uuid::isValid($uuid->toString()));
    }

    public function testCreatesFromUuidInterface()
    {
        $uuid = new Uuid($uuidv4 = \Ramsey\Uuid\Uuid::uuid4());

        $this->assertSame($uuidv4->toString(), $uuid->toString());
    }

    public function testCreatesFromString()
    {
        $uuidv4 = \Ramsey\Uuid\Uuid::uuid4();

        $uuid = Uuid::fromString($uuidv4->toString());
        $this->assertSame($uuidv4->toString(), $uuid->toString());
    }

    public function testInvalidUuidStringShouldThrowAnException()
    {
        $this->expectException(\Ramsey\Uuid\Exception\InvalidUuidStringException::class);
        $this->expectErrorMessage('Invalid UUID string: test');
        Uuid::fromString('test');
    }

    public function testInConvertsToString()
    {
        $uuid = Uuid::generate();

        $this->assertSame($uuid->toString(), (string) $uuid);
    }
}
