<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthorModel extends Model {
    protected $table = 'authors';

    protected $allowedFields = ['name'];

    protected $validationRules = [
        'name' => 'required|is_unique[authors.name]',
    ];

    protected $validationMessages = [
        'name' => [
            'is_unique' => '昵称已存在',
        ],
    ];
}
