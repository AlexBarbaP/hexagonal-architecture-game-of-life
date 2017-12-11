<?php
declare(strict_types=1);

namespace Domain\Model\Entities;

final class SimulationStatus
{
    /** @var SimulationStatusId */
    private $id;

    /** @var string */
    private $status;

    /**
     * @param SimulationStatusId $id
     * @param string             $status
     */
    public function __construct(SimulationStatusId $id, string $status)
    {
        $this->id     = $id;
        $this->status = $status;
    }

    /**
     * @return SimulationStatusId
     */
    public function getId(): SimulationStatusId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
