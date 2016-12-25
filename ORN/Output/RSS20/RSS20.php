<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/7/15
 * Time: 6:32 PM
 */

namespace ORN\Output\RSS20;

/**
 * Class RSS20
 * @package RSS20
 */
abstract class RSS20 extends \stdClass {
    /**
     * @var $title string
     */
    protected $title;

    /**
     * @var $link string
     */
    protected $link;

    /**
     * @var $description string
     */
    protected $description;

    /**
     * @param $title
     * @param $link
     * @param $description
     */
    function __construct($title, $link, $description) {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return htmlspecialchars($this->title);
    }

    /**
     * @param $title string
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @param $link string
     */
    public function setLink($link) {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return htmlspecialchars($this->description);
    }

    /**
     * @param $description string
     */
    public function setDescription($description) {
        $this->description = $description;
    }

}
