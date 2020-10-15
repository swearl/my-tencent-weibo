<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

/**
 * Weibo Controller.
 *
 * @property \App\Models\PostModel $model
 */
class Weibo extends ResourceController {
    protected $modelName = 'App\Models\PostModel';

    public function index() {
        $page = (int) $this->request->getGet('page');
        if ($page < 1) {
            $page = 1;
        }
        $data = $this->model->getPage($page);
        // echo $this->model->getLastQuery();
        // exit;

        return $this->respond($data);
    }
}
