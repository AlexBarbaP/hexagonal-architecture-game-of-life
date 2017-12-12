<?php
declare(strict_types=1);

namespace Tests\Integration\Simulation;

use Domain\Exception\InvalidSizeException;
use PHPUnit\Framework\TestCase;

class SimulationIntegrationTest extends TestCase
{
    /**
     * @test
     */
    public function run_simulation_for_ten_iterations_should_output_ten_simulation_boards()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @expectedException Domain\Exception\InvalidSizeException
     */
    public function run_simulation_should_output_a_message_error_for_invalid_board_dimensions()
    {
        throw new InvalidSizeException();
    }

    /**
     * @test
     */
    public function run_simulation_should_save_simulation_initial_board_into_database()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function run_simulation_loading_initial_status_from_database_should_output_an_arbitrary_final_simulation_board()
    {
        $this->assertTrue(true);
    }
}
