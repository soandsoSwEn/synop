<?php

namespace Soandso\Synop\Fabrication;

use Soandso\Synop\Sheme\GroupInterface;

/**
 * UnitInterface must be implemented by a class that works with the units of measurement of meteorological parameters
 * of all groups of the weather report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface UnitInterface
{
    /**
     * Sets the unit of measurement for the group parameter to other than the default
     *
     * @param string $group Fully-qualified name for a class of group of weather report
     * @param string $parameter Meteorological parameter of this weather report group
     * @param string $value Parameter value
     */
    public function setUnit(string $group, string $parameter, string $value) : void;

    /**
     * Returns the unit of measure for all parameters of the given weather group
     *
     * array['parameter 1' => 'value of parameter 1', 'parameter 2' ...]
     *
     * @param string $group Fully-qualified name for a class of group of weather report
     * @return array|null
     */
    public function getUnitByGroup(string $group) : ?array;
}
