<?php
declare(strict_types=1);

namespace Domain\Model\PopulateStrategies;

use Domain\Exception\EntityNotFoundException;
use Domain\Exception\InvalidSizeException;
use Domain\Model\Cell;
use Domain\Model\CellStatus;
use Domain\Model\Coordinate;
use Domain\Model\Entities\GameStatusId;
use Domain\Model\Ports\GameStatusRepositoryInterface;

final class FromStoragePopulateStrategy implements PopulateStrategyInterface
{
    /** @var GameStatusRepositoryInterface */
    private $gameStatusRepository;

    /** @var GameStatusId */
    private $gameStatusId;

    /**
     * @param GameStatusRepositoryInterface $gameStatusRepository
     * @param GameStatusId                  $gameStatusId
     */
    public function __construct(GameStatusRepositoryInterface $gameStatusRepository, GameStatusId $gameStatusId)
    {
        $this->gameStatusRepository = $gameStatusRepository;
        $this->gameStatusId         = $gameStatusId;
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
        $gameStatus = $this->gameStatusRepository->find($this->gameStatusId);

        $gameStatusArray = unserialize($gameStatus->getStatus());

        $populatedGrid = [];
        for ($y = 0; $y < count($boardGrid); $y++) {
            $populatedGrid[] = [];

            for ($x = 0; $x < count($boardGrid[$y]); $x++) {
                $populatedGrid[count($populatedGrid) - 1][] = $this->populateCell($gameStatusArray, $y, $x);
            }
        }

        return $populatedGrid;
    }

    /**
     * @param array $gameStatusArray
     * @param int   $y
     * @param int   $x
     *
     * @return Cell
     *
     * @throws InvalidSizeException
     */
    private function populateCell(array $gameStatusArray, int $y, int $x): Cell
    {
        if (!isset($gameStatusArray[$y][$x])) {
            throw new InvalidSizeException();
        }

        $coordinate = new Coordinate($y, $x);
        $cellStatus = new CellStatus($gameStatusArray[$y][$x]);

        return new Cell($coordinate, $cellStatus);
    }
}
