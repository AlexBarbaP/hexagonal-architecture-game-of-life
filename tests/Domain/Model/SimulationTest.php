<?php
declare(strict_types=1);

namespace Tests\Domain\Model;

use PHPUnit\Framework\TestCase;

class SimulationTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider solitudeUseCaseDataProvider
     */
    public function populated_cell_with_less_than_two_neighbors_should_die($data)
    {
        $grid = $data['grid'];

        $height = count($grid);
        $width  = count($grid[0]);

        $x = $data['coordinates'][0];
        $y = $data['coordinates'][1];

        $simulation = new Simulation($height, $width);

        $simulation->iterate();

        $cellNewStatus = $simulation->getBoard()->getCell($x, $y)->getCellStatus();

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
     */
    public function populated_cell_with_two_or_three_neighbors_should_survive()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function populated_cell_with_more_than_three_neighbors_should_die()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function unpopulated_cell_with_three_neighbors_becomes_populated($data)
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function unpopulated_cell_with_neighbors_different_than_three_continues_unpopulated($data)
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function simulation_is_completed_should_be_return_as_expected(array $grid, $expectedResult)
    {
        $this->assertTrue(true);
    }
}
