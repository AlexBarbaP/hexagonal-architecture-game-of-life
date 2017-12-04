<?php
declare(strict_types=1);

namespace Tests\Domain\Model;

use Domain\Model\Cell;
use Domain\Model\Coordinate;
use Domain\Model\Game;
use Domain\Model\PopulatorStrategies\FixedPopulateStrategy;
use Domain\Model\Size;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider solitudeUseCaseDataProvider
     */
    public function populated_cell_with_less_than_two_neighbors_should_die($data)
    {
        list($grid, $size, $coordinate) = $this->parseData($data);

        $cellNewStatus = $this->getCellNewStatus($grid, $size, $coordinate);

        $this->assertEquals(Cell::UNPOPULATED, $cellNewStatus);
    }

    public function solitudeUseCaseDataProvider()
    {
        return [
            'Size two, neighbors zero'   => [
                [
                    'grid'        => [
                        [1, 0],
                        [0, 0],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size two, neighbors one'    => [
                [
                    'grid'        => [
                        [1, 0],
                        [0, 1],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size three, neighbors zero' => [
                [
                    'grid'        => [
                        [0, 0, 0],
                        [0, 1, 0],
                        [0, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors one'  => [
                [
                    'grid'        => [
                        [0, 0, 1],
                        [0, 1, 0],
                        [0, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider equilibriumUseCaseDataProvider
     */
    public function populated_cell_with_two_or_three_neighbors_should_survive($data)
    {
        list($grid, $size, $coordinate) = $this->parseData($data);

        $cellNewStatus = $this->getCellNewStatus($grid, $size, $coordinate);

        $this->assertEquals(Cell::POPULATED, $cellNewStatus);
    }

    public function equilibriumUseCaseDataProvider()
    {
        return [
            'Size two, neighbors two'     => [
                [
                    'grid'        => [
                        [1, 1],
                        [1, 0],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size two, neighbors three'   => [
                [
                    'grid'        => [
                        [1, 1],
                        [1, 1],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size three, neighbors two'   => [
                [
                    'grid'        => [
                        [0, 1, 0],
                        [0, 1, 0],
                        [0, 1, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors three' => [
                [
                    'grid'        => [
                        [0, 1, 0],
                        [0, 1, 1],
                        [0, 1, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider overpopulationUseCaseDataProvider
     */
    public function populated_cell_with_more_than_three_neighbors_should_die($data)
    {
        list($grid, $size, $coordinate) = $this->parseData($data);

        $cellNewStatus = $this->getCellNewStatus($grid, $size, $coordinate);

        $this->assertEquals(Cell::UNPOPULATED, $cellNewStatus);
    }

    public function overpopulationUseCaseDataProvider()
    {
        return [
            'Size three, neighbors four'  => [
                [
                    'grid'        => [
                        [1, 0, 1],
                        [0, 1, 0],
                        [1, 0, 1],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors five'  => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [0, 1, 0],
                        [1, 1, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors six'   => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [0, 1, 1],
                        [1, 1, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors seven' => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors eight' => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider becomingPopulatedUseCaseDataProvider
     */
    public function unpopulated_cell_with_three_neighbors_becomes_populated($data)
    {
        list($grid, $size, $coordinate) = $this->parseData($data);

        $cellNewStatus = $this->getCellNewStatus($grid, $size, $coordinate);

        $this->assertEquals(Cell::POPULATED, $cellNewStatus);
    }

    public function becomingPopulatedUseCaseDataProvider()
    {
        return [
            'Size two, neighbors three'   => [
                [
                    'grid'        => [
                        [0, 1],
                        [1, 1],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size three, neighbors three' => [
                [
                    'grid'        => [
                        [0, 1, 0],
                        [1, 1, 0],
                        [0, 0, 1],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider stayingUnpopulatedUseCaseDataProvider
     */
    public function unpopulated_cell_with_neighbors_different_than_three_continues_unpopulated($data)
    {
        list($grid, $size, $coordinate) = $this->parseData($data);

        $cellNewStatus = $this->getCellNewStatus($grid, $size, $coordinate);

        $this->assertEquals(Cell::UNPOPULATED, $cellNewStatus);
    }

    public function stayingUnpopulatedUseCaseDataProvider()
    {
        return [
            'Size two, neighbors zero'    => [
                [
                    'grid'        => [
                        [0, 0],
                        [0, 0],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size two, neighbors one'     => [
                [
                    'grid'        => [
                        [0, 1],
                        [0, 0],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size two, neighbors two'     => [
                [
                    'grid'        => [
                        [0, 1],
                        [1, 0],
                    ],
                    'coordinates' => [0, 0],
                ],
            ],
            'Size three, neighbors zero'  => [
                [
                    'grid'        => [
                        [0, 0, 0],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors one'   => [
                [
                    'grid'        => [
                        [1, 0, 0],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors two'   => [
                [
                    'grid'        => [
                        [1, 1, 0],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors four'  => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [1, 0, 0],
                        [0, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors five'  => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [1, 0, 1],
                        [0, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors six'   => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [1, 0, 1],
                        [1, 0, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors seven' => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [1, 0, 1],
                        [1, 1, 0],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
            'Size three, neighbors eight' => [
                [
                    'grid'        => [
                        [1, 1, 1],
                        [1, 0, 1],
                        [1, 1, 1],
                    ],
                    'coordinates' => [1, 1],
                ],
            ],
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function parseData(array $data): array
    {
        $grid = $data['grid'];

        $height = count($grid);
        $width  = count($grid[0]);
        $size   = new Size($height, $width);

        $coordinate = new Coordinate($data['coordinates'][0], $data['coordinates'][1]);

        return [$grid, $size, $coordinate];
    }

    /**
     * @param array      $grid
     * @param Size       $size
     * @param Coordinate $coordinate
     *
     * @return int
     *
     * @throws \Domain\Exception\InvalidSizeException
     */
    private function getCellNewStatus(array $grid, Size $size, Coordinate $coordinate): int
    {
        $fixedPopulateStrategy = new FixedPopulateStrategy($grid);

        $game = new Game($size, $fixedPopulateStrategy);

        $game->iterate();

        $cellNewStatus = $game->getBoard()->getCell($coordinate)->getCellStatus();

        return $cellNewStatus();
    }
}
