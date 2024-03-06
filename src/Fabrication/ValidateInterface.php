<?php

namespace Soandso\Synop\Fabrication;

use Exception;

/**
 * This interface describes methods for validating weather reports
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface ValidateInterface
{
    /**
     * Performs initial preparation of weather reports for decoding
     *
     * @param string $report
     * @return string
     */
    public function preparation(string $report): string;

    /**
     * Performs a basic check of the entire weather report
     *
     * @return true
     * @throws Exception
     */
    public function check(): bool;
}
