<?php
declare(strict_types=1);

namespace Domain\Model\Entities;

use Ramsey\Uuid\Uuid;

final class SimulationStatusId
{
    /** @var string */
    private $id;

    /**
     * @param null $id
     */
    private function __construct($id = null)
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    /**
     * @param null $id
     *
     * @return SimulationStatusId
     */
    public static function create($id = null): SimulationStatusId
    {
        return new static($id);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }
}
