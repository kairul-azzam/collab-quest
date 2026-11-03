<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'To-Do List API',
    version: '1.0.0',
    description: 'REST API for To-Do List Application'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local development server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum'
)]

#[OA\Get(
    path: '/api/profile',
    operationId: 'getProfile',
    summary: 'Get authenticated user profile',
    tags: ['Authentication'],
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Profile retrieved successfully'
        ),
        new OA\Response(
            response: 401,
            description: 'Unauthenticated'
        )
    ]
)]


class ApiDocumentation
{
}