<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/7/15
 * Time: 6:32 PM
 */

namespace ORN\Output\RSS20;

/**
 * Class RSS20Channel
 * @package RSS20
 */
class RSS20Channel extends RSS20 {
    /**
     * @var RSS20Image
     */
    private $image;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var RSS20Item[]
     */
    private $items = array();

    /**
     * @return RSS20Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param RSS20Image $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Time to live in minutes
     *
     * @param int $ttl
     */
    public function setTtl($ttl = 60)
    {
        $this->ttl = $ttl;
    }

    /**
     * @return RSS20Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param RSS20Item[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param RSS20Item $item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }
}
