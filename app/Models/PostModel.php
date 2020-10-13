<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model {
    protected $table = 'posts';

    protected $allowedFields = ['parent_id', 'author', 'content', 'date', 'images'];

    public function getPage($page = 1) {
        $limit = 10;
        $start = ($page - 1) * $limit;

        $data = $this->builder()
            ->select([
                'posts.author',
                'posts.content',
                'posts.date',
                'posts.images',
                'repost.author as repost_author',
                'repost.content as repost_content',
                'repost.date as repost_date',
                'repost.images as repost_images',
            ])
            ->join("{$this->table} as repost", 'posts.id=repost.parent_id', 'LEFT')
            ->where(['posts.parent_id' => 0])
            ->limit($limit, $start)
            ->get()
            ->getResultArray();
        if (!empty($data)) {
            $data = array_map(function ($item) {
                if (!is_null($item['images'])) {
                    $item['images'] = json_decode($item['images']);
                }
                if (!is_null($item['repost_images'])) {
                    $item['repost_images'] = json_decode($item['repost_images']);
                }
                $item['has_repost'] = is_null($item['repost_content']) ? false : true;

                return $item;
            }, $data);
        }

        return $data;
    }
}
