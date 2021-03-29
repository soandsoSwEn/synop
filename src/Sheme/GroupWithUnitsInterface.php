<?php


namespace Synop\Sheme;


use Synop\Fabrication\UnitInterface;

interface GroupWithUnitsInterface
{
    /**
     * Sets the current instance of the Unit for the weather report
     *
     * @param UnitInterface $unit The current instance of the Unit object
     */
    public function setUnit(UnitInterface $unit) : void;

    /**
     * Returns the current instance of the Unit for the weather report
     *
     * @return UnitInterface The current instance of the Unit object
     */
    public function getUnit() : UnitInterface;

    /**
     * Returns the units of measurement for the parameters of this group
     *
     * @return array|null
     */
    public function getUnitValue() : ?array;
}