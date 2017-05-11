<?php 

namespace Sage\Controllers;

use Sage\Traits\{Base, Common, TokenBuilder, FilesCopier};
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ApiController
{
    use Base, Common, TokenBuilder, FilesCopier;

    /**
     * Main method
     *
     * @return mixed
     */
    public function index(Request $request, Response $response, array $args = [])
    {   
        // check for the presence of the token 
        $token = $this->checkAuthorizarionHeader($request, $response);

        // check for response
        if (is_array($token)) {
            return $response->withJson($token, 201);
        }
		
		// only for debug
		// dd( $request->getHeader('HTTP_AUTHORIZATION') );
		

        // convert token into JWT object
        $token = $this->parser->parse((string) $token);

        // $validator = $this->validator;
        // $validator->setId($this->configs->get('jwt')['jti']);
        // $validator->setIssuer($this->configs->get('jwt')['iss']);
        // $validator->setAudience($this->configs->get('jwt')['aud']);
        // $validator->setSubject($this->configs->get('jwt')['sub']);
        // $validator->setCurrentTime($this->configs->get('jwt')['exp']);

        // validate the token signature
        if(!$token->verify($this->signer, env('API_KEY'))) {

            // respond with error
            return $this->messageResponse($request, $response, 401, 'Invalid token');
        }

        // check if the param has query
        if (!$request->getParam('query')) {

            // respond with error
            return $this->messageResponse($request, $response, 401, 'Invalid params');
        }

        // SQL query to execute
        $sql_query = trim($request->getParam('query'));

        // prepare pdo statement
        $data = $this->sqlserver->prepare($sql_query);
        $data->execute();
        $data = $data->fetchAll();
		
		// recopy all files to public directory
		$this->initializeCopy();
				
        // respond with data
        return $response->withJson($data, 201);
    }

    /**
     * Sign the user and return a valid json webtoken
     *
     * The params should be passed via POST
     *
     * @param ['username', 'password']
     * @return string
     */
    public function signin(Request $request, Response $response, array $args = [])
    {
        // credentials
        $name = trim($request->getParam('username'));
        $password = trim($request->getParam('password'));
		
        // attemp login
        if ($name != env('API_NAME') || $password != env('API_PASSWORD')) {

            // log the IP of the failed user attemp 
            $this->log('Invalid authentication.', [
                'IP_ADDRESS' => $request->getAttribute('ip_address'),
                'DATE' => date('d-m-Y H:i:s'),
            ]);

            // respond with error
            return $this->messageResponse($request, $response, 401, 'Invalid credentials');
        }

        // generate token
        $token = $this->generateToken();

        // prin token
        echo $token;
    }
}
