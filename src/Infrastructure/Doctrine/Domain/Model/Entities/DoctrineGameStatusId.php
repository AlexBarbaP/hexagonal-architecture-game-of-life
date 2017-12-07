<?php
declare(strict_types=1);

namespace Infrastructure\Doctrine\Domain\Model\Entities;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Domain\Model\Entities\GameStatusId;

class DoctrineGameStatusId extends GuidType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'GameStatusId';
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
     * @return GameStatusId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return GameStatusId::create($value);
    }
}
