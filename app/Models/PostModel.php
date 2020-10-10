<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model {
    protected $table = 'posts';

    protected $allowedFields = ['parent_id', 'author', 'content', 'date', 'images'];
}