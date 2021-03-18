<?php


namespace Synop\Fabrication;


use Synop\Sheme\GroupInterface;
use Synop\Sheme\SectionInterface;

/**
 * This interface should be implemented by a class that generates the individual data of
 * the meteorological weather report
 *
 * @package Synop\Fabrication
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface PartDataInterface
{
    /**
     * Returns all weather groups for a given section
     *
     * @param SectionInterface $sectionData All Sections of meteorological report
     * @param string $titleSection Preset section
     * @return array
     */
    public function getBodyOfSection(SectionInterface $sectionData, string $titleSection) : ?array;

    /**
     * Returns object of weather report group
     *
     * @param array $groupsData All groups of a specific section
     * @param string $groupItem The name of the class whose object you want to get
     * @return GroupInterface|null
     */
    public function getGroupData(array $groupsData, string $groupItem) : ?GroupInterface;
}