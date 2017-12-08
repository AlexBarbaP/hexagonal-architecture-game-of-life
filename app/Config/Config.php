<?php
declare(strict_types=1);

namespace Application\Config;

/**
 * Class Config
 *
 * @package Application\Config
 */
class Config
{
    /** @var string */
    public const TEST_ENV = 'test';

    /** @var string */
    public const PROD_ENV = 'prod';

    /** @var string */
    public const ENTITY_PATHS = 'entity_paths';

    /** @var string */
    public const MASTER_DB_PARAMS = 'master_db_connection_params';

    /** @var string */
    public const SLAVE_DB_PARAMS = 'slave_db_connection_params';

    /** @var array */
    private const CONFIGS = [
        self::TEST_ENV => [
            self::ENTITY_PATHS     => [
                __DIR__ . '/../../src/Infrastructure/Doctrine/Domain/Model/Entities',
            ],
            self::MASTER_DB_PARAMS => [
                'host'     => 'mysql-master',
                'driver'   => 'pdo_mysql',
                'dbname'   => 'gol_hexagonal',
                'user'     => 'gol_hexagonal',
                'password' => 'gol_hexagonal',
                'charset'  => 'utf8',
            ],
            self::SLAVE_DB_PARAMS  => [
                'host'     => 'mysql-slave',
                'driver'   => 'pdo_mysql',
                'dbname'   => 'gol_hexagonal',
                'user'     => 'gol_hexagonal',
                'password' => 'gol_hexagonal',
                'charset'  => 'utf8',
            ],
        ],
        self::PROD_ENV => [
            self::ENTITY_PATHS     => [
                __DIR__ . '/../../src/Infrastructure/Doctrine/Domain/Model/Entities',
            ],
            self::MASTER_DB_PARAMS => [
                'host'     => 'mysql-master',
                'driver'   => 'pdo_mysql',
                'dbname'   => 'gol_hexagonal',
                'user'     => 'gol_hexagonal',
                'password' => 'gol_hexagonal',
                'charset'  => 'utf8',
            ],
            self::SLAVE_DB_PARAMS  => [
                'host'     => 'mysql-slave',
                'driver'   => 'pdo_mysql',
                'dbname'   => 'gol_hexagonal',
                'user'     => 'gol_hexagonal',
                'password' => 'gol_hexagonal',
                'charset'  => 'utf8',
            ],
        ],
    ];

    /**
     * Gets config settings by environment
     *
     * @param string $environment
     *
     * @return array
     */
    public static function getConfig(string $environment): array
    {
        return self::CONFIGS[$environment];
    }
}
