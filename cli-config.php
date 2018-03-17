<?php

use Application\Config\Config;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use App\Infrastructure\Doctrine\DoctrineEntityManagerFactory;

require_once 'vendor/autoload.php';

$config = Config::getConfig(Config::PROD_ENV);

$doctrineEntityManagerFactory = new DoctrineEntityManagerFactory($config[Config::ENTITY_PATHS], $config[Config::MASTER_DB_PARAMS]);
$entityManager                = $doctrineEntityManagerFactory->getEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
