<?php
declare(strict_types=1);

namespace Domain\Model\PopulateStrategies;

use Domain\Exception\EntityNotFoundException;
use Domain\Exception\InvalidSizeException;
use Domain\Model\Cell;
use Domain\Model\CellStatus;
use Domain\Model\Coordinate;
use Domain\Model\Entities\SimulationStatusId;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;

final class FromStoragePopulateStrategy implements PopulateStrategyInterface
{
    /** @var SimulationStatusRepositoryInterface */
    private $simulationStatusRepository;

    /** @var SimulationStatusId */
    private $simulationStatusId;

    /**
     * @param SimulationStatusRepositoryInterface $simulationStatusRepository
     * @param SimulationStatusId                  $simulationStatusId
     */
    public function __construct(SimulationStatusRepositoryInterface $simulationStatusRepository, SimulationStatusId $simulationStatusId)
    {
        $this->simulationStatusRepository = $simulationStatusRepository;
        $this->simulationStatusId         = $simulationStatusId;
    }

    /**
     * @param array $boardGrid
     *
     * @return array
     *
     * @throws EntityNotFoundException
     * @throws InvalidSizeException
     */
    public function populate(array $boardGrid): array
    {
        $simulationStatus = $this->simulationStatusRepository->find($this->simulationStatusId);

        $simulationStatusArray = unserialize($simulationStatus->getStatus());

        $populatedGrid = [];
        for ($y = 0; $y < count($boardGrid); $y++) {
            $populatedGrid[] = [];

            for ($x = 0; $x < count($boardGrid[$y]); $x++) {
                $populatedGrid[count($populatedGrid) - 1][] = $this->populateCell($simulationStatusArray, $y, $x);
            }
        }

        return $populatedGrid;
    }

    /**
     * @param array $simulationStatusArray
     * @param int   $y
     * @param int   $x
     *
     * @return Cell
     *
     * @throws InvalidSizeException
     */
    private function populateCell(array $simulationStatusArray, int $y, int $x): Cell
    {
        if (!isset($simulationStatusArray[$y][$x])) {
            throw new InvalidSizeException();
        }

        $coordinate = new Coordinate($y, $x);
        $cellStatus = new CellStatus($simulationStatusArray[$y][$x]);

        return new Cell($coordinate, $cellStatus);
    }
}
