<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/8/15
 * Time: 12:07 AM
 */

namespace ORN\Output\RSS20;

/**
 * Class RSS20ItemEnclosure
 *
 * @package RSS20\RSS20Item
 */
class RSS20ItemEnclosure {

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $length;

    /**
     * @var string
     */
    private $type;

    /**
     * @param $url
     * @param $length
     * @param $type
     */
    function __construct($url, $length, $type)
    {
        $this->url = $url;
        $this->length = $length;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * length of the file in bytes
     *
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * MIME type of the file
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
