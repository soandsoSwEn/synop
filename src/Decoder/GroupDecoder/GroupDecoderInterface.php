<?php

namespace Synop\Decoder\GroupDecoder;

use Synop\Fabrication\ValidateInterface;

/**
 * The interface should be implemented in a class that performs decoding of individual groups
 * of this section of the weather report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface GroupDecoderInterface
{
    /**
     * Checks the validity of the specified group.
     * The check is whether the intended group is desensitized.
     *
     * @param ValidateInterface $validate
     * @return bool
     */
    public function isGroup(ValidateInterface $validate) : bool;
}
