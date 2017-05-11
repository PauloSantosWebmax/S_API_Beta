<?php 

/**
 * This trait depends of the Base trait,
 * because it's using the container instace 
 * and the getter
 */

namespace Sage\Traits;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

trait Common 
{
    /**
     * Log into app.log file some ocurrences in api
     *
     * @param string $name identifier of the log
     * @param array $data extra information to store
     * @return  void
     */
    public function log(string $name, array $data = [])
    {
        $this->logger->addInfo($name, $data);
    }

    /**
     *
     * @param Request $request psr7 instance
     * @param Response $response psr7 instance
     * @return mixed
     */
    public function checkAuthorizarionHeader(Request $request, Response $response)
    {
        // check if the request has a JWT (Authorization required)
        if (!$request->hasHeader('HTTP_AUTHORIZATION')) {
            return [
                'error' => 401,
                'message' => 'No JWT provided.',
            ];
        } else {
            // retieve token
            return $token = explode(' ', $request->getHeader('HTTP_AUTHORIZATION')[0])[1];
        }
    }

    /**
     *
     * @param Request $request psr7 instance
     * @param Response $response psr7 instance
     * @return mixed
     */
    public function checkQueryParam(Request $request, Response $response)
    {
        // check if the param has query
        if (!$request->getParam('query')) {
            return $response->withJson([
                'error' => 401,
                'message' => 'Invalid params',
            ], 201);
        }
    }

    /**
     * Responde with a json code
     *
     * @param Request $request psr7 instance
     * @param Response $response psr7 instance
     * @param int $error server code
     * @param string $message information message
     * @return json response
     */
    public function messageResponse(Request $request, Response $response, int $error, string $message)
    {
        return $response->withJson([
            'error' => $error,
            'message' => $message,
        ], 201);
    }
}
