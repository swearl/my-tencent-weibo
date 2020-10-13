<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Api implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type');

        return $response;
    }
}
