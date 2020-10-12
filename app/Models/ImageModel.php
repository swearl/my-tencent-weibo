<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model {
    protected $table = 'images';

    protected $allowedFields = ['hash', 'url', 'type', 'size'];

    protected $validationRules = [
        'hash' => 'required|is_unique[images.hash]',
    ];

    public function getByHash($hash) {
        return $this->select('hash, type')->where(['hash' => $hash])->first();
    }
}
