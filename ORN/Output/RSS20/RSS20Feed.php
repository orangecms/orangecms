<?php

namespace ORN\Output\RSS20;

use SimpleXMLElement;

/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/15/15
 * Time: 10:08 PM
 */

/**
 * Class RSS20Feed
 * @package RSS20
 */
class RSS20Feed {
    /**
     * @param RSS20Channel $channel
     * @return SimpleXMLElement
     */
    public function generateFeed(RSS20Channel $channel) {
        $rssFeed = new SimpleXMLElement('<rss/>');
        $rssFeed->addAttribute('version', '2.0');
        $rssChannel = $rssFeed->addChild('channel');
        /* add mandatory elements */
        $rssChannel->addChild('title', $channel->getTitle());
        $rssChannel->addChild('link', $channel->getLink());
        $rssChannel->addChild('description', $channel->getDescription());
        /* add image */
        $rssImage = $rssChannel->addChild('image');
        $rssImage->addChild('url', $channel->getImage()->getUrl());
        $rssImage->addChild('title', $channel->getImage()->getTitle());
        $rssImage->addChild('link', $channel->getImage()->getLink());
        /* add items */
        foreach ($channel->getItems() as $item) {
            /**@var $item RSS20Item*/
            /* add mandatory elements */
            $rssItem = $rssChannel->addChild('item');
            $rssItem->addChild('title', $item->getTitle());
            $rssItem->addChild('link', $item->getLink());
            $rssItem->addChild('description', $item->getDescription());
            /* add date */
            $rssItem->addChild('pubDate', date('r', $item->getPubDate()->getTimestamp() - 10000));
            /* add enclosure */
            $enclosure = $item->getEnclosure();
            if ($enclosure instanceof RSS20ItemEnclosure) {
                $rssItemEnclosure = $rssItem->addChild('enclosure');
                $rssItemEnclosure->addAttribute('url', $enclosure->getUrl());
                $rssItemEnclosure->addAttribute('length', $enclosure->getLength());
                $rssItemEnclosure->addAttribute('type', $enclosure->getType());
            }
        }
        /* return result :) */
        return $rssFeed;
    }
}
