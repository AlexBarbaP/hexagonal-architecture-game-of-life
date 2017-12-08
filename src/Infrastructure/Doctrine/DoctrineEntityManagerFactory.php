<?php
declare(strict_types=1);

namespace Infrastructure\Doctrine;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Infrastructure\Doctrine\Domain\Model\Entities\DoctrineGameStatusId;

class DoctrineEntityManagerFactory
{
    /** @var array */
    private $entityPaths = [];

    /** @var array */
    private $connectionParams = [];

    /**
     * @param array $entityPaths
     * @param array $connectionParams
     *
     * @throws DBALException
     */
    public function __construct(array $entityPaths, array $connectionParams)
    {
        $this->entityPaths      = $entityPaths;
        $this->connectionParams = $connectionParams;

        if (!Type::hasType('GameStatusId')) {
            Type::addType('GameStatusId', DoctrineGameStatusId::class);
        }
    }

    /**
     * @return EntityManager
     *
     * @throws ORMException
     */
    public function getEntityManager()
    {
        $isDevMode = true;
        $proxyDir  = null;
        $cache     = new ArrayCache();

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
