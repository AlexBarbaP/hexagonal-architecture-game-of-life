<?php

use Infrastructure\Doctrine\DoctrineEntityManagerFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'vendor/autoload.php';


// paths to entities
$entityPaths = [
    realpath(__DIR__ . '/src/Infrastructure/Doctrine/Domain/Model/Entities'),
];

// the connection configuration
$connectionParams = [
    'host'     => 'mysql-master',
    'driver'   => 'pdo_mysql',
    'dbname'   => 'gol_hexagonal',
    'user'     => 'gol_hexagonal',
    'password' => 'gol_hexagonal',
    'charset'  => 'utf8',
];

$doctrineEntityManagerFactory = new DoctrineEntityManagerFactory($entityPaths, $connectionParams);
$entityManager                = $doctrineEntityManagerFactory->getEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
