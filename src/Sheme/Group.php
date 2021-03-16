<?php

namespace Synop\Sheme;

use Exception;
use Synop\Sheme\GroupInterface;

/**
 * Description of Group
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Group implements GroupInterface
{
    private $title;
    
    public function __construct(string $title)
    {
        $this->setTitle($title);
    }
    
    public function setTitle(string $title) : void
    {
        if(!empty($title)) {
            $this->title = $title;
        } else {
            throw new Exception('Meteorological section title cannot be an empty string!');
        }
    }
}
