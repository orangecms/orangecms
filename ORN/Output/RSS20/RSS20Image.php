<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/7/15
 * Time: 6:46 PM
 */

namespace ORN\Output\RSS20;

/**
 * Class RSS20Image
 * @package RSS20
 */
class RSS20Image {
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $link;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @param string $url
     * @param string $title
     * @param string $link
     */
    function __construct($url, $title, $link)
    {
        $this->url = $url;
        $this->title = $title;
        $this->link = $link;
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Maximum value for width is 144, default value is 88.
     *
     * @param int $width
     */
    public function setWidth($width = 88)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Maximum value for height is 400, default value is 31.
     *
     * @param int $height
     */
    public function setHeight($height = 31)
    {
        $this->height = $height;
    }
}
