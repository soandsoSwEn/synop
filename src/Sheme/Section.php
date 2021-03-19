<?php

namespace Synop\Sheme;

use Synop\Sheme\SectionInterface;
use Exception;

/**
 * Description of Section
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Section implements SectionInterface
{
    private $title;
    
    private $body = [];

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
    
    public function getTile() : string
    {
        if(!is_null($this->title) && !empty($this->title)) {
            return $this->title;
        } else {
            throw new Exception('Incorrect meteorological section title format!');
        }
    }
    
    public function setBody($data, $key = false) : void
    {
        if ($key) {
            $this->body[$key] = $data;
        } else {
            $this->body[] = $data;
        }
    }
    
    public function getBody() : array
    {
        return $this->body;
    }

    public function getBodyByTitle(string $titleSection)
    {
        $body =  $this->getBody();
        foreach ($body as $key => $value) {
            if (strcasecmp($titleSection, $value->title) == 0) {
                return $body[$key];
            }
        }

        return false;
    }
}
