<?php 

/**
 * ===========================================
 * TOKEN SETTINGS
 *
 */

return [
    'jwt' => [
        'iss' => getenv('JWT_ISS'), // issuer
        'aud' => getenv('JWT_AUD'), // audience
        'sub' => getenv('JWT_SUB'), // subject
        'iat' => time(), // issued at
        'nbf' => time() + 60, // not before
        'exp' => time() + 3600, // expiration
        'jti' => getenv('JWT_JTI'), // json token id
        'key' => getenv('API_KEY'), // api key
    ],
];
