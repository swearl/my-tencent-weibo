<?php

namespace App\Libraries;

use DOMDocument;
use DOMNodeList;
use DOMXPath;

/**
 * Tencent Weibo Library.
 */
class TencentWeibo {
    /**
     * load backup file.
     *
     * @param string $file
     *
     * @return \DOMDocument
     */
    public static function loadBackupFile($file) {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTMLFile($file);

        return $dom;
    }

    /**
     * get items.
     *
     * @return \DOMNodeList
     */
    public static function getItems(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        return $xpath->query("//div[contains(@class, 'item')]");
    }

    /**
     * parse item.
     *
     * @return array
     */
    public static function parseItem(DOMNodeList $nodes) {
        $item = [];
        /** @var \DOMElement $node */
        foreach ($nodes as $node) {
            $className = $node->getAttribute('class');
            switch ($className) {
                case 'repost-content':
                    $item[$className] = self::parseItem($node->childNodes);
                    break;
                case 'image-container':
                    if ($node->hasChildNodes()) {
                        $item[$className] = self::parseImages($node->childNodes);
                    }
                    break;
                default:
                    $item[$className] = trim($node->textContent);
                    break;
            }
        }

        return $item;
    }

    /**
     * parse images
     *
     * @param DOMNodeList $nodes
     * @return array
     */
    public static function parseImages(DOMNodeList $nodes) {
        $images = [];
        /** @var \DOMElement $node */
        foreach ($nodes as $node) {
            $images[] = $node->getAttribute('src');
        }

        return $images;
    }

    public static function saveItem($item, $parent_id = 0) {
        $data = [
            'parent_id' => $parent_id,
            'author' => $item['author-name'],
            'content' => $item['post'],
            'date' => $item['date'],
        ];
        if(!empty($item['image-container'])) {
            $data['images'] = json_encode($item['image-container']);
        }
        /** @var \App\Models\PostModel */
        $model = model('App\Models\PostModel');
        $model->insert($data);
        if(!empty($item['repost-content'])) {
            $parent_id = $model->getInsertID();
            self::saveItem($item['repost-content'], $parent_id);
        }
    }
}
