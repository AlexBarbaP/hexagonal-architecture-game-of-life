<?php
declare(strict_types=1);

namespace Tests\Integration\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Domain\Model\Entities\SimulationStatus;
use App\Domain\Model\Entities\SimulationStatusId;

class DoctrineSimulationStatusFixtureLoader implements FixtureInterface
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $simulationStatusId = SimulationStatusId::create();
        $status       = serialize([
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1],
        ]);

        $simulationStatus = new SimulationStatus($simulationStatusId, $status);

        $manager->persist($simulationStatus);
        $manager->flush();
    }
}
