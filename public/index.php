<?php

use Pecee\SimpleRouter\SimpleRouter;


require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../routes/web.php';

// Start the routing
try {
    SimpleRouter::start();
} catch (Exception $e) {
    return response()->json([
                                'message' => 'Not found',
                                'code' => 404,
                                'data' => [],
                            ]);
}
