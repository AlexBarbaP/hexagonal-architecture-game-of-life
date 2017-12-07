<?php
declare(strict_types=1);

namespace Infrastructure\Doctrine;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL\Types\Type;
use Infrastructure\Doctrine\Domain\Model\Entities\DoctrineGameStatusId;

class DoctrineEntityManagerFactory
{
    /** @var array */
    private $entityPaths = [];

    /** @var array */
    private $connectionParams = [];

    /**
     * DoctrineEntityManagerFactory constructor.
     *
     * @param array $entityPaths
     * @param array $connectionParams
     */
    public function __construct(array $entityPaths, array $connectionParams)
    {
        $this->entityPaths      = $entityPaths;
        $this->connectionParams = $connectionParams;
    }

    /**
     * Returns Doctrine EntityManager instance
     */
    public function getEntityManager()
    {
        Type::addType('GameStatusId', DoctrineGameStatusId::class);

        $isDevMode    = true;
        $proxyDir     = null;
        $cache        = new ArrayCache();

        $config = Setup::createXMLMetadataConfiguration(
            $this->entityPaths,
            $isDevMode,
            $proxyDir,
            $cache
        );

        $entityManager = EntityManager::create($this->connectionParams, $config);

        return $entityManager;
    }
}
