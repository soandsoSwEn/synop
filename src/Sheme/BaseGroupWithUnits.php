<?php

namespace Synop\Sheme;

use Exception;
use Synop\Fabrication\Unit;
use Synop\Fabrication\UnitInterface;
use Synop\Sheme\GroupInterface;

/**
 * BaseGroupWithUnits class contains methods for working with the current group with units
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class BaseGroupWithUnits implements GroupWithUnitsInterface
{
    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;

    /**
     * Sets the value of the Unit object
     * @param UnitInterface $unit class instance of the entity Unit
     */
    public function setUnit(UnitInterface $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * Returns the value of the Unit object
     * @return UnitInterface
     */
    public function getUnit() : UnitInterface
    {
        return $this->unit;
    }

    /**
     * Returns unit data for the weather report group
     * @return array|null
     */
    public function getUnitValue() : ?array
    {
        return $this->getUnit()->getUnitByGroup(get_class($this));
    }
}
