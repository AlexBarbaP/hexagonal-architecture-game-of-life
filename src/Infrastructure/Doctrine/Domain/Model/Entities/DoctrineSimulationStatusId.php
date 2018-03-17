<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Domain\Model\Entities;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use App\Domain\Model\Entities\SimulationStatusId;

class DoctrineSimulationStatusId extends GuidType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'SimulationStatusId';
    }

    /**
     * @param $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return SimulationStatusId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return SimulationStatusId::create($value);
    }
}
