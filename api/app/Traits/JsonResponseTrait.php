<?php
declare(strict_types=1);

namespace App\Traits;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

trait JsonResponseTrait
{
    public function successJsonResponse($message, $data = [], $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message,
            'data'      => $data,
        ], $statusCode);
    }

    public function errorJsonResponse($message, $statusCode = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message
        ], $statusCode);
    }

    public function unAuthenticatedJsonResponse($message, $statusCode = Response::HTTP_FORBIDDEN): JsonResponse
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message
        ], $statusCode);
    }

    public function createdJsonResponse($message, $data = [], $statusCode = Response::HTTP_CREATED): JsonResponse
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message,
            'data'      => $data,
        ], $statusCode);
    }

    public function badJsonResponse($message, $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message
        ], $statusCode);
    }
}