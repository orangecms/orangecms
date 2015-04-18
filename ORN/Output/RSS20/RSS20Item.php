<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/7/15
 * Time: 6:32 PM
 */

namespace ORN\Output\RSS20;

use DateTime;

/**
 * Class RSS20Item
 * @package RSS20
 */
class RSS20Item extends RSS20 {
    /**
     * @var string
     */
    private $author = "";

    /**
     * @var DateTime
     */
    private $pubDate = null;

    /**
     * @var RSS20ItemEnclosure
     */
    private $enclosure = null;

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return DateTime
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * @param DateTime $pubDate
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;
    }

    /**
     * @return RSS20ItemEnclosure
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param RSS20ItemEnclosure $enclosure
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }
}
