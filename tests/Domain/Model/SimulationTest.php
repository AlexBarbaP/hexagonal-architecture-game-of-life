<?php
declare(strict_types=1);

namespace Tests\Domain\Model;

use PHPUnit\Framework\TestCase;

class SimulationTest extends TestCase
{
    /**
     * @test
     */
    public function populated_cell_with_less_than_two_neighbors_should_die()
    {
        $this->assertTrue(true);
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
    public function unpopulated_cell_with_three_neighbors_becomes_populated()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function unpopulated_cell_with_neighbors_different_than_three_continues_unpopulated()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function simulation_is_completed_should_be_return_as_expected()
    {
        $this->assertTrue(true);
    }
}
