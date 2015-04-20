<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/15/15
 * Time: 10:17 PM
 */

namespace ORN\Output;

/* from PHP */
use DateTime;

/* RSS20 classes */
use finfo;
use ORN\DB\Posts;
use ORN\MediaFile;
use ORN\Output\RSS20\RSS20Channel;
use ORN\Output\RSS20\RSS20Feed;
use ORN\Output\RSS20\RSS20Image;
use ORN\Output\RSS20\RSS20Item;
use ORN\Output\RSS20\RSS20ItemEnclosure;

/**
 * Class RSS
 */
class RSSOutput implements OutputInterface {
    /** @var RSS20Channel */
    private $channel;

    /**
     * @param array $route
     * @param array $params
     * @return bool
     */
    public function prepare($route, $params) {
        date_default_timezone_set('Europe/Berlin');
        $channelName = isset($route[0]) ? $route[0] : null;
        if (DEBUG) var_dump($channelName);
        if (DEBUG && VERBOSE) var_dump($route);
        $postsRepo = new Posts();
        $posts = $postsRepo->getPostsByTag($channelName);
        /* create channel */
        global $config;
        $baseURL = $config['env']['base_URL'];
        /* basic channel setup */
        $title = $config['blog']['title'];
        if ($channelName) {
            $title .= ' - ' . $channelName;
        }
        $this->channel = new RSS20Channel(
            $title,
            $baseURL,
            $config['blog']['description']
        );
        /* channel image */
        $image = new RSS20Image(
            $baseURL . '/media/image/channel.png',
            $config['blog']['title'],
            $baseURL
        );
        $this->channel->setImage($image);
        /* add channel items */
        if (is_array($posts) && sizeof($posts) > 0) {
            foreach ($posts as $post) {
                $this->channel->addItem($this->createRSSItem($post, $baseURL));
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    function out()
    {
        $rssFeed = new RSS20Feed();
        /* RSS header */
        header('Content-Type: application/rss+xml; charset=utf-8');
        echo $rssFeed->generateFeed($this->channel)->asXML();
        return true;
    }

    /**
     * @param $post
     * @param $baseURL
     * @return RSS20Item
     */
    private function createRSSItem($post, $baseURL)
    {
        /* create basic item */
        $id = $post['id'];
        $item = new RSS20Item(
            $post['title'],
            $baseURL . '/blog/' . $id,
            $post['text']
        );
        /* add date */
        $item->setPubDate(new DateTime($post['date']));
        /* append media enclosure if defined */
        $fileName = $post['media'];
        if ($fileName) {
            global $config;
            $appDir = $config['env']['app_root_dir'];
            $filePath = '';
            /* search and verify file */
            $imagePath = 'media/image/' . $id . $fileName;
            $audioPath = 'media/audio/' . $id . $fileName;
            if ($type = MediaFile::imageFileType(
                /* try image file */
                $appDir . '/' . $imagePath, $fileName
            )) {
                $filePath = $imagePath;
            } else if ($type = MediaFile::audioFileType(
                /* try audio file */
                $appDir . '/' . $audioPath, $fileName
            )) {
                $filePath = $audioPath;
            }
            /* append to item */
            if ($filePath && $type) {
                $item->setEnclosure(new RSS20ItemEnclosure(
                    $baseURL . '/' . $filePath,
                    filesize($appDir . '/' . $filePath),
                    $type
                ));
            }
        }

        return $item;
    }
}
