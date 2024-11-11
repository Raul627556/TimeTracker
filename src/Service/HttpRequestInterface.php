<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

interface HttpRequestInterface
{
    public function request(string $url, $data = null): JsonResponse;
}
