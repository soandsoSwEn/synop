<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Sheme\SectionInterface;
use Exception;

/**
 * Description of Section
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Section implements SectionInterface
{
    /**
     * @var string Title of the section of the meteorological report
     */
    private $title;

    /**
     * @var array Array for storing data for sections of the weather report
     */
    private $body = [];

    public function __construct(string $title)
    {
        $this->setTitle($title);
    }

    /**
     * Sets a title of the section of the meteorological report
     * @param string $title
     * @throws Exception
     */
    public function setTitle(string $title) : void
    {
        if(!empty($title)) {
            $this->title = $title;
        } else {
            throw new Exception('Meteorological section title cannot be an empty string!');
        }
    }

    /**
     * Returns title of the section of the meteorological report
     * @return string
     * @throws Exception
     */
    public function getTitle() : string
    {
        if(!is_null($this->title) && !empty($this->title)) {
            return $this->title;
        } else {
            throw new Exception('Incorrect meteorological section title format!');
        }
    }

    /**
     * Adds a data for the section of the meteorological report
     * @param array|string $data Data of the section of the meteorological report
     */
    public function setBody($data) : void
    {
        $this->body[] = $data;
    }

    /**
     * Returns data for all sections of the meteorological report
     * @return array
     */
    public function getBody() : array
    {
        return $this->body;
    }

    /**
     * Returns data for a section by its title
     * @param string $titleSection Title of section of the meteorological report
     * @return false|mixed
     */
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
