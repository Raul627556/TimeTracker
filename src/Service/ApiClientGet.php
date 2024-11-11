<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiClientGet implements HttpRequestInterface
{
    const API_BASE_URL = 'http://time.trackerapi.com/';

    public function request(string $url, $data = null): JsonResponse
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::API_BASE_URL . $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPGET, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response === false) {
                return new JsonResponse(['error' => 'Request failed'], 500);
            }

            $decodedResponse = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse(['error' => 'Invalid JSON response'], 500);
            }

            if ($httpCode >= 400) {
                return new JsonResponse([
                    'error' => 'Request failed with status code ' . $httpCode,
                    'details' => $decodedResponse
                ], $httpCode);
            }

            return new JsonResponse($decodedResponse, $httpCode);
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}
