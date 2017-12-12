<?php
declare(strict_types=1);

namespace Tests\Integration;

use Application\Factories\CommandBusFactory;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;

class IntegrationTestAbstract extends TestCase
{
    /** @var CommandBus */
    protected $commandBus;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setupCommandBus();
    }

    /**
     *
     */
    private function setupCommandBus(): void
    {
        $commandBusFactory = new CommandBusFactory();

        $this->commandBus = $commandBusFactory->create();
    }
}
