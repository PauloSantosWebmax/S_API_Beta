<?php 

namespace Sage\Middlewares;

use Sage\Traits\Base;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AppServiceMiddleware
{
    use Base;
    
    public function __invoke(Request $request, Response $response, $next)
    {
        // $this->logger->addInfo('Api start', ['time' => date('d-m-Y H:i:s')]);

        return $next($request, $response);
    }
}
