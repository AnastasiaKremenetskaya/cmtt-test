<?php

namespace App\Http\Responses;

use App\Models\Ad;

class AdResponse
{
    /**
     * @param Ad $ad
     * @return string
     */
    public function getSuccessResponse(Ad $ad): string
    {
        return response()->json([
            'message' => 'OK',
            'code' => 200,
            'data' => [
                "id" => $ad->getId(),
                "text" => $ad->getText(),
                "banner" => $ad->getBanner(),
            ],
        ]);
    }

    /**
     * @param array $errors
     * @return string
     */
    public function getErrorResponse(array $errors): string
    {
        return response()->json([
            'message' => $errors,
            'code' => 400,
            'data' => [],
        ]);
    }
}