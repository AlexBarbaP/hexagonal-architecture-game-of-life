<?php
declare(strict_types=1);

namespace Tests\Integration\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Model\Entities\GameStatus;
use Domain\Model\Entities\GameStatusId;

class DoctrineGameStatusFixtureLoader implements FixtureInterface
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $gameStatusId = GameStatusId::create();
        $status       = serialize([
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
        ]);

        $gameStatus = new GameStatus($gameStatusId, $status);

        $manager->persist($gameStatus);
        $manager->flush();
    }
}
