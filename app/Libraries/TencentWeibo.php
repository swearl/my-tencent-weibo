<?php

namespace App\Libraries;

use CodeIgniter\CLI\CLI;
use Config\Mimes;
use Config\Services;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Exception;

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
     * parse images.
     *
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
        if (!empty($item['image-container'])) {
            $images = self::saveImages($item['image-container']);
            if (!empty($images)) {
                $data['images'] = json_encode($images);
            }
        }
        /** @var \App\Models\PostModel */
        $model = model('App\Models\PostModel');
        $model->insert($data);
        if (!empty($item['repost-content'])) {
            $parent_id = $model->getInsertID();
            self::saveItem($item['repost-content'], $parent_id);
        }
    }

    public static function saveImages($images) {
        $result = [];
        /** @var \App\Models\ImageModel */
        $model = model('App\Models\ImageModel');
        foreach ($images as $url) {
            $hash = self::getUrlHash($url);
            if (!$img = $model->getByHash($hash)) {
                CLI::write("下载 {$url}");
                try {
                    $img = self::downloadImage($url);
                } catch (Exception $e) {
                    CLI::write('下载出错, 出错原因: ' . $e->getMessage());
                    continue;
                }
            } else {
                CLI::write("{$hash} 存在, 跳过下载");
            }
            $file = empty($img['type']) ? $hash : $hash . '.' . $img['type'];
            $result[] = $file;
        }

        return $result;
    }

    public static function downloadImage($url) {
        $path = WRITEPATH . 'uploads/images/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $hash = self::getUrlHash($url);
        $client = Services::curlrequest();
        /** @var \CodeIgniter\HTTP\Response */
        $res = $client->get($url);
        $body = $res->getBody();
        $contentType = trim($res->getHeader('Content-Type')->getValue());
        $size = trim($res->getHeader('Content-Length')->getValue());
        $type = Mimes::guessExtensionFromType($contentType);
        $file = empty($type) ? "{$path}{$hash}" : "{$path}{$hash}.{$type}";
        file_put_contents($file, $body);
        model('App\Models\ImageModel')->insert(compact('hash', 'url', 'type', 'size'));

        return compact('hash', 'type');
    }

    public static function getUrlHash($url) {
        $array = explode('/', $url);
        array_pop($array);

        return array_pop($array);
    }
}
